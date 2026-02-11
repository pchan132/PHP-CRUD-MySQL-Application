<?php
//  เรียกใช้ไฟล์ dbConfig.php เพื่อเชื่อมต่อฐานข้อมูล
include_once 'dbConfig.php';

$index = 1; 

// สร้างตัวแปรค้นหา
$keyword = $_GET['keyword'] ?? '';
// เตรียมคำสั่ง SQL สำหรับค้นหา
$sql = "SELECT * FROM Products";

// ถ้ามีการกรอกคำค้นหา
if ($keyword != "" ) {
    // เพิ่มเงื่อนไขการค้นหาในคำสั่ง SQL ใช้ Prepared Statement
    // Prepared Statement คือการเตรียมคำสั่ง SQL ล่วงหน้า โดยใช้เครื่องหมาย ? แทนค่าที่จะส่งเข้าไป
    // ใช้ LIKE สำหรับการค้นหาที่ไม่ตรงตัว
    $sql .= "  WHERE PrdName LIKE ? 
                OR PrdCategory LIKE ? 
                OR PrdDescription LIKE ?";
    // เตรียมคำสั่ง SQL
    $stmt = mysqli_prepare($connectDB, $sql);

    // กำหนดค่าพารามิเตอร์
    // % % คือการระบุว่าค้นหาคำที่มีข้อความใดๆ อยู่ข้างหน้า หรือ ข้างหลังคำที่ค้นหา ก็ได้
    $searchTerm = "%$keyword%";

    // ผูกค่าพารามิเตอร์กับคำสั่ง SQL
    // mysqli คือการผูกค่าพารามิเตอร์กับคำสั่ง SQL
    // stmt = ตัวแปรคำสั่ง SQL ที่เตรียมไว้
    // bind_param = ฟังก์ชันสำหรับผูกค่าพารามิเตอร์
    // sss = ประเภทข้อมูลของพารามิเตอร์ที่ผูก (s = string)
    // $searchTerm = ตัวแปรที่เก็บค่าที่จะผูก
    mysqli_stmt_bind_param( $stmt, "sss",
    $searchTerm,
        $searchTerm,
        $searchTerm);

    // รันคำสั่ง SQL
    mysqli_stmt_execute($stmt);

    // ดึงผลลัพธ์จากคำสั่ง SQL
    $result = mysqli_stmt_get_result($stmt);
    } else { // ถ้าไม่มีการกรอกคำค้นหา
    // รันคำสั่ง SQL ปกติ คือ SELECT * FROM Products แสดงสินค้าทั้งหมด
        $result = mysqli_query($connectDB, $sql);
    }

    // Debug หรือ แสดงข้อมูล
    // echo $searchTerm // Debug
    // echo '<pre>';
    // print_r($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงสินค้า</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>รายการสินค้า</h2>
        <div class="search-container">
            <!-- ฟอร์มค้นหา -->
            <!-- ใช้ get เพราะต้องการให้คำค้นหาแสดงใน URL เพื่อให้สามารถเอาชื่อไปใส่ในเงื่อนไขการค้นหาได้ -->
            <form method="get" class="search-form">
                <input type="text" name="keyword" value="<?= $keyword ?>" placeholder="ค้นหาชื่อ, หมวดหมู่ หรือรายละเอียด">
                <button type="submit" class="button">ค้นหา</button>
                <!-- ปุ่มรีเซ็ตเพื่อเคลียร์คำค้นหา -->
                <button type="button" onclick="window.location.href='index.php'" class="button gray">Reset</button>
            </form>

            <!-- ปุ่มเพิ่มสินค้า -->
            <a href="add_product.php" class="add-btn">
                + เพิ่มสินค้า
            </a>
        </div>

    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>รูปสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>หมวดหมู่สินค้า</th>
                <th>คำอธิบายสินค้า</th>
                <th>ราคาสินค้า</th>
                <th>สินค้าที่อยู่ในคลัง</th>
            </tr>
        </thead>
        <tbody>

        <!-- แสดงสินค้า -->
        <?php while ( $row = mysqli_fetch_assoc($result) ) { ?>
            <tr>
                <!-- แสดงลำดับ -->
                <td> <?= htmlspecialchars($index) ?></td>
                <!-- แสดงชื่อสินค้า และส่งไปหน้าแก้ไข -->
                <td>
                    <a href="edit_product.php?id=<?= $row['PrdID'] ?>">
                        <?= htmlspecialchars($row['PrdName']) ?>
                    </a>
                </td>
                <!-- แสดงรูปภาพสินค้า -->
                <td>
                    <img src="<?= htmlspecialchars($row['PrdPicture']) ?>" alt="<?= htmlspecialchars($row['PrdPicture']) ?>" width="100">
                </td>

                <!-- แสดง หมวดหมู่สินค้า, คำอธิบาย, ราคา, จำนวนในคลัง -->
                <td> <?= htmlspecialchars($row['PrdCategory'])?> </td>
                <td> <?= htmlspecialchars($row['PrdDescription']) ?></td>
                <td> <?= htmlspecialchars($row['PrdPrice'])?> </td>
                <td> <?=  htmlspecialchars($row['PrdQtyStock']) ?> </td>
            </tr>
            <!-- เพิ่มลำดับ -->
            <?php $index++ ?>
        <?php  } ?>
        </tbody>
    </table>
    </div>
</body>
</html>