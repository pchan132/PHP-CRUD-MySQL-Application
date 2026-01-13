<?php
// กำหนดค่าการเชื่อมต่อฐานข้อมูล
    define('DB_HOST', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'ShopDB');

    // สร้างการเชื่อมต่อฐานข้อมูล
    $connectDB = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // ตรวจสอบการเชื่อมต่อ
    if ($connectDB == false){
        die("ERROR: ไม่สามารถเชื่อมต่อฐานข้อมูลได้" . $connectDB->connect_error);
    }
?>