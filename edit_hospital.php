<?php
include("db_connect.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM hospitals WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $hospital = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hospital_name = $_POST["hospital_name"];
    $location = $_POST["location"];
    $contact = $_POST["contact"];

    $stmt = $conn->prepare("UPDATE hospitals SET hospital_name=?, location=?, contact=? WHERE id=?");
    $stmt->bind_param("sssi", $hospital_name, $location, $contact, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Hospital Updated!'); window.location.href='manage_hospitals.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hospital</title>
</head>
<body>
    <h2>Edit Hospital</h2>
    <form method="POST">
        <label>Hospital Name:</label><br>
        <input type="text" name="hospital_name" value="<?php echo $hospital['hospital_name']; ?>" required><br>

        <label>Location:</label><br>
        <input type="text" name="location" value="<?php echo $hospital['location']; ?>" required><br>

        <label>Contact:</label><br>
        <input type="text" name="contact" value="<?php echo $hospital['contact']; ?>" required><br
