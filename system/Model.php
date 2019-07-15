<?php
/**
 * frameduzPHP v6
 *
 * @Author  	: M. Hanif Afiatna <hanif.softdev@gmail.com>
 * @Since   	: version 6.0.0
 * @Date		: 21 Mei 2019
 * @package 	: core system
 * @Description : 
 */
 
namespace system;
use PDO, PDOException;

class Model{
	
	public function __construct(){
		$this->databaseConfig = Config::Load('database');
		$this->defaultValue = array();
		$this->con = 'null';
		$this->db = null;
		
		$this->getUrl = new Url();
		$this->files = new Files();
		$this->project = $this->getUrl->ProjectName;
		$this->session = $this->getUrl->mainConfig['project'][$this->project]['session'];
		$this->cookie = $this->getUrl->mainConfig['project'][$this->project]['cookie'];
		$setting = $this->getUrl->mainConfig['setting'];
		if(!empty($setting)){
			foreach($setting as $set => $value) $this->{$set} = $value;
		}
	} 
	
	protected function setConnection($con){
		$this->con = $con;
	}
	
	protected function setDefaultValue($val = array()){
		$this->defaultValue = $val;
	}
	
	private function openConnection(){
		if(isset($this->databaseConfig[$this->con]) && !empty($this->databaseConfig[$this->con])){
			$dsn = $this->databaseConfig[$this->con]['driver'] . 
				   ':host=' . $this->databaseConfig[$this->con]['host'] . 
				   ';port=' . $this->databaseConfig[$this->con]['port'] . 
				   ';dbname=' . $this->databaseConfig[$this->con]['dbname'];	
			$opt[PDO::ATTR_PERSISTENT] = $this->databaseConfig[$this->con]['persistent'];
			try{$this->db = new PDO($dsn, $this->databaseConfig[$this->con]['user'], $this->databaseConfig[$this->con]['password'], $opt);}
			catch(PDOexception $err){
				header("HTTP/1.1 200");
		        header("Content-Type:application/json");
		        $errorMsg = array('status' => 'failed', 'data' => $err->getMessage());
		        // $errorMsg = array('status' => 'failed', 'data' => $this->databaseConfig[$this->con]['errorMsg']);
		        echo json_encode($errorMsg);
				die;
			}
		}else{
			header("HTTP/1.1 200");
	        header("Content-Type:application/json");
	        $errorMsg = array('status' => 'Error', 'data' => 'Maaf, '.$this->con.' belum dikonfigurasikan silahkan check di file config/database.php');
	        echo json_encode($errorMsg);
			die;
		}
	}
	
	private function closeConnection(){
		$this->db = null;
	}
	
	private function sql_debug($sql_string, array $params = null){
		if(!empty($params)){
			$indexed = $params == array_values($params);
			foreach($params as $k=>$v){
				if (is_object($v)){
					if ($v instanceof \DateTime) $v = $v->format('Y-m-d H:i:s');
					else continue;
				}
				else if(is_string($v)) $v="'$v'";
				else if($v === null) $v='NULL';
				else if(is_array($v)) $v = implode(',', $v);
	
				if($indexed){
					$sql_string = preg_replace('/\?/', $v, $sql_string, 1);
				}
				else{
					if($k[0] != ':') $k = ':'.$k;
					$sql_string = str_replace($k,$v,$sql_string);
				}
			}
		}
		return $sql_string;
	}

	public function checkConnection(){
		$this->openConnection();
	}

	public function getDataTabel($tabel, $id = array()){
		set_time_limit(0);
		if(!empty($id)){
			$data = $this->getData('SELECT * FROM '.$tabel.' WHERE ('.$id[0].' = ?) ', array($id[1]));
			if($data['count'] > 0) $result =  $data['value'][0];
			else $result = $this->getTabel($tabel);
		}
		else
			$result = $this->getTabel($tabel);
		return $result;
	}
	
	public function getTabel($tabel){
		$result = $this->getData('SHOW COLUMNS FROM '.$tabel);
		$defaultValue = $this->defaultValue;
		$dataTabel = array();
		foreach($result['value'] as $kol){$dataTabel[$kol['Field']] = '';}
		foreach($dataTabel as $key => $value){if(isset($defaultValue[$key])) $dataTabel[$key] = $defaultValue[$key];}
		return $dataTabel;
    }
	
