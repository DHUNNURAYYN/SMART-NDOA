<?php
include '../connection.php';
include '../session_check.php';

// Get the logged-in user ID
$user_id = $_SESSION['user_id'];

// Total sessions = 10 weeks × 2 days (Sat & Sun) = 20
$total_sessions = 20;

// Count 'Present' entries
$sql = "SELECT COUNT(*) as present_days FROM attendances 
        WHERE user_id = ? AND status = 'Present'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$present_days = $row['present_days'];
$attendance_percentage = ($present_days / $total_sessions) * 100;

// Determine eligibility
$eligibility = '';
if ($attendance_percentage >= 75) {
    $eligibility = "<span style='color: green;'>✔️ Eligible for Certificate</span>";
} elseif ($attendance_percentage < 70) {
    $eligibility = "<span style='color: red;'>❌ Not Eligible for Certificate</span>";
} else {
    $eligibility = "<span style='color: orange;'>⚠️ Borderline</span>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance Summary</title>
    <style>
        .summary {
            width: 60%;
            margin: 40px auto;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            font-family: sans-serif;
        }
        .summary h2 { color: #333; }
        .summary p { font-size: 18px; }
    </style>
</head>
<body>
    <div class="summary">
        <h2>📋 Attendance Report</h2>
        <p><strong>Total Sessions:</strong> 20 (10 Weeks × 2 Days)</p>
        <p><strong>Days Attended:</strong> <?= $present_days ?></p>
        <p><strong>Attendance Percentage:</strong> <?= round($attendance_percentage, 2) ?>%</p>
        <p><strong>Status:</strong> <?= $eligibility ?></p>
    </div>
</body>
</html>
