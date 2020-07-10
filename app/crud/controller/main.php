<?php

namespace app\crud\controller;

use app\defaults\controller\application;

class main extends application{
	
	public function __construct(){
		parent::__construct();
		$this->data['title'] = 'FrameduzV6';
		$this->data['project'] = array(
			array('name' => 'Simple CRUD', 'link' => $this->link('simple')),
			array('name' => 'Custom CRUD', 'link' => $this->link('custom')),
		);
	}

	protected function index(){
		$this->showView('index', $this->data, 'defaults');
	}

	protected function header($data){
		$this->data += $data;
        $this->subView('header', $this->data);
	}
	
	protected function modal($id){
		$this->subView('modal-'.$id, $this->data);
	}

}
?>
