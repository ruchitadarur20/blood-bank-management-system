<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ff512f, #dd2476);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
            text-align: center;
        }
        .container {
            max-width: 500px;
        }
        .card-custom {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        .btn-custom {
            background: linear-gradient(to right, #ff512f, #dd2476);
            border: none;
            color: white;
            font-size: 16px;
            padding: 8px;
            width: 100%;
            margin-top: 8px;
            border-radius: 5px;
        }
        .btn-custom:hover {
            background: linear-gradient(to right, #dd2476, #ff512f);
        }
        .banner-img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        h1 {
            font-size: 22px;
            font-weight: bold;
        }
        h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-3 text-light">Welcome to the <span class="text-warning">Blood Bank Management System</span></h1>
        <div class="card-custom">
            <img src="https://wallpapercave.com/wp/wp7897950.jpg" alt="Blood Donation" class="banner-img">
            <h3 class="text-center text-danger">Get Started</h3>
            <a href="register.php" class="btn btn-custom"> <i class="bi bi-person-plus-fill"></i> Register as a Donor</a>
            <a href="inventory.php" class="btn btn-custom"> <i class="bi bi-box-seam"></i> View Blood Inventory</a>
            <a href="appointment.php" class="btn btn-custom"> <i class="bi bi-calendar-check-fill"></i> Book an Appointment</a>
            <a href="login.php" class="btn btn-custom"> <i class="bi bi-box-arrow-in-right"></i> Log In</a>
            <a href="signup.php" class="btn btn-custom"> <i class="bi bi-person-plus"></i> Sign Up</a>
        </div>
    </div>
</body>
</html>
