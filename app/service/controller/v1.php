<?php

namespace app\service\controller;

use app\defaults\controller\application;
use app\crud\controller\custom as crud;

class v1 extends application{ 
	
	public function __construct(){
		parent::__construct();
		$this->data['url_path'] = $this->link($this->getProject().$this->getController());
	}

	protected function index(){
		$this->showResponse($this->errorMsg, 404);
	}

	protected function script(){
		header('Content-Type: application/javascript');
		$this->subView('script', $this->data);
	}

	protected function crud($id){
		$crud = new crud();
		if((int)method_exists($crud, $id) > 0) echo $crud->{$id}();
		else $this->showResponse($this->errorMsg, 404);
	}

}
?>
