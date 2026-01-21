<?php 
    include_once "dbConfig.php";

    $keyword = $_GET['keyword'] ?? "";
    $sql = "SELECT * FROM Products";

    if ($keyword != ""){
        $sql .= "WHERE PrdName LIKE ? ";

        $stmt = mysqli_prepare($con,$sql);

        $searchTerm = "%$keyword%";

        mysqli_stmt_bind_param($stmt, "s", $searchTerm);

        mysqli_stmt_execute($stmt );

        $result = mysqli_stmt_get_result($stmt);
    } else {
        $result = mysqli_query ($con, $sql);
    }

    // echo '<pre>';
    // print_r($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <li> <?= htmlspecialchars($row['PrdName']) ?> </li>
    <?php } ?>
</body>
</html>