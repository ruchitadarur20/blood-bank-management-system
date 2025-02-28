<?php
session_start();
include("db_connect.php"); // Secure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(strip_tags($_POST["username"]));
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["user"] = $username;
            $_SESSION["role"] = $row["role"];

            if ($row["role"] === "admin") {
                echo "<script>alert('Admin Login Successful!'); window.location.href='admin.php';</script>";
            } else {
                echo "<script>alert('Login Successful!'); window.location.href='dashboard.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid Password!');</script>";
        }
    } else {
        echo "<script>alert('User not found!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ff512f, #dd2476);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="container p-3">
        <div class="card p-4 shadow bg-white rounded mx-auto" style="max-width: 400px;">
            <h3 class="text-center text-danger">Login</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-danger w-100">Login</button>
            </form>
            <p class="text-center mt-3">
                Don't have an account? <a href="signup.php" class="text-danger fw-bold">Sign Up</a>
            </p>
        </div>
    </div>
</body>
</html>
