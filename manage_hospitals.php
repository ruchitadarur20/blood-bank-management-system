<?php
include("db.php");

// Fetch all hospitals
$hospitals = $conn->query("SELECT * FROM hospitals");

// Check for success messages
$message = isset($_GET['message']) ? $_GET['message'] : "";
$type = isset($_GET['type']) ? $_GET['type'] : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Hospitals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center"><i class="bi bi-hospital"></i> Manage Hospitals</h2>
        <div class="d-flex justify-content-end">
            <a href="register_hospital.php" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Register Hospital
            </a>
        </div>

        <table class="table table-striped table-hover mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Contact</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $hospitals->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["hospital_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["location"]); ?></td>
                        <td><?php echo htmlspecialchars($row["contact"]); ?></td>
                        <td>
                            <a href="edit_hospital.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="delete_hospital.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Toast Notification -->
    <div id="toastContainer" class="position-fixed bottom-0 end-0 p-3"></div>

    <script>
    function showToast(message, type) {
        let bgColor = type === 'success' ? 'bg-success' : 'bg-danger';
        let toastHTML = `<div class="toast show ${bgColor} text-white" role="alert">
                            <div class="toast-body">${message}</div>
                         </div>`;
        document.getElementById('toastContainer').innerHTML = toastHTML;
        setTimeout(() => { document.querySelector('.toast').remove(); }, 3000);
    }

    // Show toast if message is set
    <?php if (!empty($message)) { ?>
        showToast("<?php echo $message; ?>", "<?php echo $type; ?>");
    <?php } ?>
    </script>

</body>
</html>
