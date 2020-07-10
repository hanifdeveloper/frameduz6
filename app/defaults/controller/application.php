<?php
namespace app\defaults\controller;

use system\Controller;
use system\Config;
use comp\FUNC;

class application extends Controller{

    protected $errorCode = 404;
    protected $errorMsg = array('status' => 'error', 'message' => array(
		'title' => 'Oops',
		'text' => 'Missing Parameter or method not found',
	));
	
	public function __construct(){
        parent::__construct();
	}

	protected function index(){
        $this->showResponse($this->errorMsg, $this->errorCode);
	}
    
    public function createCookie($session){
        $cookie = $this->cookie;
        setcookie($cookie, $session, time() + COOKIE_EXP, '/');
    }
    
    public function removeCookie(){
        $cookie = $this->cookie;
        unset($_COOKIE[$cookie]);
        setcookie($cookie, '', time() - COOKIE_EXP, '/');
    }

    protected function uploadImage($file, $prefix, $action = ''){
        ini_set('memory_limit', '-1');
        $result['status'] = 'success';
        $result['errorMsg'] = 'file tidak dilampirkan';
        $result['UploadFile'] = '';
        if(isset($_FILES[$file]) && !empty($_FILES[$file]['name'])){
            $file = $_FILES[$file];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $opt_upload = array(
                'fileName' => date('dmYHis').'.'.$ext,
                'fileType' => $this->file_type_image,
                'maxSize' => $this->max_size,
                'folder' => $this->dir_upload_image,
                'session' => false,
            );
            $result = $this->files->upload($file, $opt_upload);
            if($result['status'] == 'success'){
                $src = $this->dir_upload_image.'/'.$result['UploadFile'];
                $dst = $this->dir_upload_image.'/'.$prefix.'_'.$result['UploadFile'];
                $result['UploadFile'] = $prefix.'_'.$result['UploadFile'];
                if($action == '' | $action == 'resize'){
                    FUNC::resizeImage(800, $src, $ext, $dst);
                }else if($action == 'crop'){
                    FUNC::cropImage(400, $src, $ext, $dst);
                }
            }
        }
        return $result;
    }

    protected function uploadLampiran($file, $folder, $prefix){
        ini_set('memory_limit', '-1');
        $result['status'] = 'success';
        $result['errorMsg'] = 'file tidak dilampirkan';
        $result['UploadFile'] = '';
        if(isset($_FILES[$file]) && !empty($_FILES[$file]['name'])){
            $file = $_FILES[$file];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $opt_upload = array(
                'fileName' => $prefix.'_'.date('dmYHis').'.'.$ext,
                'fileType' => $this->file_type_lampiran,
                'maxSize' => $this->max_size,
                'folder' => $folder,
                'session' => false,
            );
            $result = $this->files->upload($file, $opt_upload);
        }
        return $result;
    }

    protected function FileExists($file, $action = ''){
        $exist = false;
        if(file_exists($file)) $exist = true;
        if($exist == true && $action == 'delete') unlink($file);
        return $exist;
    }

    protected function showResponse($errorMsg, $code = ''){
    	$_content_type = 'application/json';
    	$_code = 200;
        if(empty($code)) $code = $_code;
        header('HTTP/1.1 '.$code);
        header('Content-Type:'.$_content_type);
        echo json_encode($errorMsg);
    }

    protected function debugResponse($data){
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    } 

    protected function sendMessagePost($data){
        /**
         * Params:
         * $data['url']
         * $data['header']
         * $data['fields']
         */
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $data['url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $data['header']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data['fields']);
        $result = curl_exec($ch);           
        if($result === FALSE){
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
	
}
?>
