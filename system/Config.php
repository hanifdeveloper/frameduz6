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

class Config{
	
	private static $config = array();
	
	public static function Load($name){
		if(!isset(self::$config[$name])){
			$dataArr = require CONFIG . $name . '.php';
			self::$config[$name] = $dataArr;
			return $dataArr;
		}
		else{
			return self::$config[$name];
		}
	}
	
}
?>