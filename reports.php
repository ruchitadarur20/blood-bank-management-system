<?php
include("db_connect.php");

// Fetch all hospitals for dropdown
$hospitals_result = $conn->query("SELECT id, hospital_name FROM hospitals");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center">Blood Bank Reports & Analytics</h2>

        <!-- Filter Form -->
        <div class="row mt-4">
            <div class="col-md-4">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="hospital_filter">Select Hospital:</label>
                <select id="hospital_filter" class="form-control">
                    <option value="">All Hospitals</option>
                    <?php while ($row = $hospitals_result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['hospital_name']); ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="text-center mt-3">
            <button class="btn btn-primary" onclick="filterReports()">Apply Filters</button>
        </div>

        <h3 class="mt-5">Monthly Blood Donations</h3>
        <canvas id="donationChart"></canvas>

        <h3 class="mt-5">Hospital Blood Requests</h3>
        <canvas id="hospitalChart"></canvas>
    </div>

    <script>
        function filterReports() {
            let startDate = document.getElementById("start_date").value;
            let endDate = document.getElementById("end_date").value;
            let hospitalId = document.getElementById("hospital_filter").value;

            $.ajax({
                url: "fetch_report_data.php",
                type: "POST",
                data: { start_date: startDate, end_date: endDate, hospital_id: hospitalId },
                success: function(response) {
                    let data = JSON.parse(response);

                    updateChart(donationChart, data.months, data.donation_counts);
                    updateChart(hospitalChart, data.hospitals, data.request_counts);
                }
            });
        }

        function updateChart(chart, labels, data) {
            chart.data.labels = labels;
            chart.data.datasets[0].data = data;
            chart.update();
        }

        // Initialize Charts
        const donationCtx = document.getElementById('donationChart').getContext('2d');
        const donationChart = new Chart(donationCtx, {
            type: 'line',
            data: { labels: [], datasets: [{ label: 'Donations', data: [], borderColor: 'blue', fill: false }] }
        });

        const hospitalCtx = document.getElementById('hospitalChart').getContext('2d');
        const hospitalChart = new Chart(hospitalCtx, {
            type: 'bar',
            data: { labels: [], datasets: [{ label: 'Blood Requests', data: [], backgroundColor: 'red' }] }
        });
    </script>
</body>
</html>
