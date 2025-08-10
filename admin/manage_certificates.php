<?php 
include '../connection.php';
include '../session_check.php';

// Create the visibility table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS attendance_visibility (
    id INT AUTO_INCREMENT PRIMARY KEY,
    is_visible TINYINT(1) DEFAULT 0
)");

//  Insert a default row if empty
$conn->query("INSERT IGNORE INTO attendance_visibility (id, is_visible) VALUES (1, 0)");

//  Handle toggle request
if (isset($_POST['toggle_visibility'])) {
    $new_visibility = $_POST['current'] == '1' ? 0 : 1;
    $conn->query("UPDATE attendance_visibility SET is_visible = $new_visibility WHERE id = 1");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

//  Fetch current visibility
$visibility_result = $conn->query("SELECT is_visible FROM attendance_visibility WHERE id = 1");
$visibility = ($visibility_result && $visibility_result->num_rows > 0) 
    ? $visibility_result->fetch_assoc()['is_visible'] 
    : 0;
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Simamia Vyeti</title>
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

        .icon-view { color: #2980b9; }
        .icon-download { color: #27ae60; }
        .not-eligible { color: #999; font-style: italic; }

        .toggle-btn {
            padding: 10px 20px;
            background-color: #b22222;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .toggle-btn.red {
            background-color: #228B22;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <?php include '../sidebar.php'; ?>

    <div class="main-content">
        <header><h1>Simamia Vyeti</h1></header>

        <div class="users">
            <!--  Toggle Button -->
            <form method="POST" style="margin-bottom: 20px;">
                <input type="hidden" name="current" value="<?= $visibility ?>">
                <button type="submit" name="toggle_visibility" class="toggle-btn <?= $visibility ? 'red' : '' ?>">
                   <?= $visibility 
                        ? '<i class="fas fa-check-circle" style="color:green;"></i> Ruhusu Vyeti' 
                        : '<i class="fas fa-times-circle" style="color:red;"></i> Zuia Vyeti' 
                    ?>
                </button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Jina la Mwanafunzi</th>
                        <th>Kozi</th>
                        <th>Siku za Kuwapo</th>
                        <th>Ustahiki</th>
                        <th>Hatua</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $session_result = $conn->query("SELECT COUNT(DISTINCT date) AS total FROM attendances");
                    $total_sessions = ($session_result && $session_result->num_rows > 0) 
                        ? $session_result->fetch_assoc()['total'] 
                        : 1;

                    $sql = "SELECT u.user_id, u.full_name, COUNT(a.status) AS present_days
                            FROM users u
                            LEFT JOIN attendances a 
                            ON u.user_id = a.user_id AND a.status = 'Present'
                            WHERE u.role = 'student'
                            GROUP BY u.user_id, u.full_name";

                    $result = $conn->query($sql);
                    $sn = 1;

                    while ($row = $result->fetch_assoc()) {
                        $user_id = $row['user_id'];
                        $name = $row['full_name'];
                        $present = $row['present_days'];
                        $percentage = ($total_sessions > 0) ? ($present / $total_sessions) * 100 : 0;
                        $eligible = $percentage >= 75;

                        $status = $eligible 
                            ? '<span style="color:green;">Mstahiki</span>' 
                            : '<span style="color:red;">Hastahiki</span>';

                        $action = $eligible
                            ? "
                                <a href='view_certificate.php?user_id=$user_id' title='Tazama Cheti' class='icon-action icon-view' target='_blank'>
                                    <i class='fas fa-eye'></i>
                                </a>
                                <a href='download_certificate.php?user_id=$user_id' title='Pakua Cheti' class='icon-action icon-download'>
                                    <i class='fas fa-download'></i>
                                </a>
                              "
                            : "<span class='not-eligible'>Hastahiki</span>";

                        echo "<tr>
                                <td>$sn</td>
                                <td>$name</td>
                                <td>Maadili ya Ndoa</td>
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
