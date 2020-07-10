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

class Controller{
	
	public function __construct(){
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
	
	public function runMethod($method, $idKey){
		return $this->{$method}($idKey);
	}
	
	/**
	 * Memanggil view dari controller project lain (menjalankan controller suatu project)
	 */
	public function getView($project, $controller, $method, $idKey){
		$PathController = 'app\\' . $project . '\\controller\\';
		$Controller = $controller;
		$ctrl = $PathController . $Controller;
		$this->setSession('getView', array('PathController' => $project, 'Controller' => $Controller));
		if(class_exists($ctrl)){
			if(method_exists($ctrl, $method)){
				$ctrl = new $ctrl();
				$ctrl->runMethod($method, $idKey);
			}	
			else{
				echo 'method not found';
			}
		}
		else{
			echo 'controller not found';
		}
	}

	/**
	 * Memanggil view dari project lain (requred file view)
	 */
	protected function projectView($project, $controller, $fileView = '', $data = array()){
		extract($data, EXTR_SKIP);
		$viewPath = APP . $project . '/view/' . $controller . '/' . $fileView . '.' . $controller . '.php';
		require_once $viewPath;
		unset($data);
	}
	
	protected function showView($fileView = '', $data = array(), $template = '', $validation = false){
		$Caller = $this->getCaller();
		$PathController = (!empty($Caller['PathController'])) ? $Caller['PathController'] : $this->getUrl->getPathController();
		$Controller = (!empty($Caller['Controller'])) ? $Caller['Controller'] : $this->getUrl->getController();
		$css = $this->getUrl->mainConfig['template'][$template]['css'];
		$js = $this->getUrl->mainConfig['template'][$template]['js'];
		$template = (empty($template)) ? $this->getUrl->mainConfig['template'][$this->getUrl->defaultTemplate]['basePath'] : $this->getUrl->mainConfig['template'][$template]['basePath'];
		$basePath = $this->getUrl->baseUrl . 'template/' . $template;

		$cssPath = '';
        foreach ($css as $key => $value) {
            $cssPath .= '<link rel="stylesheet" href="'.$value.'">'."\n";
        };

        $jsPath = '';
        foreach ($js as $key => $value) {
            $jsPath .= '<script src="'.$value.'"></script>'."\n";
        };

		extract($data, EXTR_SKIP);
		$viewPath = APP . $PathController . '/view/' . $Controller . '/' . $fileView . '.' . $Controller . '.php';
		require_once TEMPLATE . $template . 'template.php';
		unset($data, $template);
	}
	
	protected function subView($fileView = '', $data = array()){
		$caller = $this->getSession('getView');
		$PathController = !empty($caller['PathController']) ? $caller['PathController'] : $this->getUrl->getPathController();
		$Controller = !empty($caller['Controller']) ? $caller['Controller'] : $this->getUrl->getController();
		extract($data, EXTR_SKIP);
		$viewPath = APP . $PathController . '/view/' . $Controller . '/' . $fileView . '.' . $Controller . '.php';
		require_once $viewPath;
		$this->delSession('getView');
		unset($data);
	}

	protected function getCaller(){
		$caller = debug_backtrace();
		$caller = $caller[2];
		$result['PathController'] = '';
		$result['Controller'] = '';
		if(isset($caller['object'])){
			$_caller = get_class($caller['object']);
			$_caller = explode('\\', $_caller);
			$result['PathController'] = strtolower($_caller[1]);
			$result['Controller'] = strtolower($_caller[3]);
		}
		return $result;
	}
	
	protected function getProject(){
		return ($this->getUrl->ProjectName == $this->getUrl->defaultProject) ? '' : $this->getUrl->ProjectName . '/';
	}
	
	protected function getController(){
		return $this->getUrl->getController();
	}

	protected function getMethod(){
		return $this->getUrl->getMethod();
	}

	protected function getID(){
		return $this->getUrl->getID();
	}
	
	protected function link($location = ''){
		return $this->getUrl->baseUrl . $location;
	}
	
	protected function activeLink($location = ''){
		return $this->getUrl->activeUrl . $location;
	}
	
	protected function redirect($location, $status = 302){
		$location = (empty($location)) ? $this->link() : $location;
		if(substr($location, 0, 4) != 'http')
			$location = $this->link() . $location;
		header('Location: ' . $location, true, $status);
		exit;
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
	
	protected function post($validation = false, $key = false, $filterType = false){
		if($validation === true){
			if(isset($_SESSION[$this->session])){
				if(!$key) return filter_var_array($_POST, FILTER_SANITIZE_ADD_SLASHES);       
				if($filterType) return filter_input(INPUT_POST, $key, $filterType);
				else return $_POST[$key];
			}
			else{
				return false;
			}
		}
		else{
			if(!$key) return filter_var_array($_POST, FILTER_SANITIZE_ADD_SLASHES);       
			if($filterType) return filter_input(INPUT_POST, $key, $filterType);
			else return $_POST[$key];
		}
    }
	
}
?>