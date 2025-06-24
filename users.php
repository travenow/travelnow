<?php
$servername = "localhost";
$username = "root";
$password = "V@ibhav001";
$dbname = "travelnow";

// checking connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql code to create table
$sql = "CREATE TABLE users (
    userid INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    phoneNo VARCHAR(15) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL);";
$password = "user_password";
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
if ($conn->query($sql) === TRUE) {
    echo "Table student created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>