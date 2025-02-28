<?php
include("db.php");

$message = "";
$type = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("UPDATE blood_requests SET status='Rejected' WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $message = "Request Rejected!";
        $type = "error";
    } else {
        $message = "Error: Could not reject request.";
        $type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reject Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

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
    setTimeout(() => { window.location.href = "admin_dashboard.php"; }, 2000);
<?php } ?>
</script>

</body>
</html>
