<?php
include '../session_check.php';
include '../connection.php';

$user_id = $_SESSION['user']; // Make sure this matches your login session key

// Get student name
$name = "Student";
$sql = "SELECT full_name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $name = $row['full_name'];

    // Check if application is approved
    $check_status_query = "SELECT status FROM application_form WHERE full_name = ?";
    $check_stmt = $conn->prepare($check_status_query);
    $check_stmt->bind_param("s", $name);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($status_row = $check_result->fetch_assoc()) {
        if ($status_row['status'] !== 'approved') {
            echo "<script>alert('Maombi yako bado hayajaidhinishwa!'); window.location.href = '../applicant/application_success.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Hujajaza fomu ya maombi bado!'); window.location.href = '../applicant/applicant_dashboard.php';</script>";
        exit;
    }

} else {
    echo "<script>alert('Taarifa za mtumiaji hazijapatikana. Tafadhali login tena.'); window.location.href = '../login.php';</script>";
    exit;
}

//  Fetch attendance data
$attendance_sql = "SELECT * FROM attendances WHERE user_id = ? ORDER BY date DESC";
$attendance_stmt = $conn->prepare($attendance_sql);
$attendance_stmt->bind_param("i", $user_id);
$attendance_stmt->execute();
$attendance_result = $attendance_stmt->get_result();

$total_days = $attendance_result->num_rows;
$present = 0;
$absent = 0;
$rows = [];

while ($att_row = $attendance_result->fetch_assoc()) {
    $rows[] = $att_row;
    if ($att_row['status'] == 'Present') {
        $present++;
    } else {
        $absent++;
    }
}

$attendance_rate = $total_days > 0 ? round(($present / $total_days) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - Smart Ndoa </title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dashboard-container">

    <!-- Sidebar -->
    <?php include "../sidebar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Welcome: <b><?= htmlspecialchars($name) ?></b> </h1>
        </header>

        <!-- Attendance Summary -->
        <div class="info-box">
            <h3>Attendance Overview</h3>
            <div class="summary-box">
                <div class="summary-item">
                    <h4>Total Days</h4>
                    <p><?= $total_days ?></p>
                </div>
                <div class="summary-item">
                    <h4>Present</h4>
                    <p><?= $present ?></p>
                </div>
                <div class="summary-item">
                    <h4>Absent</h4>
                    <p><?= $absent ?></p>
                </div>
            </div>
            <br>
            <p class="percentage">Attendance Rate: <?= $attendance_rate ?>%</p>
        </div>

        <!-- Detailed Attendance -->
        <div class="info-box">
            <h3>Daily Attendance Record</h3>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($rows) > 0): ?>
                        <?php foreach ($rows as $day): ?>
                            <tr>
                                <td><?= date("d-M-Y", strtotime($day['date'])) ?></td>
                                <td>
                                    <span class="status-<?= strtolower($day['status']) ?>">
                                        <?= $day['status'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="2">No attendance data available.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
</body>
</html>
