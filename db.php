<?php
$host = "localhost";
$dbname = "db8yirtqrvgunr";
$username = "ur9iyguafpilu";
$password = "51gssrtsv3ei";
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
 
