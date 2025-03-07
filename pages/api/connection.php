<?php
global $pdo;

// $dsn = "mysql:host=sql210.infinityfree.com;port=3306;dbname=if0_38012954_eclairfinancedb";
// $dbusername = "if0_38012954";
// $dbpassword = "1DnbEVD7RY7";

$dsn = "mysql:host=localhost;dbname=cryptodb";
$dbusername = "root";
$dbpassword = "";

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // echo "Connection failed: " . $e->getMessage();
    echo '<script>console.log("Connection Failed: ' . $e->getMessage() . '")</script>';
}
?>