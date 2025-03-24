<?php
require_once 'config/database.php';
session_start();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $blood_type = mysqli_real_escape_string($conn, $_POST['blood_type']);
                $units = mysqli_real_escape_string($conn, $_POST['units']);
                $collection_date = mysqli_real_escape_string($conn, $_POST['collection_date']);
                $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);
                $donor_id = mysqli_real_escape_string($conn, $_POST['donor_id']);

                $sql = "INSERT INTO blood_inventory (blood_type, units, collection_date, expiry_date, donor_id) 
                        VALUES ('$blood_type', '$units', '$collection_date', '$expiry_date', '$donor_id')";
                
                if (mysqli_query($conn, $sql)) {
                    // Update donor's last donation date
                    $update_donor = "UPDATE donors SET last_donation_date = '$collection_date' WHERE id = '$donor_id'";
                    mysqli_query($conn, $update_donor);
                    
                    $_SESSION['success'] = "Blood unit added successfully!";
                } else {
                    $_SESSION['error'] = "Error adding blood unit: " . mysqli_error($conn);
                }
                break;

            case 'update':
                $id = mysqli_real_escape_string($conn, $_POST['id']);
                $blood_type = mysqli_real_escape_string($conn, $_POST['blood_type']);
                $units = mysqli_real_escape_string($conn, $_POST['units']);
                $collection_date = mysqli_real_escape_string($conn, $_POST['collection_date']);
                $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);
                $status = mysqli_real_escape_string($conn, $_POST['status']);

                $sql = "UPDATE blood_inventory SET 
                        blood_type = '$blood_type',
                        units = '$units',
                        collection_date = '$collection_date',
                        expiry_date = '$expiry_date',
                        status = '$status'
                        WHERE id = '$id'";
                
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['success'] = "Blood unit updated successfully!";
                } else {
                    $_SESSION['error'] = "Error updating blood unit: " . mysqli_error($conn);
                }
                break;

            case 'delete':
                $id = mysqli_real_escape_string($conn, $_POST['id']);
                $sql = "DELETE FROM blood_inventory WHERE id = '$id'";
                
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['success'] = "Blood unit deleted successfully!";
                } else {
                    $_SESSION['error'] = "Error deleting blood unit: " . mysqli_error($conn);
                }
                break;
        }
        header('Location: inventory.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Inventory - Blood Bank System</title>
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
                        <a class="nav-link active" href="inventory.php">Inventory</a>
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
                <h2>Blood Inventory</h2>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-custom btn-custom-primary" data-bs-toggle="modal" data-bs-target="#addInventoryModal">
                    <i class="fas fa-plus"></i> Add New Unit
                </button>
            </div>
        </div>

        <!-- Blood Type Summary Cards -->
        <div class="row mb-4">
            <?php
            $blood_types = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
            $colors = ['primary', 'success', 'info', 'warning', 'danger', 'secondary', 'dark', 'light'];
            
            foreach ($blood_types as $index => $type) {
                $sql = "SELECT SUM(units) as total FROM blood_inventory WHERE blood_type = '$type' AND status = 'Available'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                $total = $row['total'] ?? 0;
                
                echo "<div class='col-md-3 mb-4'>
                        <div class='card bg-{$colors[$index]} text-white inventory-card' data-units='{$total}'>
                            <div class='card-body'>
                                <h5 class='card-title'>{$type}</h5>
                                <h2 class='card-text'>{$total}</h2>
                                <div class='stock-warning' style='display: none;'>
                                    <small>Low Stock Alert!</small>
                                </div>
                            </div>
                        </div>
                    </div>";
            }
            ?>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search inventory...">
                    </div>
                    <div class="col-md-4">
                        <select id="bloodTypeFilter" class="form-control">
                            <option value="">All Blood Types</option>
                            <?php foreach ($blood_types as $type): ?>
                                <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select id="statusFilter" class="form-control">
                            <option value="">All Status</option>
                            <option value="Available">Available</option>
                            <option value="Reserved">Reserved</option>
                            <option value="Used">Used</option>
                            <option value="Expired">Expired</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="inventoryTable">
                        <thead>
                            <tr>
                                <th>Blood Type</th>
                                <th>Units</th>
                                <th>Collection Date</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Donor</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT bi.*, d.name as donor_name 
                                   FROM blood_inventory bi 
                                   LEFT JOIN donors d ON bi.donor_id = d.id 
                                   ORDER BY bi.collection_date DESC";
                            $result = mysqli_query($conn, $sql);

                            while ($row = mysqli_fetch_assoc($result)) {
                                $status_class = [
                                    'Available' => 'success',
                                    'Reserved' => 'warning',
                                    'Used' => 'info',
                                    'Expired' => 'danger'
                                ][$row['status']] ?? 'secondary';

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['blood_type']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['units']) . "</td>";
                                echo "<td>" . date('M d, Y', strtotime($row['collection_date'])) . "</td>";
                                echo "<td>" . date('M d, Y', strtotime($row['expiry_date'])) . "</td>";
                                echo "<td><span class='badge bg-{$status_class}'>{$row['status']}</span></td>";
                                echo "<td>" . htmlspecialchars($row['donor_name']) . "</td>";
                                echo "<td>
                                        <button class='btn btn-sm btn-primary' onclick='editInventory(" . $row['id'] . ")'>
                                            <i class='fas fa-edit'></i>
                                        </button>
                                        <button class='btn btn-sm btn-danger' onclick='deleteInventory(" . $row['id'] . ")'>
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

    <!-- Add Inventory Modal -->
    <div class="modal fade" id="addInventoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Blood Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="inventory.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label class="form-label">Blood Type</label>
                            <select class="form-control" name="blood_type" required>
                                <option value="">Select Blood Type</option>
                                <?php foreach ($blood_types as $type): ?>
                                    <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Units</label>
                            <input type="number" class="form-control" name="units" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Collection Date</label>
                            <input type="date" class="form-control" name="collection_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="expiry_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Donor</label>
                            <select class="form-control" name="donor_id" required>
                                <option value="">Select Donor</option>
                                <?php
                                $donors = mysqli_query($conn, "SELECT id, name, blood_type FROM donors ORDER BY name");
                                while ($donor = mysqli_fetch_assoc($donors)) {
                                    echo "<option value='{$donor['id']}'>{$donor['name']} ({$donor['blood_type']})</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-custom btn-custom-primary">Add Unit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Inventory Modal -->
    <div class="modal fade" id="editInventoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Blood Unit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="inventory.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Blood Type</label>
                            <select class="form-control" name="blood_type" id="edit_blood_type" required>
                                <option value="">Select Blood Type</option>
                                <?php foreach ($blood_types as $type): ?>
                                    <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Units</label>
                            <input type="number" class="form-control" name="units" id="edit_units" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Collection Date</label>
                            <input type="date" class="form-control" name="collection_date" id="edit_collection_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" name="expiry_date" id="edit_expiry_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status" id="edit_status" required>
                                <option value="Available">Available</option>
                                <option value="Reserved">Reserved</option>
                                <option value="Used">Used</option>
                                <option value="Expired">Expired</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-custom btn-custom-primary">Update Unit</button>
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
                    Are you sure you want to delete this blood unit?
                </div>
                <div class="modal-footer">
                    <form action="inventory.php" method="POST">
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
            searchTable('inventoryTable', this);
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
            const rows = document.querySelectorAll('#inventoryTable tbody tr');
            
            rows.forEach(row => {
                const rowBloodType = row.cells[0].textContent;
                const rowStatus = row.cells[4].textContent.trim();
                const bloodTypeMatch = !bloodType || rowBloodType === bloodType;
                const statusMatch = !status || rowStatus === status;
                
                row.style.display = bloodTypeMatch && statusMatch ? '' : 'none';
            });
        }

        // Edit inventory function
        function editInventory(id) {
            fetch(`get_inventory.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_id').value = data.id;
                    document.getElementById('edit_blood_type').value = data.blood_type;
                    document.getElementById('edit_units').value = data.units;
                    document.getElementById('edit_collection_date').value = data.collection_date;
                    document.getElementById('edit_expiry_date').value = data.expiry_date;
                    document.getElementById('edit_status').value = data.status;
                    
                    new bootstrap.Modal(document.getElementById('editInventoryModal')).show();
                });
        }

        // Delete inventory function
        function deleteInventory(id) {
            document.getElementById('delete_id').value = id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Initialize date validation
        document.addEventListener('DOMContentLoaded', function() {
            validateDates();
        });
    </script>
</body>
</html> 