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
        'password' => '',
        'dbname' => 'dbweb_sample',
        'charset' => 'utf8',
        'collate' => 'utf8_general_ci',
        'persistent' => false,
        'errorMsg' => 'Maaf, Gagal terhubung dengan database',
    ),
);
/*----------------------*/
?>
