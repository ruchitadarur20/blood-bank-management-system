<?php
$host = "localhost";
$user = "root";  // Default XAMPP user
$pass = "";      // Default password is empty in XAMPP
$db = "blood_bank";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
