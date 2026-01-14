<?php
// เรียกใช้ไฟล์ dbConfig.php เพื่อเชื่อมต่อฐานข้อมูล
require_once 'dbConfig.php';

    // ดึง ID จาก URL
    $productId = intval($_GET['id'] ?? 0);
    if (!$productId){
        die("ไม่พบรหัสสินค้า");
    }

    // ------------------ ดึงข้อมูลเก่ามาแสดง ----------------------------
    // เตรียมคำสั่ง SQL และพารามิเตอร์ PrdID
    $stmt = $connectDB->prepare("SELECT * FROM Products WHERE PrdID=?");
    // ผูกค่ากับพารามิเตอร์ในคำสั่ง SQL
    // i = integer
    $stmt->bind_param("i", $productId);
    // รันคำสั่ง SQL
    $stmt->execute();
    // ดึงข้อมูลสินค้า มาเก็บในตัวแปร $product
    $product = $stmt->get_result()->fetch_assoc();
    // ปิดคำสั่ง SQL
    $stmt->close();

    // ตรวจสอบว่าพบสินค้าหรือไม่
    if (!$product){
        die("ไม่พบสินค้า");
    }

    // ========================== เมื่อแก้ไข้ข้อมูล ====================================
    // สร้างตัวแปรเก็บข้อมูล
    $productName = $product['PrdName'];
    $productPicture = $product['PrdPicture'];
    $productCategory = $product['PrdCategory'];
    $productDescription = $product['PrdDescription'];
    $productPrice = $product['PrdPrice'];
    $productQtyStock = $product['PrdQtyStock'];

    // สร้างตัวแปรเก็บ Error
    $productNameErr = $productPictureErr = $productCategoryErr = $productDescriptionErr = "";
    $productPriceErr = $productQuantityStockErr = "";

    // ---------------------------เมื่อกด submit ----------------------------------
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
    } elseif (strlen($productQuantityStock) > 4){
        $productQuantityStockErr = "ต้องไม่เกิน 4 ตัว";
    }

    // ตรวจสอบ ค่าว่าง ประเภทข้อมูลของค่าที่กรอกในฟอร์ม และจำนวนข้อมูล จำนวนสินค้า
    if (empty ($productQuantityStock)) {
        $productQuantityStockErr = "กรุณากรอกจำนวนสินค้า";
    } elseif (!is_numeric($productQuantityStock) || $productQuantityStock < 0 ){
        $productQuantityStockErr = "กรุณากรอกจำนวนสินค้าเป็นตัวเลขที่มากกว่าหรือเท่ากับ 0";
    } elseif (!filter_var($productQuantityStock, FILTER_VALIDATE_INT)){
        $productQuantityStockErr = "กรุณากรอกจำนวนสินค้าเป็นจำนวนเต็ม";
    } elseif (strlen($productQuantityStock) > 3){
        $productQuantityStockErr = "ต้องไม่เกิน 3 ตัว";
    }

    // ! ถ้าไม่มี Error 
     if (empty($productNameErr) && empty($productPictureErr) && empty($productCategoryErr) &&
        empty($productDescriptionErr) && empty($productPriceErr) && empty($productQuantityStockErr)) {
            
        // อัพเดทข้อมูลลงฐานข้อมูล
            $stmt = $connectDB->prepare(
                "UPDATE Products
                    SET PrdName=?, PrdPicture=?, PrdCategory=?, PrdDescription=?, PrdPrice=?, PrdQtyStock=? WHERE PrdID=?"
            );

            // ผูกค่ากับพารามิเตอร์ในคำสั่ง SQL
            $stmt->bind_param("ssssiii", $productName, $productPicture, $productCategory, $productDescription, $productPrice, $productQuantityStock, $productId);

            // รันคำสั่ง SQL
            $stmt->execute();
            // ปิดคำสั่ง SQL
            $stmt->close();
            // กลับไปที่หน้าแสดงสินค้า
            header("Location:index.php");
            exit();
        }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2> แก้ไขสินค้า ID <?= $productId ?> </h2>

    <form method="POST" action="edit_product.php?id=<?= $productId ?> ">
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
            <input type="text" name="productQuantityStock" value="<?php echo htmlspecialchars($productQtyStock); ?>">
            <span style="color: red;"><?php echo $productQuantityStockErr; ?></span><br><br>

            <!-- ปุ่มลบ แก้ไข ยกเลิก -->
            <div class="flex">
                <a href="delete_product.php?id=<?= $productId ?>" class="button red"
                    onclick = "return confirm('ลบสินค้านี้จริงหรือไม่?')"
                >
                    ลบสินค้า
                </a>
                
                <button type="submit" class="button green">บันทึก</button>
                <a href="index.php" class="button gray">ยกเลิก</a>
            </div>
    </form>
</body>
</html>