<?php

namespace app\crud\controller;

use app\defaults\controller\application;
use app\crud\model\crud;

class simple extends application{
	
	public function __construct(){
		parent::__construct();
		$this->url_path = $this->link($this->getProject().$this->getController());
		$this->crud = new crud();
	}

	protected function index(){
		$data['url_path'] = $this->url_path;
		$data['pilihan_jenis_kelamin'] = array('' => array('text' => 'Semua')) + $this->crud->getJenisKelamin();
		$data['header']['page_title'] = 'Simple CRUD';
		$data['header']['description'] = 'Contoh penggunaan frameduz membuat aplikasi CRUD versi MVC';
		$data['header']['background'] = 'bg-dark';
		$this->showView('index', $data, 'defaults');
	}

	protected function script(){
		header('Content-Type: application/javascript');
		$data['url_path'] = $this->url_path;
		$this->subView('script', $data);
	}

	protected function tabel(){
		$input = $this->post(true);
		if($input){
			$data = $this->crud->getTabelUser($input);
			$this->subView('tabel', $data);
		}
	}

	protected function form(){
		$input = $this->post(true);
		if($input){
			$data = $this->crud->getFormUser($input['id']);
			$this->subView('form', $data);
		}
	}

	protected function simpan(){
		$input = $this->post(true);
		if($input){
			$upload = $this->uploadImage('foto', 'foto');
			if($upload['status'] == 'success'){
				$data = $this->crud->getDataTabel('tb_user', array('id_user', $_POST['id_user']));
				// Check Input Post
				foreach($data as $key => $value){if(isset($input[$key])) $data[$key] = $input[$key];}
				// Check Upload File
				if(!empty($upload['UploadFile'])) $data['foto_user'] = $upload['UploadFile'];
				// Check Input Array (Checkbox)
				if(is_array($data['hobby_user'])){
					$data['hobby_user'] = (count($data['hobby_user']) > 1) ? implode(',', $data['hobby_user']) : $data['hobby_user'][0];
				}
				$result = $this->crud->save_update('tb_user', $data);
				$error_msg = ($result['error']) ? array('status' => 'success', 'message' => 'Data User telah disimpan') : array('status' => 'error', 'message' => $result['message']);
			}else{
				$error_msg = array('status' => 'error', 'message' => $upload['errorMsg']);
			}

			$this->showResponse($error_msg);
		}
	}

	protected function hapus(){
		$input = $this->post(true);
		if($input){
			$result = $this->crud->delete('tb_user', array('id_user' => $input['id']));
			$error_msg = ($result['error']) ? array('status' => 'success', 'message' => 'Data User telah dihapus') : array('status' => 'error', 'message' => $result['message']);
			$this->showResponse($error_msg);
		}
	}	
}
?>
