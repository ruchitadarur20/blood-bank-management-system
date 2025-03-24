<?php
require_once 'config/database.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-heartbeat"></i> Blood Bank System
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="donors.php">Donors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="inventory.php">Inventory</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="requests.php">Requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports.php">Reports</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <!-- Quick Stats -->
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Donors</h5>
                        <?php
                        $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM donors");
                        $row = mysqli_fetch_assoc($result);
                        echo "<h2 class='card-text'>" . $row['count'] . "</h2>";
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Available Units</h5>
                        <?php
                        $result = mysqli_query($conn, "SELECT SUM(units) as total FROM blood_inventory WHERE status = 'Available'");
                        $row = mysqli_fetch_assoc($result);
                        echo "<h2 class='card-text'>" . ($row['total'] ?? 0) . "</h2>";
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Pending Requests</h5>
                        <?php
                        $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM requests WHERE status = 'Pending'");
                        $row = mysqli_fetch_assoc($result);
                        echo "<h2 class='card-text'>" . $row['count'] . "</h2>";
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Expiring Soon</h5>
                        <?php
                        $result = mysqli_query($conn, "SELECT COUNT(*) as count FROM blood_inventory WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND status = 'Available'");
                        $row = mysqli_fetch_assoc($result);
                        echo "<h2 class='card-text'>" . $row['count'] . "</h2>";
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Donations</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Donor</th>
                                        <th>Blood Type</th>
                                        <th>Units</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = mysqli_query($conn, "SELECT d.name, d.blood_type, bi.units, bi.collection_date 
                                                                 FROM blood_inventory bi 
                                                                 JOIN donors d ON bi.donor_id = d.id 
                                                                 ORDER BY bi.collection_date DESC LIMIT 5");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['blood_type']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['units']) . "</td>";
                                        echo "<td>" . date('M d, Y', strtotime($row['collection_date'])) . "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Requests</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Hospital</th>
                                        <th>Blood Type</th>
                                        <th>Units</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = mysqli_query($conn, "SELECT hospital_name, blood_type, units, status 
                                                                 FROM requests 
                                                                 ORDER BY created_at DESC LIMIT 5");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['hospital_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['blood_type']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['units']) . "</td>";
                                        echo "<td><span class='badge bg-" . 
                                            ($row['status'] == 'Pending' ? 'warning' : 
                                            ($row['status'] == 'Approved' ? 'success' : 
                                            ($row['status'] == 'Rejected' ? 'danger' : 'info'))) . 
                                            "'>" . htmlspecialchars($row['status']) . "</span></td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html> 