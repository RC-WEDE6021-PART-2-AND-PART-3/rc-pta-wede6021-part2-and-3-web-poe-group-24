<?php
include "DBConn.php";

/* 1. Disable foreign key checks */
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

/* 2. Dropping the  tables (child first) */
$conn->query("DROP TABLE IF EXISTS tblOrder");
$conn->query("DROP TABLE IF EXISTS tblUser");
$conn->query("DROP TABLE IF EXISTS tblAdmin");
$conn->query("DROP TABLE IF EXISTS tblClothes");

/* 3. Enable foreign key checks */
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

/* 4. Creating tblUser */
$conn->query("
CREATE TABLE IF NOT EXISTS tblUser (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150),
    password VARCHAR(255),
    is_verified BOOLEAN DEFAULT 0
)");

/* 5. Creating tblAdmin */
$conn->query("
CREATE TABLE IF NOT EXISTS tblAdmin (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    password VARCHAR(255)
)");

/* 6. Creating tblClothes */
$conn->query("
CREATE TABLE IF NOT EXISTS tblClothes (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255),
    price DECIMAL(10,2)
)");

/* 7. Creating tblOrder with relationships */
$conn->query("
CREATE TABLE IF NOT EXISTS tblOrder (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    FOREIGN KEY (user_id) REFERENCES tblUser(user_id),
    FOREIGN KEY (product_id) REFERENCES tblClothes(product_id)
)");

echo "<h2>ClothingStore database loaded successfully!</h2>";
?>