<?php
try {
    $username = "root";
    $password = "";
    $database = new PDO("mysql:host=localhost;dbname=emp;charset=utf8;", $username, $password);

    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>