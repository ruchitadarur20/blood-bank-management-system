<?php
include("db.php");

if (isset($_GET["token"])) {
    $token = $_GET["token"];
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token=?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("Invalid token!");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = password_hash($_POST["password"], PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE users SET password=?, reset_token=NULL WHERE reset_token=?");
        $stmt->bind_param("ss", $new_password, $token);
        $stmt->execute();

        echo "<script>alert('Password Reset Successful!'); window.location.href='login.php';</script>";
    }
} else {
    die("Token missing!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center">Reset Password</h2>
        <form method="POST" class="p-3 bg-white shadow rounded mx-auto" style="max-width: 400px;">
            <label>New Password:</label>
            <input type="password" name="password" class="form-control mb-3" required>
            <button type="submit" class="btn btn-success w-100">Reset Password</button>
        </form>
    </div>
</body>
</html>
