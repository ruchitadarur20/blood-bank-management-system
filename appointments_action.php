<?php
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_id = $_POST["donor_id"];
    $appointment_date = $_POST["appointment_date"];

    $stmt = $conn->prepare("INSERT INTO appointments (donor_id, appointment_date) VALUES (?, ?)");
    $stmt->bind_param("is", $donor_id, $appointment_date);

    if ($stmt->execute()) {
        echo "<script>alert('Appointment Booked Successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