	public function getData($query, $arrData = array()){
		if(is_null($this->db)) $this->openConnection();
		$sql_stat = $this->db->prepare($query);
		$sql_stat->execute($arrData);
		$sql_value = $sql_stat->fetchAll(PDO::FETCH_ASSOC);
		$sql_count = $sql_stat->rowCount();		
		$sql_query = $this->sql_debug($query, $arrData);
		$this->closeConnection();
		return array(
			'value' => $sql_value,
			'count' => $sql_count,
			'query' => $sql_query.';',
		);
	}
	
	public function save($tabel, $arrData){
		if(is_null($this->db)) $this->openConnection();
		foreach($arrData as $key => $value) $keys[] = ':' . $key;
		$valTable = implode(', ',$keys);
		$query = 'INSERT INTO ' . $tabel . ' VALUES (' . $valTable . ')';
		$error = 0;
		$message = '';
		try{
			$sql_stat = $this->db->prepare($query);
			$error = $sql_stat->execute($arrData);
			$message = $sql_stat->errorInfo();
		}
		catch(Exception $err){}
		$this->closeConnection();
		$sql_query = $this->sql_debug($query, $arrData);
		return array(
			'error' => $error,
			'message' => $message[2],
			'query' => $sql_query.';',
		);
	}
	
	public function save_update($tabel, $arrData){
		if(is_null($this->db)) $this->openConnection();
		$col = array();
		$val = array();
		$dup = array();
		$data = array();
		foreach($arrData as $key => $value){
			array_push($col, $key);
			array_push($val, '?');
			array_push($data, $value);
			array_push($dup, $key.'=VALUES('.$key.')');
		}
		$colTable = '('.implode(',', $col).')';
		$valTable = '('.implode(',', $val).')';
		$dupTable = implode(',', $dup);	
		$query = 'INSERT INTO ' . $tabel . ' ' . $colTable . ' VALUES ' . $valTable . ' ON DUPLICATE KEY UPDATE '. $dupTable;
		$error = 0;
		$message = '';
		try{
			$this->db->beginTransaction();
			$sql_stat = $this->db->prepare($query);
			$error = $sql_stat->execute($data);
			$message = $sql_stat->errorInfo();
			$this->db->commit();
		}
		catch(Exception $err){
			$this->db->rollback();
		}
		$this->closeConnection();
		$sql_query = $this->sql_debug($query, $data);
		return array(
			'error' => $error,
			'message' => $message[2],
			'query' => $sql_query.';',
		);
	}

	public function save_update_($tabel, $arrData){
		if(is_null($this->db)) $this->openConnection();
		foreach($arrData as $key => $value) $keys[] = $key . '= :' . $key;
		$valTable = implode(', ',$keys);
		$query = 'INSERT INTO ' . $tabel . ' SET ' . $valTable . ' ON DUPLICATE KEY UPDATE ' . $valTable;
		$error = 0;
		$message = '';
		try{
			$this->db->beginTransaction();
			$sql_stat = $this->db->prepare($query);
			$error = $sql_stat->execute($arrData);
			$message = $sql_stat->errorInfo();
			$this->db->commit();
		}
		catch(Exception $err){
			$this->db->rollback();
		}
		$this->closeConnection();
		$sql_query = $this->sql_debug($query, $arrData);
		return array(
			'error' => $error,
			'message' => $message[2],
			'query' => $sql_query.';',
		);
	}

	public function save_update_all($tabel, $rowData){
		/**
		 * show variables like 'max_allowed_packet';
		 * SET GLOBAL max_allowed_packet=524288000; //500mb
		 * max_rows=5000000000;
		 * 
		 * Output : Multi Values statement
		 * 
		*/


		if(is_null($this->db)) $this->openConnection();
		$rows = count($rowData);
		$col = array();
		$val = array();
		$dup = array();
		$arrData = array();
		foreach($rowData[0] as $key => $value){
			array_push($col, $key);
			array_push($val, '?');
			array_push($dup, $key.'=VALUES('.$key.')');
		}
		foreach($rowData as $key => $value){
			array_walk_recursive($value, function($item) use (&$arrData) { $arrData[] = $item; });
		}
		$colTable = '('.implode(',', $col).')';
		$valTable = '('.implode(',', $val).'),';
		$valTable = rtrim(str_repeat($valTable, $rows), ',');
		$dupTable = implode(',', $dup);	
		$query = 'INSERT INTO ' . $tabel . ' ' . $colTable . ' VALUES ' . $valTable . ' ON DUPLICATE KEY UPDATE '. $dupTable;
		$error = 0;
		$message = '';
		try{
			$sql_stat = $this->db->prepare($query);
			$error = $sql_stat->execute($arrData);
			$message = $sql_stat->errorInfo();
		}
		catch(Exception $err){}
		$this->closeConnection();
		$sql_query = $this->sql_debug($query, $arrData);
		return array(
			'error' => $error,
			'message' => $message[2],
			'query' => $sql_query.';',
		);
	}

