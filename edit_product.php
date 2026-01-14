<?php
//  เรียกใช้ไฟล์ dbConfig.php เพื่อเชื่อมต่อฐานข้อมูล
    require_once 'dbConfig.php';

// ตรวจสอบว่ามีการส่งค่า productId ผ่านทาง URL หรือไม่
    if (isset($_GET['id']) && is_numeric($_GET['id'])){
        $id = $_GET['id']; // รับค่า id จาก URL

        // ดึงข้อมูลสินค้าจากฐานข้อมูลตามรหัสสินค้า
        $sql = "SELECT * FROM products WHERE PrdID = $id";
        // ใช้งานคำสั่ง SQL เพื่อดึงข้อมูล
        $result = $connectDB->query($sql); // connectDB มาจากไฟล์ dbConfig.php

        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if ($result->num_rows > 0){
            // ให้ดึงข้อมูลสินค้า
            $product = $result->fetch_assoc();
            // ปล่อยหน่วยความจำที่ใช้ในการเก็บผลลัพธ์
            $result->free();
        } else {
            echo "ไม่พบสินค้าที่ต้องการแก้ไข";
            exit();
        }
    } else {
        echo "รหัสสินค้าที่ส่งมาไม่ถูกต้อง";
        exit();
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $connectDB->close();

    // สร้างตัวแปรเก็บค่าที่กรอกในฟอร์ม
$productName = $productPicture = $productCategory = $productDescription = "";
$productPrice = $productQuantityStock = 0;

// สร้างตัวแปรเก้บ Error
$productNameErr = $productPictureErr = $productCategoryErr = $productDescriptionErr = "";
$productPriceErr = $productQuantityStockErr = "";

    // รับค่าจากฟอร์มเมื่อมีการกดปุ่มบันทึกการแก้ไข
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['productId'])) {
        // รับค่าจากฟอร์ม
        $productId = intval($_POST['productId']);
        $productName = trim($_POST['productName']);
        $productPicture = trim($_POST['productPicture']);   
        $productCategory = trim($_POST['productCategory']);
        $productDescription = trim($_POST['productDescription']);
        $productPrice = floatval($_POST['productPrice']);
        $productQuantityStock = intval($_POST['productQuantityStock']);
        
        // ตรวจสอบค่าที่กรอกในฟอร์ม 
        // ตรวจสอบ ค่าว่าง ประเภทข้อมูลของค่าที่กรอกในฟอร์ม ชื่อสินค้า
    if (empty ($productName)){
        $productNameErr = "กรุณากรอกชื่อสินค้า";
    } elseif (strlen($productName) > 50) {
        $productNameErr = "ชื่อสินค้าต้องไม่เกิน 50 ตัวอักษร";
    } /** elseif (!preg_match("/^[A-Za-z0-9ก-๙\s]+$/u", $productName)){
        $productNameErr = "ชื่อสินค้าต้องเป็นตัวอักษรภาษาไทย, อังกฤษ และตัวเลขเท่านั้น";
    } **/

    // ตรวจสอบ ค่าว่าง ประเภทข้อมูลของค่าที่กรอกในฟอร์ม รูปภาพสินค้า
    if (empty ($productPicture)){
        $productPictureErr = "กรุณากรอก URL รูปภาพสินค้า";
    } elseif (strlen($productPicture) > 100){
        $productPictureErr = "URL รูปภาพสินค้าต้องไม่เกิน 100 ตัวอักษร";
    } elseif (!filter_var($productPicture, FILTER_VALIDATE_URL)){
        $productPictureErr = "กรุณากรอก URL รูปภาพสินค้าให้ถูกต้อง";
    }

    // ตรวจสอบ ค่าว่าง ประเภทข้อมูลของค่าที่กรอกในฟอร์ม ประเภทสินค้า
    if (empty ($productCategory)){
        $productCategoryErr = "กรุณากรอกประเภทสินค้า";
    } elseif (strlen($productCategory) > 50){
        $productCategoryErr = "ประเภทสินค้าต้องไม่เกิน 50 ตัวอักษร";
    }  /** elseif (!preg_match("/^[A-Za-zก-๙\s]+$/u", $productCategory)){
        $productCategoryErr = "ประเภทสินค้าต้องเป็นตัวอักษรภาษาไทย และอังกฤษเท่านั้น";
    } **/

    // ตรวจสอบ ค่าว่าง ประเภทข้อมูลของค่าที่กรอกในฟอร์ม รายละเอียดสินค้า
    if (empty ($productDescription)) {
        $productDescriptionErr = "กรุณากรอกรายละเอียดสินค้า";
    } elseif (strlen($productDescription) > 250){
        $productDescriptionErr = "รายละเอียดสินค้าต้องไม่เกิน 250 ตัวอักษร";
    }

    // ตรวจสอบ ค่าว่าง ประเภทข้อมูลของค่าที่กรอกในฟอร์ม และจำนวนข้อมูล ราคาสินค้า
    if (empty ($productPrice)){
        $productPriceErr = "กรุณากรอกราคาสินค้า";
    } elseif (!is_numeric($productPrice) || $productPrice < 0){
        $productPriceErr = "กรุณากรอกราคาสินค้าเป็นตัวเลขที่มากกว่าหรือเท่ากับ 0";
    }

    // ตรวจสอบ ค่าว่าง ประเภทข้อมูลของค่าที่กรอกในฟอร์ม และจำนวนข้อมูล จำนวนสินค้า
    if (empty ($productQuantityStock)) {
        $productQuantityStockErr = "กรุณากรอกจำนวนสินค้า";
    } elseif (!is_numeric($productQuantityStock) || $productQuantityStock < 0 ){
        $productQuantityStockErr = "กรุณากรอกจำนวนสินค้าเป็นตัวเลขที่มากกว่าหรือเท่ากับ 0";
    } elseif (!filter_var($productQuantityStock, FILTER_VALIDATE_INT)){
        $productQuantityStockErr = "กรุณากรอกจำนวนสินค้าเป็นจำนวนเต็ม";
    }

    // ถ้าไม่มี Error เกิดขึ้น ให้ทำการอัปเดตข้อมูลลงฐานข้อมูล
    if (empty($productNameErr) && empty($productPictureErr) && empty($productCategoryErr) &&
        empty($productDescriptionErr) && empty($productPriceErr) && empty($productQuantityStockErr)) {  

        //  เรียกใช้ไฟล์ dbConfig.php เพื่อเชื่อมต่อฐานข้อมูล
        require_once 'dbConfig.php';

        // ตรวจสอบค่าที่กรอกในฟอร์ม (สามารถเพิ่มการตรวจสอบเพิ่มเติมได้)
        // อัปเดตข้อมูลสินค้าในฐานข้อมูล
        $updateSql = "UPDATE products SET
            PrdName = ?, 
            PrdPicture = ?, 
            PrdCategory = ?, 
            PrdDescription = ?, 
            PrdPrice = ?, 
            PrdQtyStock = ? 
            WHERE PrdID = ?";
        $stmt = $connectDB->prepare($updateSql);
        $stmt->bind_param("ssssddi", 
            $productName, 
            $productPicture, 
            $productCategory, 
            $productDescription, 
            $productPrice, 
            $productQuantityStock, 
            $productId
        );

            // ดำเนินการคำสั่ง SQL
            if ($stmt->execute()) {
                // ถ้าอัปเดตข้อมูลสำเร็จ ให้รีไดเรกต์กลับไปที่หน้า index.php
                header("Location: index.php");
                exit();
            } else {
                echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . $stmt->error;
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit_Product</title>

    <!-- ไฟล์ CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div>
        <form action="edit_product.php?productId=<?php echo $product['PrdID']; ?>" method="POST">
            <h2>แก้ไขสินค้า</h2>
            <input type="hidden" name="productId" value="<?php echo $product['PrdID']; ?>">

            <label>ชื่อสินค้า:</label><br>
            <input type="text" name="productName" value="<?php echo htmlspecialchars($product['PrdName']); ?>"><br><br>

            <label>รูปภาพสินค้า (URL):</label><br>
            <input type="text" name="productPicture" value="<?php echo htmlspecialchars($product['PrdPicture']); ?>"><br><br>

            <label>ประเภทสินค้า:</label><br>
            <input type="text" name="productCategory" value="<?php echo htmlspecialchars($product['PrdCategory']); ?>"><br><br>

            <label>รายละเอียดสินค้า:</label><br>
            <textarea name="productDescription"><?php echo htmlspecialchars($product['PrdDescription']); ?></textarea><br><br>

            <label>ราคาสินค้า:</label><br>
            <input type="text" name="productPrice" value="<?php echo htmlspecialchars($product['PrdPrice']); ?>"><br><br>

            <label>จำนวนสินค้า:</label><br>
            <input type="text" name="productQuantityStock" value="<?php echo htmlspecialchars($product['PrdQtyStock']); ?>"><br><br>

            <!-- ปุ่ม ลบ บันทึกการแก้ไข ยกเลิก -->
            <div class="flex">
                <form action="delete_product.php" method="POST" 
                    onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?');">
                    <a href="index.php" class="button red">ลบ</a>
                </form>

            <!-- ปุ่มบันทึกการแก้ไข -->
                <input type="submit" value="บันทึกการแก้ไข" class="button green">

                 <!-- ปุ่มยกเลิก -->
                <a href="index.php" class="button gray">ยกเลิก</a>
            </div>
            
        </form>
    </div>
</body>
</html>