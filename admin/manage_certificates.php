<?php
include '../connection.php';
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
        .users {
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background: #228B22;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            color: white;
        }

        .icon-action {
            font-size: 18px;
            margin-right: 10px;
        }

        .icon-view {
            color: #2980b9;
        }

        .icon-download {
            color: #27ae60;
        }

        .not-eligible {
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <?php include '../sidebar.php'; ?>

    <div class="main-content">
        <header>
            <h1>Manage Certificates</h1>
        </header>

        <div class="users">
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Student Name</th>
                        <th>Course</th>
                        <th>Present Days</th>
                        <th>Eligibility</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT u.user_id, u.full_name, 
                                   COUNT(a.status) AS present_days
                            FROM users u
                            LEFT JOIN attendances a ON u.user_id = a.user_id AND a.status = 'Present'
                            WHERE u.role = 'student'
                            GROUP BY u.user_id, u.full_name";

                    $result = $conn->query($sql);
                    $sn = 1;

                    while ($row = $result->fetch_assoc()) {
                        $user_id = $row['user_id'];
                        $name = $row['full_name'];
                        $present = $row['present_days'];
                        $total_sessions = 1; // Update this later
                        $percentage = ($present / $total_sessions) * 100;
                        $eligible = $percentage >= 75;

                        $status = $eligible ? '<span style="color:green;">Eligible</span>' : '<span style="color:red;">Not Eligible</span>';

                        $action = $eligible
                            ? "
                                <a href='view_certificate.php?user_id=$user_id' title='View Certificate' class='icon-action icon-view' target='_blank'>
                                    <i class='fas fa-eye'></i>
                                </a>
                                <a href='download_certificate.php?user_id=$user_id' title='Download Certificate' class='icon-action icon-download'>
                                    <i class='fas fa-download'></i>
                                </a>
                              "
                            : "<span class='not-eligible'>Not Eligible</span>";

                        echo "<tr>
                                <td>$sn</td>
                                <td>$name</td>
                                <td>Marriage Ethics</td>
                                <td>$present</td>
                                <td>$status</td>
                                <td>$action</td>
                              </tr>";
                        $sn++;
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
