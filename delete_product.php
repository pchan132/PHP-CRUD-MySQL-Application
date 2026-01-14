<?php 
// เรียกใช้ไฟล์ dbConfig.php เพื่อเชื่อมต่อฐานข้อมูล
    require_once 'dbConfig.php';

    // ดึง ID จาก URL
    $productId = intval($_GET['id'] ?? 0);
    if (!$productId){
        die("ไม่พบรหัสสินค้า");
    }

    // ถ้ามี ไอดีสินค้า ให้ลบสินค้าจากฐานข้อมูล
    if ($productId){
        mysqli_query($connectDB, "DELETE FROM Products WHERE PrdID=$productId");
    }

    // กลับไปที่หน้าแสดงสินค้า
    header("Location: index.php");
?>