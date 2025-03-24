<?php
require_once 'config/database.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $sql = "SELECT * FROM blood_inventory WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Blood unit not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No blood unit ID provided']);
}

mysqli_close($conn);
?> 