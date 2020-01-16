<?php
/* Database Configuration */
return array(
    /**
     * 
     * Database Sample Project
     * =======================
     * Install database sample via terminal
     * cd assests/
     * sudo php install.php
     * 
     */
    'crud' => array(
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => 3306,
        'user' => 'root',
        'password' => 'root',
        'dbname' => 'dbweb_sample',
        'charset' => 'utf8',
        'collate' => 'utf8_general_ci',
        'persistent' => false,
        'errorMsg' => 'Maaf, Gagal terhubung dengan database',
    ),
    // 'crud' => array(
    //     'driver' => 'mysql',
    //     'host' => '127.0.0.1',
    //     'port' => 13306,
    //     'user' => 'root',
    //     'password' => '$pegawai-db@simapp',
    //     'dbname' => 'db_pegawai',
    //     'charset' => 'utf8',
    //     'collate' => 'utf8_general_ci',
    //     'persistent' => false,
    //     'errorMsg' => 'Maaf, Gagal terhubung dengan database',
    //     // Tunneling
    //     // ssh -N -L 13306:127.0.0.1:3306 root@192.168.254.124
    // ),
);
/*----------------------*/
?>
