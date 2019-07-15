<?php
namespace app\defaults\controller;

use app\defaults\controller\application;

class main extends application{
	
	public function __construct(){
		parent::__construct();
	}

	protected function index(){
		// 
	}

	protected function error($type){
		$data['title'] = 'Error 404';
		$data['message'] = $type.' Not Found';
		$this->showView('error', $data, 'defaults');
	}

	protected function maintenance(){
		$data['title'] = 'UNDER CONSTRUCTION';
		$data['message'] = 'Our website is under construction. We\'ll be here soon with our new awesome site, subscribe to be notified.';
		$this->showView('maintenance', $data, 'defaults');
	}

	public function fileAccess($file){
		$session_download = $this->getSession('SESSION_DOWNLOAD');
		$this->delSession('SESSION_DOWNLOAD');
		if(!empty($session_download)){
			$result = $this->files->download($session_download);
			$result = $session_download;
		}else{
			$result = array('status' => 'failed', 'message' => 'required permission');
		}
		$this->showResponse($result);
	}
	
}
?>
