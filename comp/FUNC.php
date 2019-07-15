<?php
namespace comp;
use DomDocument, DomXPath, DateInterval, DateTime, DatePeriod;

class FUNC{
    
    protected static $namabulan = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
	protected static $namahari = array('Sun' => 'Minggu', 'Mon' => 'Senin', 'Tue' => 'Selasa', 'Wed' => 'Rabu', 'Thu' => 'Kamis', 'Fri' => 'Jumat', 'Sat' => 'Sabtu');

    public static function rupiah($number){
        return number_format($number, 0, ',', '.');
    }

    public static function tanggal($tgl, $opt){
		$D = date('D', strtotime($tgl));
        $d = date('d', strtotime($tgl));
        $m = date('m', strtotime($tgl));
		$M = date('M', strtotime($tgl));
        $y = date('Y', strtotime($tgl));
		$w = date('H:i:s', strtotime($tgl));
		$t = date('H:i a', strtotime($tgl));
		switch($opt){
			case 'time' : return $t; break;
			case 'day' : return self::$namahari[$D]; break;
			case 'short_date' : return date('d/m/Y', strtotime($tgl)); break;
			case 'long_date' : return intval($d).' '.self::$namabulan[$m-1].' '.$y; break;
			case 'short_date_time' : return date('d/m/Y H:i:s', strtotime($tgl)); break;
			case 'long_date_time' : return intval($d).' '.self::$namabulan[$m-1].' '.$y.' ['.$w.']'; break;
			case 'date_month' : return intval($d).' '.$M; break;
		}
        
    }
	
	public static function moments($session_time){ 
		$session_time = strtotime($session_time);
		$time_difference = time() - $session_time ; 
		$seconds = $time_difference ; 
		$minutes = round($time_difference / 60 );
		$hours = round($time_difference / 3600 ); 
		$days = round($time_difference / 86400 ); 
		$weeks = round($time_difference / 604800 ); 
		$months = round($time_difference / 2419200 ); 
		$years = round($time_difference / 29030400 ); 
	
		if($seconds <= 60){
			echo 'Baru saja'; 
		}
		else if($minutes <= 60){
			if($minutes == 1)
				echo 'Satu menit yang lalu'; 
			else
				echo $minutes.' menit yang lalu'; 
		}
		else if($hours <= 24){
			if($hours == 1)
				echo 'Satu jam yang lalu';
			else
				echo $hours.' jam yang lalu';
		}
		else if($days <= 7){
			if($days == 1)
				echo 'Satu hari yang lalu';
			else
				echo $days.' hari yang lalu';
		}
		else if($weeks <= 4){
			if($weeks == 1)
				echo 'Satu minggu yang lalu';
			else
				echo $weeks.' minggu yang lalu';
		}
		else if($months <= 12){
			if($months == 1)
				echo 'Satu bulan yang lalu';
			else
				echo $months.' bulan yang lalu';
		}
		else{
			if($years == 1)
				echo 'Satu tahun yang lalu';
			else
			echo $years.' tahun yang lalu';
		}
	}
	
	public static function resizeImage($dw, $source, $stype, $dest, $delfile = true){
		$size = getimagesize($source); // ukuran gambar
		$sw = $size[0];
		$sh = $size[1];
		$quality = 80;
		switch(strtolower($stype)) { // format gambar
			case 'gif':
				$simg = imagecreatefromgif($source);
				$create = "imagegif";
			break;
			case 'jpg':
				$simg = imagecreatefromjpeg($source);
				$create = "imagejpeg";
			break;
			case 'jpeg':
				$simg = imagecreatefromjpeg($source);
				$create = "imagejpeg";
			break;
			case 'png':
				$simg = imagecreatefrompng($source);
				$create = "imagepng";
			break;
		}
		
		$dh = ($dw / $sw) * $sh;
		$dimg = imagecreatetruecolor($dw, $dh);
		imagecopyresampled($dimg, $simg, 0, 0, 0, 0, $dw, $dh, $sw, $sh);
		// $create($dimg, $dest, $quality);
		$create($dimg, $dest);
	  
		imagedestroy($simg);
		imagedestroy($dimg);
		if($delfile) unlink($source);
	}

	public static function cropImage($dw, $source, $stype, $dest, $delfile = true){
		$size = getimagesize($source); // ukuran gambar
		$sw = $size[0];
		$sh = $size[1];
		$quality = 80;
		switch(strtolower($stype)) { // format gambar
			case 'gif':
				$simg = imagecreatefromgif($source);
				$create = "imagegif";
			break;
			case 'jpg':
				$simg = imagecreatefromjpeg($source);
				$create = "imagejpeg";
			break;
			case 'jpeg':
				$simg = imagecreatefromjpeg($source);
				$create = "imagejpeg";
			break;
			case 'png':
				$simg = imagecreatefrompng($source);
				$create = "imagepng";
			break;
		}
		$dh = $dw;
		$dimg = imagecreatetruecolor($dw, $dh);
	    $dw_new = $sh * $dw / $dh;
	    $dh_new = $sw * $dh / $dw;
	    if($dw_new > $sw){
	        $h_point = (($sh - $dh_new) / 2);
	        imagecopyresampled($dimg, $simg, 0, 0, 0, $h_point, $dw, $dh, $sw, $dh_new);
	    }else{
	        $w_point = (($sw - $dw_new) / 2);
	        imagecopyresampled($dimg, $simg, 0, 0, $w_point, 0, $dw, $dh, $dw_new, $sh);
	    }
	     
		// $create($dimg, $dest, $quality);
		$create($dimg, $dest);
		imagedestroy($simg);
		imagedestroy($dimg);
		if($delfile) unlink($source);
	}
	
