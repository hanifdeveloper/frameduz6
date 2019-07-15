<?php

namespace app\crud\controller;

use app\defaults\controller\application;

class main extends application{
	
	public function __construct(){
		parent::__construct();
	}

	protected function index(){
		$data['title'] = 'FrameduzV6';
		$data['project'] = array(
			array('name' => 'Simple CRUD', 'link' => $this->link('simple')),
			array('name' => 'Custom CRUD', 'link' => $this->link('custom')),
		);
		$this->showView('index', $data, 'defaults');
	}

	protected function header($data){
        $this->subView('header', $data);
	}
	
	protected function modal($id){
		$data['title'] = '<!-- Modal -->';
		$this->subView('modal-'.$id, $data);
	}

	
	
}
?>
