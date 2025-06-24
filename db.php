<?php
$host = 'localhost';
$user = 'root'; // default XAMPP user
$pass = '';     // default XAMPP has no password
$dbname = 'travelnow'; // replace with your actual database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>
