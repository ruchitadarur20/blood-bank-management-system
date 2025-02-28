---- admin.php ----
<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(to right, #ff416c, #ff4b2b); color: white; }
        .card { background: white; color: black; }
        .nav-link { color: white; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="container p-3">
        <div class="card p-4 shadow bg-white rounded mx-auto" style="max-width: 600px;">
            <h3 class="text-center text-danger">Admin Dashboard</h3>
            <p class="text-center">Welcome, <strong><?php echo $_SESSION["user"]; ?></strong></p>
            <div class="list-group mt-3">
                <a href="manage_users.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-people-fill"></i> Manage Users
                </a>
                <a href="manage_donations.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-droplet-fill"></i> Manage Donations
                </a>
                <a href="manage_inventory.php" class="list-group-item list-group-item-action">
                    <i class="bi bi-box-seam"></i> Manage Inventory
                </a>
                <a href="logout.php" class="list-group-item list-group-item-action list-group-item-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </div>
</body>
</html>