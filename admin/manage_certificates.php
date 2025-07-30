<?php
include '../session_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Certificates</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f4f9;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            background-color: #f5f7fa;
        }

        .certificate-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            padding: 25px 30px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .certificate-card h2 {
            text-align: center;
            font-size: 26px;
            color: #2d3436;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background-color: #3498db;
            color: white;
        }

        th, td {
            padding: 14px 12px;
            text-align: center;
            font-size: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        tr:nth-child(even) {
            background-color: #f9fcff;
        }

        .btn {
            background-color: #3498db;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2c80b4;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include '../sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="certificate-card">
                <h2>🎓 Manage Certificates</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Course</th>
                            <th>Completion Date</th>
                            <th>Certificate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ali Juma</td>
                            <td>Marriage Ethics</td>
                            <td>2025-05-05</td>
                            <td>Not Issued</td>
                            <td><button class="btn">Issue Certificate</button></td>
                        </tr>
                        <tr>
                            <td>Fatma Salim</td>
                            <td>Marriage Ethics</td>
                            <td>2025-05-05</td>
                            <td>Issued</td>
                            <td><button class="btn">View</button></td>
                        </tr>
                        <!-- You can add more rows here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
