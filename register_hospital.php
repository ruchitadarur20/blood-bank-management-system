<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hospital_name = $_POST["hospital_name"];
    $location = $_POST["location"];
    $contact = $_POST["contact"];

    $stmt = $conn->prepare("INSERT INTO hospitals (hospital_name, location, contact) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $hospital_name, $location, $contact);

    if ($stmt->execute()) {
        echo "<script>alert('Hospital Registered Successfully!'); window.location.href='manage_hospitals.php';</script>";
    } else {
        echo "<script>alert('Error: Could not register hospital.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center">Register Hospital</h2>
        <form method="POST" class="p-4 shadow bg-white rounded mx-auto" style="max-width: 400px;">
            <label>Hospital Name:</label>
            <input type="text" name="hospital_name" class="form-control mb-3" required>

            <label>Location:</label>
            <input type="text" name="location" class="form-control mb-3" required>

            <label>Contact:</label>
            <input type="text" name="contact" class="form-control mb-3" required>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
    </div>
</body>
</html>