	public function save_update_all_($tabel, $rowData){
		/**
		 * show variables like 'max_allowed_packet';
		 * SET GLOBAL max_allowed_packet=524288000; //500mb
		 * max_rows=5000000000;
		 * 
		 * Output : Multi Insert statement
		 * 
		*/

		if(is_null($this->db)) $this->openConnection();
		$rows = count($rowData);
		$col = array();
		$val = array();
		$dup = array();
		$arrData = array();
		foreach($rowData[0] as $key => $value){
			array_push($col, $key);
			array_push($val, '?');
			array_push($dup, $key.'=VALUES('.$key.')');
		}
		foreach($rowData as $key => $value){
			array_walk_recursive($value, function($item) use (&$arrData) { $arrData[] = $item; });
		}
		$colTable = '('.implode(',', $col).')';
		$valTable = '('.implode(',', $val).')';
		$dupTable = implode(',', $dup);	
		$query = 'INSERT INTO ' . $tabel . ' ' . $colTable . ' VALUES ' . $valTable . ' ON DUPLICATE KEY UPDATE '. $dupTable.';';
		$query = rtrim(str_repeat($query, $rows), ';');
		$error = 0;
		$message = '';
		try{
			$sql_stat = $this->db->prepare($query);
			$error = $sql_stat->execute($arrData);
			$message = $sql_stat->errorInfo();
		}
		catch(Exception $err){}
		$this->closeConnection();
		$sql_query = $this->sql_debug($query, $arrData);
		return array(
			'error' => $error,
			'message' => $message[2],
			'query' => $sql_query.';',
		);
	}
	
	public function update($tabel, $arrData, $idKey){
		if(is_null($this->db)) $this->openConnection();
		foreach($arrData as $key => $value) $keys1[] = $key . ' = :' . $key;
		$valTable = implode(', ',$keys1);
		foreach($idKey as $key => $value) $keys2[] = '(' . $key . '= :' . $key .')';
		$keyTable = implode(' AND ',$keys2);
		$query = 'UPDATE ' . $tabel . ' SET ' . $valTable . ' WHERE ' . $keyTable;
		$error = 0;
		$message = '';
		try{
			$sql_stat = $this->db->prepare($query);
			$error = $sql_stat->execute(array_merge($arrData, $idKey));
			$message = $sql_stat->errorInfo();
		}
		catch(Exception $err){}
		$this->closeConnection();
		$sql_query = $this->sql_debug($query, array_merge($arrData, $idKey));
		return array(
			'error' => $error,
			'message' => $message[2],
			'query' => $sql_query.';',
		);
	}
	
	public function delete($tabel, $idKey){
		if(is_null($this->db)) $this->openConnection();
		foreach($idKey as $key => $value) $keys[] = '(' . $key . '= :' . $key .')';
		$keyTable = implode(' AND ',$keys);
		$query = 'DELETE FROM ' . $tabel . ' WHERE ' . $keyTable;
		$error = 0;
		$message = '';
		try{
			$sql_stat = $this->db->prepare($query);
			$error = $sql_stat->execute($idKey);
			$message = $sql_stat->errorInfo();
		}
		catch(Exception $err){}
		$this->closeConnection();
		$sql_query = $this->sql_debug($query, $idKey);
		return array(
			'error' => $error,
			'message' => $message[2],
			'query' => $sql_query.';',
		);
	}
	
	protected function setSession($name, $data){
		$_SESSION[$this->session][$name] = $data;
	}
	
	protected function getSession($name){
		return isset($_SESSION[$this->session][$name]) ? $_SESSION[$this->session][$name] : '';
	}
	
	protected function delSession($name){
		if(isset($_SESSION[$this->session][$name])) unset($_SESSION[$this->session][$name]);
	}
	
	protected function desSession(){
		if(isset($_SESSION[$this->session])) unset($_SESSION[$this->session]);
	}
	
	public function dropDB()
	{
		if(is_null($this->db)) $this->openConnection();
		$query = 'DROP database dbweb_simpeg';
		$error = 0;
		$message = '';
		try{
			$sql_stat = $this->db->prepare($query);
			$error = $sql_stat->execute($query);
			$message = $sql_stat->errorInfo();
		}
		catch(Exception $err){}
		$this->closeConnection();
		$sql_query = $this->sql_debug($query);
		return array(
			'error' => $error,
			'message' => $message[2],
			'query' => $sql_query.';',
		);
	}
}
?>