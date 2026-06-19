<?php
include "DBConn.php";

/* Disable FK checks */
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

/* Drop tables safely */
$conn->query("DROP TABLE IF EXISTS tblOrder");
$conn->query("DROP TABLE IF EXISTS tblUser");

/* Enable FK checks */
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

/* Recreate tblUser */
$conn->query("
CREATE TABLE tblUser (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150),
    password VARCHAR(255),
    is_verified BOOLEAN DEFAULT 0
)");

/* Load data from text file */
$lines = file("userData.txt");

foreach($lines as $line){
    list($name,$email,$pass) = explode(",", trim($line));
    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $conn->query("INSERT INTO tblUser (name,email,password)
    VALUES ('$name','$email','$hash')");
}

echo "Users loaded!";
?>