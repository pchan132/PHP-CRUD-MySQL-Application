<?php
// เรียกใช้ไฟล์ dbConfig.php เพื่อเชื่อมต่อฐานข้อมูล
require_once 'dbConfig.php';

// สร้างตัวแปรสำหรับเก็บข้อมูลจากฐานข้อมูล
$data = [];

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT * FROM Products";

// ใช้งานคำสั่ง SQL เพื่อดึงข้อมูล
if ($result = $connectDB->query($sql)){
    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($result->num_rows > 0){
        // วนลูปเพื่อดึงข้อมูลแต่ละแถว
        while ($row = $result->fetch_assoc()){
            // เก็บข้อมูลลงในตัวแปร $data
            $data[] = $row;
        }
        // ปล่อยหน่วยความจำที่ใช้ในการเก็บผลลัพธ์
        $result->free(); // ทำไม่ต้อง free หน่วยความจำ? เพราะช่วยลดการใช้หน่วยความจำเมื่อไม่ต้องการข้อมูลแล้ว
    } else {
        echo "ไม่มีข้อมูลในตาราง Products";
    }
} else {
    echo "ERROR: ไม่สามารถรันคำสั่ง SQL ได้ $sql. " . $connectDB->error;
}
// ปิดการเชื่อมต่อฐานข้อมูล
$connectDB->close(); 
// ตัวอย่าง การเรียนข้อมูลที่ดึงมา
// echo '<pre>'; // แสดงผลข้อมูลในรูปแบบที่อ่านง่าย
// print_r($data); // print_r แสดงข้อมูลในรูปแบบ array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP-CRUD-MySQL-Application</title>
</head>
<body>
    <h1> รายการสินค้า</h1>

    <a href="add_product.php"> เพิ่มสินค้า</a> <!-- ลิงก์ไปยังหน้าเพิ่มสินค้า -->
    
    <table border="1">
        <tr>
            <th>รหัสสินค้า</th>
            <th>ชื่อสินค้า</th>
            <th>รูปภาพสินค้า</th>
            <th>ประเภทสินค้า</th>
            <th>รายละเอียดสินค้า</th>
            <th>ราคาสินค้า</th>
            <th>จํานวนสินค้า</th>
        </tr>

        <!-- วนลูปแสดงข้อมูลสินค้า -->
         <!-- แสดงลำดับ -->
          <?php $index = 1;?>
        <?php foreach ($data as $product): ?>
        <tr>
            <!-- แสดงลำดับ -->
            <td><?php echo $index ?></td>
             <!-- แสดงชื่อสินค้า -->
            <td>
                <?php echo htmlspecialchars($product['PrdName']); ?>
            </td>
            <!-- แสดงรูปภาพสินค้า -->
            <td>
                <img src="<?php echo htmlspecialchars($product['PrdPicture']);?>" 
                width="150">
            </td>
            <!-- แสดงประเภทสินค้า -->
            <td>
                <?php echo htmlspecialchars($product['PrdCategory']); ?>
            </td>
            <!-- แสดงรายละเอียดสินค้า -->
             <td>
                <?php echo htmlspecialchars($product['PrdDescription']); ?>
             </td>
            <!-- แสดงราคาสินค้า -->
             <td>
                <?php echo htmlspecialchars($product['PrdPrice']); ?>
             </td>
            <!-- แสดงจํานวนสินค้า -->
            <td>
                <?php echo htmlspecialchars($product['PrdQtyStock']); ?>
            </td>
        </tr>
            <?php $index++;?> <!-- บวกลำดับ -->
            <?php endforeach; ?>
    </table>
</body>
</html>