<?php
require_once 'config/database.php';
session_start();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $hospital_name = mysqli_real_escape_string($conn, $_POST['hospital_name']);
                $blood_type = mysqli_real_escape_string($conn, $_POST['blood_type']);
                $units = mysqli_real_escape_string($conn, $_POST['units']);
                $urgency = mysqli_real_escape_string($conn, $_POST['urgency']);

                $sql = "INSERT INTO requests (hospital_name, blood_type, units, urgency) 
                        VALUES ('$hospital_name', '$blood_type', '$units', '$urgency')";
                
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['success'] = "Blood request added successfully!";
                } else {
                    $_SESSION['error'] = "Error adding blood request: " . mysqli_error($conn);
                }
                break;

            case 'update':
                $id = mysqli_real_escape_string($conn, $_POST['id']);
                $status = mysqli_real_escape_string($conn, $_POST['status']);

                $sql = "UPDATE requests SET status = '$status' WHERE id = '$id'";
                
                if (mysqli_query($conn, $sql)) {
                    if ($status === 'Approved') {
                        // Update blood inventory
                        $request = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM requests WHERE id = '$id'"));
                        $blood_type = $request['blood_type'];
                        $units = $request['units'];
                        
                        // Find available blood units
                        $inventory_sql = "SELECT id, units FROM blood_inventory 
                                        WHERE blood_type = '$blood_type' 
                                        AND status = 'Available' 
                                        ORDER BY collection_date ASC";
                        $inventory_result = mysqli_query($conn, $inventory_sql);
                        
                        $remaining_units = $units;
                        while ($inventory = mysqli_fetch_assoc($inventory_result) && $remaining_units > 0) {
                            if ($inventory['units'] <= $remaining_units) {
                                mysqli_query($conn, "UPDATE blood_inventory SET status = 'Used' WHERE id = '{$inventory['id']}'");
                                $remaining_units -= $inventory['units'];
                            } else {
                                mysqli_query($conn, "UPDATE blood_inventory SET units = units - $remaining_units WHERE id = '{$inventory['id']}'");
                                $remaining_units = 0;
                            }
                        }
                    }
                    
                    $_SESSION['success'] = "Blood request updated successfully!";
                } else {
                    $_SESSION['error'] = "Error updating blood request: " . mysqli_error($conn);
                }
                break;

            case 'delete':
                $id = mysqli_real_escape_string($conn, $_POST['id']);
                $sql = "DELETE FROM requests WHERE id = '$id'";
                
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['success'] = "Blood request deleted successfully!";
                } else {
                    $_SESSION['error'] = "Error deleting blood request: " . mysqli_error($conn);
                }
                break;
        }
        header('Location: requests.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Requests - Blood Bank System</title>
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
                        <a class="nav-link active" href="requests.php">Requests</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="reports.php">Reports</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-6">
                <h2>Blood Requests</h2>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-custom btn-custom-primary" data-bs-toggle="modal" data-bs-target="#addRequestModal">
                    <i class="fas fa-plus"></i> New Request
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search requests...">
                    </div>
                    <div class="col-md-4">
                        <select id="bloodTypeFilter" class="form-control">
                            <option value="">All Blood Types</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="statusFilter" class="form-control">
                            <option value="">All Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="requestsTable">
                        <thead>
                            <tr>
                                <th>Hospital</th>
                                <th>Blood Type</th>
                                <th>Units</th>
                                <th>Urgency</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM requests ORDER BY created_at DESC";
                            $result = mysqli_query($conn, $sql);

                            while ($row = mysqli_fetch_assoc($result)) {
                                $urgency_class = [
                                    'Low' => 'success',
                                    'Medium' => 'warning',
                                    'High' => 'danger'
                                ][$row['urgency']] ?? 'secondary';

                                $status_class = [
                                    'Pending' => 'warning',
                                    'Approved' => 'success',
                                    'Rejected' => 'danger',
                                    'Completed' => 'info'
                                ][$row['status']] ?? 'secondary';

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['hospital_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['blood_type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['units']) . "</td>";
                                echo "<td><span class='badge bg-{$urgency_class}'>{$row['urgency']}</span></td>";
                                echo "<td><span class='badge bg-{$status_class}'>{$row['status']}</span></td>";
                                echo "<td>" . date('M d, Y', strtotime($row['created_at'])) . "</td>";
                                echo "<td>";
                                if ($row['status'] === 'Pending') {
                                    echo "<button class='btn btn-sm btn-success me-1' onclick='updateStatus(" . $row['id'] . ", \"Approved\")'>
                                            <i class='fas fa-check'></i>
                                          </button>
                                          <button class='btn btn-sm btn-danger me-1' onclick='updateStatus(" . $row['id'] . ", \"Rejected\")'>
                                            <i class='fas fa-times'></i>
                                          </button>";
                                }
                                echo "<button class='btn btn-sm btn-danger' onclick='deleteRequest(" . $row['id'] . ")'>
                                        <i class='fas fa-trash'></i>
                                      </button>
                                    </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Request Modal -->
    <div class="modal fade" id="addRequestModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Blood Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="requests.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label">Hospital Name</label>
                            <input type="text" class="form-control" name="hospital_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Blood Type</label>
                            <select class="form-control" name="blood_type" required>
                                <option value="">Select Blood Type</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Units Required</label>
                            <input type="number" class="form-control" name="units" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Urgency Level</label>
                            <select class="form-control" name="urgency" required>
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-custom btn-custom-primary">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this request?
                </div>
                <div class="modal-footer">
                    <form action="requests.php" method="POST">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="delete_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            searchTable('requestsTable', this);
        });

        // Blood type filter
        document.getElementById('bloodTypeFilter').addEventListener('change', function() {
            filterTable();
        });

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', function() {
            filterTable();
        });

        function filterTable() {
            const bloodType = document.getElementById('bloodTypeFilter').value;
            const status = document.getElementById('statusFilter').value;
            const rows = document.querySelectorAll('#requestsTable tbody tr');
            
            rows.forEach(row => {
                const rowBloodType = row.cells[1].textContent;
                const rowStatus = row.cells[4].textContent.trim();
                const bloodTypeMatch = !bloodType || rowBloodType === bloodType;
                const statusMatch = !status || rowStatus === status;
                
                row.style.display = bloodTypeMatch && statusMatch ? '' : 'none';
            });
        }

        // Update status function
        function updateStatus(id, status) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'requests.php';

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = 'update';

            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id';
            idInput.value = id;

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;

            form.appendChild(actionInput);
            form.appendChild(idInput);
            form.appendChild(statusInput);

            document.body.appendChild(form);
            form.submit();
        }

        // Delete request function
        function deleteRequest(id) {
            document.getElementById('delete_id').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>
</html> 