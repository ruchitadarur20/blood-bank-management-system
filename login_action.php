<?php
session_start();
include("db_connect.php"); // Ensure this file connects to your database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(strip_tags($_POST["username"]));
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            $_SESSION["role"] = $row["role"];

            if ($row["role"] === "admin") {
                echo "<script>alert('Admin Login Successful!'); window.location.href='admin_dashboard.php';</script>";
            } else {
                echo "<script>alert('Login Successful!'); window.location.href='dashboard.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid Password!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('User not found!'); window.location.href='login.php';</script>";
    }
}
?>