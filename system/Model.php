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
		$this->errorMsg = array(
			'status' => 'error', 
			'message' => array(
				'title' => 'Oops', 
				'text' => '', 
			),
		);
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

	public function paramsFilter($params, $input) {
		foreach ($params as $key => $value) { if(isset($input[$key])) $params[$key] = $input[$key]; }
		return $params;
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
				header("HTTP/1.1 503");
		        header("Content-Type:application/json");
				$this->errorMsg['message']['text'] = $err->getMessage();
				// $this->errorMsg['text'] = $this->databaseConfig[$this->con]['errorMsg'];
		        echo json_encode($this->errorMsg);
				die;
			}
		}else{
			header("HTTP/1.1 503");
	        header("Content-Type:application/json");
			$this->errorMsg['message']['text'] = $this->con.' belum dikonfigurasikan silahkan check di file config/database.php';
		    echo json_encode($this->errorMsg);
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

	public function getData($query, $arrData = array()){
		set_time_limit(0);
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

	public function getTabel($tabel){
		set_time_limit(0);
		$result = $this->getData('SHOW COLUMNS FROM '.$tabel);
		$defaultValue = $this->defaultValue;
		$dataTabel = array();
		foreach($result['value'] as $kol){$dataTabel[$kol['Field']] = '';}
		foreach($dataTabel as $key => $value){if(isset($defaultValue[$key])) $dataTabel[$key] = $defaultValue[$key];}
		return $dataTabel;
    }

	public function getDataTabel($tabel, $id = array()){
		if(!empty($id)){
			$data = $this->getData('SELECT * FROM '.$tabel.' WHERE ('.$id[0].' = ?) ', array($id[1]));
			if($data['count'] > 0) $result =  $data['value'][0];
			else $result = $this->getTabel($tabel);
		}
		else
			$result = $this->getTabel($tabel);
		return $result;
	}
	
	public function getOrderNumber($tabel){
        $dataTabel = $this->getData('SELECT COUNT(*) AS jumlah FROM '.$tabel);
        if($dataTabel['count'] > 0){
            $order = intval($dataTabel['value'][0]['jumlah'])+1;
        }else{
            $order = 1;
        }
        return $order;
    }
	
	public function save($tabel, $arrData){
		if(is_null($this->db)) $this->openConnection();
		foreach($arrData as $key => $value) $keys[] = ':' . $key;
		$valTable = implode(', ',$keys);
		$query = 'INSERT INTO ' . $tabel . ' VALUES (' . $valTable . ')';
		$success = 0;
		$message = '';
		try{
			$sql_stat = $this->db->prepare($query);
			$success = $sql_stat->execute($arrData);
			$message = $sql_stat->errorInfo();
		}
		catch(Exception $err){}
		$this->closeConnection();
		$sql_query = $this->sql_debug($query, $arrData);
		return array(
			'success' => $success,
			'message' => $message[2],
			'query' => $sql_query.';',
		);
	}

	public function save_update($tabel, $arrData){
		if(is_null($this->db)) $this->openConnection();
		foreach($arrData as $key => $value) $keys[] = $key . '= :' . $key;
		$valTable = implode(', ',$keys);
		$query = 'INSERT INTO ' . $tabel . ' SET ' . $valTable . ' ON DUPLICATE KEY UPDATE ' . $valTable;
		$success = 0;
		$message = '';
		try{
			$this->db->beginTransaction();
			$sql_stat = $this->db->prepare($query);
			$success = $sql_stat->execute($arrData);
			$message = $sql_stat->errorInfo();
			$this->db->commit();
		}
		catch(Exception $err){
			$this->db->rollback();
		}
		$this->closeConnection();
		$sql_query = $this->sql_debug($query, $arrData);
		return array(
			'success' => $success,
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
		$success = 0;
		$message = '';
		try{
			$sql_stat = $this->db->prepare($query);
			$success = $sql_stat->execute(array_merge($arrData, $idKey));
			$message = $sql_stat->errorInfo();
		}
		catch(Exception $err){}
		$this->closeConnection();
		$sql_query = $this->sql_debug($query, array_merge($arrData, $idKey));
		return array(
			'success' => $success,
			'message' => $message[2],
			'query' => $sql_query.';',
		);
	}
	
	public function delete($tabel, $idKey){
		if(is_null($this->db)) $this->openConnection();
		foreach($idKey as $key => $value) $keys[] = '(' . $key . '= :' . $key .')';
		$keyTable = implode(' AND ',$keys);
		$query = 'DELETE FROM ' . $tabel . ' WHERE ' . $keyTable;
		$success = 0;
		$message = '';
		try{
			$sql_stat = $this->db->prepare($query);
			$success = $sql_stat->execute($idKey);
			$message = $sql_stat->errorInfo();
		}
		catch(Exception $err){}
		$this->closeConnection();
		$sql_query = $this->sql_debug($query, $idKey);
		return array(
			'success' => $success,
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
	
}
?>