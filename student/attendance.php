<?php
include '../connection.php';
include '../session_check.php';

$user_id = $_SESSION['user'];

// Get student name
$name = "Mwanafunzi";
$sql = "SELECT full_name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $name = $row['full_name'];
}

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
    $eligibility = "<span style='color: green; font-weight:bold;'>Unastahili Cheti</span>";
} elseif ($attendance_percentage < 70) {
    $eligibility = "<span style='color: red; font-weight:bold;'>Haujastahili Cheti</span>";
} else {
    $eligibility = "<span style='color: orange; font-weight:bold;'>Kiwango cha Kati</span>";
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Ripoti ya Hudhurio - Smart Ndoa</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
        }

        .summary {
            width: 70%;
            margin: 40px auto;
            padding: 25px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        .summary h2 {
            color: #0b3954;
            margin-bottom: 20px;
        }

        .summary p {
            font-size: 18px;
            margin: 10px 0;
        }

        .btn-download {
            display: inline-block;
            padding: 12px 28px;
            margin-top: 25px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
        }

        .btn-download:hover {
            background-color: #1e7e34;
        }

        .waiting-message {
            color: #ff6600;
            font-weight: bold;
            margin-top: 25px;
        }

        header h1 {
            font-size: 24px;
            margin: 20px;
        }
    </style>
</head>
<body>
<div class="dashboard-container">

    <!-- Sidebar -->
    <?php include "../sidebar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Karibu, <b><?= htmlspecialchars($name) ?></b></h1>
        </header>
        <?php 
        $query = "SELECT is_visible FROM attendance_visibility WHERE id = 1;";
        $result = $conn->query($query);

        if ($result && $row = $result->fetch_assoc()) {
            if ($row['is_visible'] == 0) {
                echo "Samahani, matokeo hayajatolewa bado.";
            } else { ?>
                <div class="summary">
                    <h2>Ripoti ya Hudhurio</h2>
                    <p><strong>Jumla ya Siku:</strong> 20 (Wiki 10 × Siku 2)</p>
                    <p><strong>Siku Ulihudhuria:</strong> <?= $present_days ?></p>
                    <p><strong>Asilimia ya Hudhurio:</strong> <?= round($attendance_percentage, 2) ?>%</p>
                    <p><strong>Hali:</strong> <?= $eligibility ?></p>

                    <?php if ($attendance_percentage >= 75): ?>
                        <a href="download_certificate.php" class="btn-download" target="_blank">⬇ Pakua Cheti</a>
                    <?php else: ?>
                        <p class="waiting-message">Tafadhali subiri muhula mwingine ili kustahili cheti.</p>
                    <?php endif; ?>
                </div>
        <?php }
        } else {
            echo "Kuna tatizo katika kuchukua taarifa.";
        }
        ?>
    </div>

</div>
</body>
</html>
