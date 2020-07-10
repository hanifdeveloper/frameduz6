<?php

namespace app\crud\controller;

use app\defaults\controller\application;
use app\crud\model\crud;

class simple extends application{
	
	public function __construct(){
		parent::__construct();
		$this->crud = new crud();
		$this->id_ref = 'id_user';
		$this->table_ref = 'tb_user';
		$this->data['url_path'] = $this->link($this->getProject().$this->getController());
	}

	protected function index(){
		$this->data['pilihan_jenis_kelamin'] = array('' => array('text' => 'Semua')) + $this->crud->getJenisKelamin();
		$this->data['header']['page_title'] = 'Simple CRUD';
		$this->data['header']['description'] = 'Contoh penggunaan frameduz membuat aplikasi CRUD versi MVC';
		$this->data['header']['background'] = 'bg-dark';
		$this->showView('index', $this->data, 'defaults');
	}

	protected function script(){
		header('Content-Type: application/javascript');
		$this->subView('script', $this->data);
	}

	protected function table(){
		$input = $this->post(true);
		if($input){
			$data = $this->crud->getTabelUser($input);
			$this->subView('table', $data);
		}
	}

	protected function form(){
		$input = $this->post(true);
		if($input){
			$data = $this->crud->getFormUser($input['id']);
			$this->subView('form', $data);
		}
	}

	public function save(){
		$input = $this->post(false);
		if($input){
			$data = $this->crud->getDataTabel($this->table_ref, array($this->id_ref, $input[$this->id_ref]));
			$data = $this->crud->paramsFilter($data, $input);
			$upload = $this->uploadImage('foto', 'foto');
			
			// Check error upload
			if($upload['status'] != 'success'){
				$this->errorMsg['message']['text'] =  $upload['errorMsg'];
				$this->showResponse($this->errorMsg, $this->errorCode);
				die;
			}
			// Check Upload File
			if(!empty($upload['UploadFile'])) $data['foto_user'] = $upload['UploadFile'];
			// Check Input Array (Checkbox)
			if(is_array($data['hobby_user'])){
				$data['hobby_user'] = (count($data['hobby_user']) > 1) ? implode(',', $data['hobby_user']) : $data['hobby_user'][0];
			}

			$result = $this->crud->save_update($this->table_ref, $data);
			$this->errorCode = 200;
			$this->errorMsg = ($result['success']) ? 
				array('status' => 'success', 'message' => array(
					'title' => 'Sukses',
					'text' => 'Data User telah disimpan',
				)) : 
				array('status' => 'error', 'message' => array(
					'title' => 'Maaf',
					'text' => $result['message'],
				)); 

			$this->showResponse($this->errorMsg, $this->errorCode);
		}
	}

	public function delete(){
		$input = $this->post(false);
		if($input){
			$result = $this->crud->delete($this->table_ref, array($this->id_ref => $input['id']));
			$this->errorCode = 200;
			$this->errorMsg = ($result['success']) ? 
				array('status' => 'success', 'message' => array(
					'title' => 'Sukses',
					'text' => 'Data User telah dihapus',
				)) : 
				array('status' => 'error', 'message' => array(
					'title' => 'Maaf',
					'text' => $result['message'],
				));

			$this->showResponse($this->errorMsg, $this->errorCode);
		}
	}	
}
?>
