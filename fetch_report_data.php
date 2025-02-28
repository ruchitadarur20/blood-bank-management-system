<?php
include("db_connect.php");

$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$hospital_id = $_POST['hospital_id'] ?? null;

// Build Dynamic Query
$donation_query = "SELECT MONTHNAME(appointment_date) AS month, COUNT(*) AS count FROM appointments WHERE 1";
$request_query = "SELECT hospitals.hospital_name, COUNT(*) AS count FROM blood_requests JOIN hospitals ON blood_requests.hospital_id = hospitals.id WHERE 1";

if ($start_date) {
    $donation_query .= " AND appointment_date >= '$start_date'";
    $request_query .= " AND blood_requests.request_date >= '$start_date'";
}
if ($end_date) {
    $donation_query .= " AND appointment_date <= '$end_date'";
    $request_query .= " AND blood_requests.request_date <= '$end_date'";
}
if ($hospital_id) {
    $request_query .= " AND hospitals.id = '$hospital_id'";
}

$donation_query .= " GROUP BY MONTH(appointment_date)";
$request_query .= " GROUP BY hospitals.hospital_name";

$donation_result = $conn->query($donation_query);
$request_result = $conn->query($request_query);

$months = [];
$donation_counts = [];
while ($row = $donation_result->fetch_assoc()) {
    $months[] = $row["month"];
    $donation_counts[] = $row["count"];
}

$hospitals = [];
$request_counts = [];
while ($row = $request_result->fetch_assoc()) {
    $hospitals[] = $row["hospital_name"];
    $request_counts[] = $row["count"];
}

// Return JSON data for AJAX response
echo json_encode([
    "months" => $months,
    "donation_counts" => $donation_counts,
    "hospitals" => $hospitals,
    "request_counts" => $request_counts
]);
?>
