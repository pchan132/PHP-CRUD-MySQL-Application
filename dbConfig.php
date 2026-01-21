<?php 
    define('host', 'localhost');
    define('userName', 'root');
    define('password', '' );
    define('dbName', 'ShopDB');

    $con = new mysqli(host, userName, password, dbName);

    if ($con == false){
        echo('ไม่สามารถเชื่อมต่อฐานข้อมูลได้');
    } 
?>