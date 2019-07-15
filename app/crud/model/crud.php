<?php
namespace app\crud\model;

use system\Model;

class crud extends Model{
	
	public function __construct(){
		parent::__construct();
        parent::setConnection('crud');
        parent::setDefaultValue(array(
			'id_user' => date('Ymdhis'),
			'jenis_kelamin' => 'laki-laki',
		));
    }

    public function getJenisKelamin(){
        return array(
            'laki-laki' => array('text' => 'Laki-laki'), 
            'perempuan' => array('text' => 'Perempuan'), 
        );
	}
	
	public function getJenisHobby(){
		return array('Makan', 'Tidur', 'Maen Game');
	}
	
	public function getFormUser($id = ''){
        set_time_limit(0);
		$result['form'] = $this->getDataTabel('tb_user', array('id_user', $id));
		$foto_user = (!empty($result['form']['foto_user']) && file_exists('upload/image/'.$result['form']['foto_user'])) ? $this->getUrl->baseUrl . 'upload/image/'.$result['form']['foto_user'] : $this->no_image;
		$result['form']['foto_user'] = $foto_user;
		$result['form_title'] = empty($id) ? 'Tambah Data User' : 'Edit Data User';
		$result['pilihan_jenis_kelamin'] = $this->getJenisKelamin();
		$result['pilihan_hobby'] = $this->getJenisHobby();
		$result['mimes_image'] = $this->files->getMimeTypes($this->file_type_image);
		$result['keterangan_upload_image'] = '*) File Type : '.$this->file_type_image.', Max Size : '.($this->max_size / 1024 /1024).'Mb';
        return $result;
    }
	
	public function getTabelUser($data){
        set_time_limit(0);
		$page = $data['page'];
		$cari = '%'.$data['cari'].'%';
		$jenis = '%'.$data['jenis'].'%';
		$q_value = 'SELECT * FROM tb_user WHERE (nama_user LIKE ?) AND (jenis_kelamin LIKE ?) ORDER BY id_user ASC';
		$q_count = 'SELECT COUNT(*) AS jumlah FROM tb_user WHERE (nama_user LIKE ?) AND (jenis_kelamin LIKE ?)';
		$idKey = array($cari, $jenis);
		$batas = 10;
        $posisi = ($page - 1) * $batas;
		$dataArr = $this->getData($q_value.' LIMIT '.$posisi.','.$batas, $idKey);
		$dataCount = $this->getData($q_count, $idKey);
		$pilihan_jenis_kelamin = $this->getJenisKelamin();
		foreach ($dataArr['value'] as $key => $value) {
			$foto_user = (!empty($value['foto_user']) && file_exists('upload/image/'.$value['foto_user'])) ? $this->getUrl->baseUrl . 'upload/image/'.$value['foto_user'] : $this->no_image;
			$dataArr['value'][$key]['foto_user'] = $foto_user;
			$dataArr['value'][$key]['jenis_kelamin'] = $pilihan_jenis_kelamin[$value['jenis_kelamin']]['text'];
		}
        $result['no'] = $posisi + 1;
        $result['page'] = $page;
        $result['batas'] = $batas;
        $result['jumlah'] = $dataCount['value'][0]['jumlah'];
        $result['tabel'] = $dataArr['value'];
		$result['query'] = $dataArr['query'];
		$result['query'] = '';
        return $result;
    }

}
?>