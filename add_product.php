<?php
//  เรียกใช้ไฟล์ dbConfig.php เพื่อเชื่อมต่อฐานข้อมูล
require_once 'dbConfig.php';

// สร้างตัวแปรเก็บค่าที่กรอกในฟอร์ม
$productName = $productPicture = $productCategory = $productDescription = "";
$productPrice = $productQuantityStock = 0;

// สร้างตัวแปรเก้บ Error
$productNameErr = $productPictureErr = $productCategoryErr = $productDescriptionErr = "";
$productPriceErr = $productQuantityStockErr = "";

// รับข้อมูลจากฟอร์มเมื่อมีการกดปุ่ม submit
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // รับขค่า input จากฟอร์ม
    $productName = trim($_POST['productName']);
    $productPicture = trim($_POST['productPicture']);
    $productCategory = trim($_POST['productCategory']);
    $productDescription = trim($_POST['productDescription']);

    $productPrice = floatval($_POST['productPrice']);
    $productQuantityStock = intval($_POST['productQuantityStock']);

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
    } elseif (strlen($productPrice) > 4){
        $productPriceErr = "ราคาสินค้าต้องไม่เกิน 4 ตัวเลข";
    }

    // ตรวจสอบ ค่าว่าง ประเภทข้อมูลของค่าที่กรอกในฟอร์ม และจำนวนข้อมูล จำนวนสินค้า
    if (empty ($productQuantityStock)) {
        $productQuantityStockErr = "กรุณากรอกจำนวนสินค้า";
    } elseif (!is_numeric($productQuantityStock) || $productQuantityStock < 0 ){
        $productQuantityStockErr = "กรุณากรอกจำนวนสินค้าเป็นตัวเลขที่มากกว่าหรือเท่ากับ 0";
    } elseif (!filter_var($productQuantityStock, FILTER_VALIDATE_INT)){
        $productQuantityStockErr = "กรุณากรอกจำนวนสินค้าเป็นจำนวนเต็ม";
    } elseif (strlen($productQuantityStock) > 3){
        $productQuantityStockErr = "จำนวนสินค้าต้องไม่เกิน 3 ตัวเลข";
    }
    
    // ถ้าไม่มี Error เกิดขึ้น ให้ทำการบันทึกข้อมูลลงฐานข้อมูล
    if (empty($productNameErr) && empty($productPictureErr) && empty($productCategoryErr) &&
        empty($productDescriptionErr) && empty($productPriceErr) && empty($productQuantityStockErr)) {
        
        // เตรียมคำสั่ง SQL แบบ Prepared Statement
        $stmt = $connectDB->prepare("INSERT INTO Products (PrdName, PrdPicture, PrdCategory, PrdDescription, PrdPrice, PrdQtyStock) 
                                VALUES (?, ?, ?, ?, ?, ?)");
        // ผูกค่าตัวแปรกับคำสั่ง SQL 
        // s = string, d = double, i = integer
        // bind_param(ประเภทข้อมูลของตัวแปรที่ผูก)
        $stmt->bind_param("ssssdi", $productName, $productPicture, $productCategory, 
                          $productDescription, $productPrice, $productQuantityStock);
        
        // ดำเนินการคำสั่ง SQL
        if ($stmt->execute()) {
            // ถ้าบันทึกข้อมูลสำเร็จ ให้รีไดเรกต์กลับไปที่หน้า index.php
            header("Location: index.php");
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
        }
        
        // ปิดคำสั่ง SQL
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add_product</title>

    <!-- ไฟล์ CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <form action="add_product.php" method="POST">
            <h2>เพิ่มสินค้าใหม่</h2>
            <label>ชื่อสินค้า:</label><br>
            <input type="text" name="productName" value="<?php echo htmlspecialchars($productName); ?>">
            <span style="color: red;"><?php echo $productNameErr; ?></span><br><br>

            <label>รูปภาพสินค้า (URL):</label><br>
            <input type="text" name="productPicture" value="<?php echo htmlspecialchars($productPicture); ?>">
            <span style="color: red;"><?php echo $productPictureErr; ?></span><br><br>

            <label>ประเภทสินค้า:</label><br>
            <input type="text" name="productCategory" value="<?php echo htmlspecialchars($productCategory); ?>">
            <span style="color: red;"><?php echo $productCategoryErr; ?></span><br><br>

            <label>รายละเอียดสินค้า:</label><br>
            <textarea name="productDescription"><?php echo htmlspecialchars($productDescription); ?></textarea>
            <span style="color: red;"><?php echo $productDescriptionErr; ?></span><br><br>

            <label>ราคาสินค้า:</label><br>
            <input type="text" name="productPrice" value="<?php echo htmlspecialchars($productPrice); ?>">
            <span style="color: red;"><?php echo $productPriceErr; ?></span><br><br>

            <label>จำนวนสินค้า:</label><br>
            <input type="text" name="productQuantityStock" value="<?php echo htmlspecialchars($productQuantityStock); ?>">
            <span style="color: red;"><?php echo $productQuantityStockErr; ?></span><br><br>

            <!--ปุ่มเพิ่มสินค้า ยกเลิก -->
            <div class="flex">
                <a href="index.php" class="button red">ยกเลิก</a>
                <input type="submit" value="เพิ่มสินค้า" class="button green">
            </div>
        </form>
    </div>
</body>
</html>