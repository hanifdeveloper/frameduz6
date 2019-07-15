<?php
$mysql = exec('which mysql', $output); // Linux OS
$mysql = !empty($output) ? $output : exec('locate mysql', $output); // Mac OS
if(!empty($output)){
    $user = input('User Database : ');
    $pass = input('Password Database : ');
    array_walk($output, function($v, $k) use (&$command) {if(preg_match('/\bbin\/mysql\b/i', $v)) $command = $v;});
    output('Create Database ...');
    $query = $command.' -h localhost -u'.$user.' -p'.$pass.' -e "CREATE DATABASE IF NOT EXISTS dbweb_sample CHARACTER SET utf8 COLLATE utf8_general_ci" 2>&1';
    $cmd = exec($query, $response, $status);
    output('Import Database ...');
    $query = $command.' -h localhost -u'.$user.' -p'.$pass.' dbweb_sample < dbweb_sample.sql 2>&1';
    $cmd = exec($query, $response, $status);
    $result = ($status === 0) ? 'Database berhasil diinstall' : 'Database gagal diinstall';
    output($result);
}else{
    output('mysql not installed');
}

function input($str){
    echo "$str";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    return trim($line);
}
function output($str){
    echo "$str\n";
}
?>