<?php
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $blood_type = $_POST["blood_type"];
    $contact = $_POST["contact"];
    $email = $_POST["email"];

    // Use Prepared Statement (Security Fix)
    $stmt = $conn->prepare("INSERT INTO donors (name, blood_type, contact, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $blood_type, $contact, $email);

    if ($stmt->execute()) {
        echo "<script>alert('Registration Successful!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error: Could not register.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="container p-3">
        <div class="card p-4 shadow bg-white rounded mx-auto" style="max-width: 400px;">
            <h3 class="text-center">Donor Registration</h3>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Blood Type</label>
                    <select name="blood_type" class="form-control" required>
                        <option value="A+">A+</option>
                        <option value="O-">O-</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact</label>
                    <input type="text" name="contact" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
        </div>
    </div>
</body>
</html>