	public static function encodeImage($img_file, $mimeType){
		$img_bin = base64_encode(fread(fopen($img_file, 'r'), filesize($img_file)));
		return 'data:'.$mimeType.';base64,'.$img_bin;
	}

	public static function scaleImage($img, $scale){
		list($width, $height) = getimagesize($img);
		$width = round($width * $scale / 100);
		$height = round($height * $scale / 100);
		return array(
			'img' => $img,
			'width' => $width,
			'height' => $height
		);
	}
	
	public static function slug($s){
		$c = array (' ');
		$d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');
		$s = str_replace($d, '-', $s);
		$s = strtolower(str_replace($c, '-', $s));
		return $s;
	}
	
	public static function getContent($s){
		preg_match('/<p.*>(.*)<\/p>/', $s, $match);
		return $match[1];
	}

	public static function encryptor($string){
		$output = false;
		$encrypt_method = 'AES-256-CBC';
		$secret_key1 = 'xxx-123';
		$secret_key2 = 'xxx-456';
		$key1 = hash('sha256', $secret_key1);
		$key2 = substr(hash('sha256', $secret_key2), 0, 16);
		$output = base64_encode(openssl_encrypt(($string), $encrypt_method, $key1, 0, $key2));
		return $output;
	}
	
	public static function decryptor($string){
		$output = false;
		$encrypt_method = 'AES-256-CBC';
		$secret_key1 = 'xxx-123';
		$secret_key2 = 'xxx-456';
		$key1 = hash('sha256', $secret_key1);
		$key2 = substr(hash('sha256', $secret_key2), 0, 16);
		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key1, 0, $key2);
		return $output;
	}

	public static function getInfoDesktopAccess($header){
		$info_ip = @file_get_contents('http://ip-api.com/json');
		$info_ip = json_decode($info_ip, true);
		$browser = get_browser();
		$result = array(
			'deviceType' => $browser->device_type,
			'deviceSystem' => $browser->platform,
			'browserVersion' => $browser->comment,
			'localIP' => isset($header['Localip']) ? $header['Localip'] : '',
		);
		$result = (empty($info_ip)) ? $result : array_merge($result, $info_ip);
		return $result;
		// return array();
	}

	public static function getInfoMobileAccess($header){
		$info_ip = @file_get_contents('http://ip-api.com/json');
		$info_ip = json_decode($info_ip, true);
		$result = array(
			'deviceType' => 'Android',
			'deviceBrand' => $header['BrandDevice'],
			'deviceModel' => $header['ModelDevice'],
			'androidVersion' => $header['VersionDevice'],
		);
		$result = (empty($info_ip)) ? $result : array_merge($result, $info_ip);
		return $result;
		// return array();
	}

	public static function getDom(){
		$data = file_get_contents('http://ip-api.com');
        $dom = new DomDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($data);
        $xPath = new DomXPath($dom);
        $elements = $xPath->query("//parent::tr");
        print_r($data);
        if (!is_null($elements)) {
            foreach ($elements as $element) {
              echo "<br/>[". $element->nodeName. "]";
              $nodes = $element->childNodes;
              foreach ($nodes as $node) {
                echo $node->nodeValue. "\n";
              }
            }
        }
	}

	public static function getDateFromRange($start, $end, $format = 'Y-m-d'){
		$result = array();
		$interval = new DateInterval('P1D');
		$dateEnd = new DateTime($end);
		$dateEnd->add($interval);
		$period = new DatePeriod(new DateTime($start), $interval, $dateEnd);
		foreach ($period as $date) {
			$result[] = $date->format($format);
		}
		return $result;
	}

	public static function isDate($str){
		$format = 'Y-m-d';
		$d = DateTime::createFromFormat($format, $str);
		return $d && $d->format($format) === $str;
	}

	public static function getLastDate($m, $y){
		$current_month = $m + 1;
		return date('Y-m-d', mktime(0, 0, 0, $current_month, 0, $y));
	}

	public static function getFileSize($file_url){
		$result  = filesize($file_url);
		$result = intval($result) / 1024; // Kb
		if($result > 1024){
			$result = intval($result) / 1024; // Mb
			return round($result, 2).' Mb';
		}else{
			return round($result, 2).' Kb';
		}
	}

	public static function sortArrayByColumn($arr, $col, $dir = SORT_ASC){
		$sort_col = array();
		$result = array();
		foreach ($arr as $key => $row) {
			$sort_col[$key] = $row[$col];
		}
		array_multisort($sort_col, $dir, $arr);
		return $arr;
	}
	
}

?>
