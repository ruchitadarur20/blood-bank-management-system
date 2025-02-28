<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Blood Inventory</title>
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
        .inventory-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="container inventory-container">
        <h2 class="text-center text-danger mb-4"><i class="bi bi-droplet-fill"></i> Available Blood Inventory</h2>
        <table class="table table-bordered table-striped text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Blood Type</th>
                    <th>Quantity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>A+</td>
                    <td>10</td>
                    <td><span class="badge bg-success">Available</span></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>B+</td>
                    <td>15</td>
                    <td><span class="badge bg-success">Available</span></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>O-</td>
                    <td>5</td>
                    <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>AB+</td>
                    <td>8</td>
                    <td><span class="badge bg-success">Available</span></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>O+</td>
                    <td>2</td>
                    <td><span class="badge bg-danger">Critical</span></td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>A-</td>
                    <td>12</td>
                    <td><span class="badge bg-success">Available</span></td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>B-</td>
                    <td>7</td>
                    <td><span class="badge bg-success">Available</span></td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>AB-</td>
                    <td>3</td>
                    <td><span class="badge bg-warning text-dark">Low Stock</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
