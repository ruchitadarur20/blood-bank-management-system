<?php
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

include("db.php");

// Fetch Donors
$donors = $conn->query("SELECT * FROM donors");

// Fetch Blood Requests
$blood_requests = $conn->query("
    SELECT blood_requests.id, hospitals.hospital_name, blood_requests.blood_type, blood_requests.quantity, blood_requests.status
    FROM blood_requests
    JOIN hospitals ON blood_requests.hospital_id = hospitals.id
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center"><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>

        <div class="row text-center">
            <div class="col-md-6">
                <div class="p-4 shadow bg-white rounded">
                    <h4><i class="bi bi-person-fill"></i> Total Donors</h4>
                    <p class="fs-1 text-primary"><?php echo $donors->num_rows; ?></p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-4 shadow bg-white rounded">
                    <h4><i class="bi bi-droplet-fill text-danger"></i> Blood Requests</h4>
                    <p class="fs-1 text-danger"><?php echo $blood_requests->num_rows; ?></p>
                </div>
            </div>
        </div>

        <h3 class="mt-4"><i class="bi bi-list-ul"></i> Blood Requests</h3>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Hospital</th>
                    <th>Blood Type</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $blood_requests->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["hospital_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["blood_type"]); ?></td>
                        <td><?php echo htmlspecialchars($row["quantity"]); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $row["status"] == 'Approved' ? 'success' : 'warning'; ?>">
                                <?php echo htmlspecialchars($row["status"]); ?>
                            </span>
                        </td>
                        <td>
                            <a href="approve_request.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
                                <i class="bi bi-check-circle"></i> Approve
                            </a>
                            <a href="reject_request.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">
                                <i class="bi bi-x-circle"></i> Reject
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
