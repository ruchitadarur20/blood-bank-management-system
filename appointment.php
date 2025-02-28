<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ff512f, #dd2476);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }
        .appointment-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container appointment-container">
        <h2 class="text-center text-danger mb-4"><i class="bi bi-calendar-check-fill"></i> Book an Appointment</h2>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Donor ID</label>
                <input type="text" name="donor_id" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Appointment Date</label>
                <input type="date" name="appointment_date" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-danger w-100">Book Now</button>
        </form>
    </div>
</body>
</html>
