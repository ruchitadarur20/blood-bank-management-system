<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $blood_type = $_POST["blood_type"];
    $contact = $_POST["contact"];
    $email = $_POST["email"];

    // Secure Insert with Prepared Statement
    $stmt = $conn->prepare("INSERT INTO donors (name, blood_type, contact, email) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $blood_type, $contact, $email);

    if ($stmt->execute()) {
        $message = "Registration Successful!";
        $type = "success";
    } else {
        $message = "Error: Could not register.";
        $type = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Action</title>
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
    setTimeout(() => { window.location.href = "index.php"; }, 2000);
<?php } ?>
</script>

</body>
</html>
