<?php
include '../session_check.php';
include '../connection.php';

$user_id = $_SESSION['user']; // Hakikisha hii ni key sahihi ya session

// Pata jina la mwanafunzi
$name = "Mwanafunzi";
$sql = "SELECT full_name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $name = $row['full_name'];

    // Angalia kama maombi yameidhinishwa
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
    echo "<script>alert('Taarifa za mtumiaji hazijapatikana. Tafadhali ingia tena.'); window.location.href = '../login.php';</script>";
    exit;
}

// Pata data za mahudhurio
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

// Pata alama za assessment
$marks_sql = "
    SELECT COUNT(*) AS score
    FROM assessment_answers aa
    INNER JOIN assessment a ON aa.assessment_id = a.id
    WHERE aa.user_id = ? AND aa.selected_option = a.correct_option
";
$marks_stmt = $conn->prepare($marks_sql);
$marks_stmt->bind_param("i", $user_id);
$marks_stmt->execute();
$marks_result = $marks_stmt->get_result();
$score = 0;
if ($marks_row = $marks_result->fetch_assoc()) {
    $score = $marks_row['score'];
}

// Pata jumla ya maswali yote
$total_questions_sql = "SELECT COUNT(*) AS total FROM assessment";
$total_questions_result = $conn->query($total_questions_sql);
$total_questions = 0;
if ($total_row = $total_questions_result->fetch_assoc()) {
    $total_questions = $total_row['total'];
}

// Hesabu asilimia
$score_percentage = $total_questions > 0 ? round(($score / $total_questions) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard ya Mwanafunzi - Smart Ndoa</title>
    <link rel="stylesheet" href="student_dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
        }
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
            padding: 20px 30px;
            background: #fff;
        }
        header h1 {
            font-size: 24px;
            margin-bottom: 30px;
            color: #0b3954;
        }
        .info-box {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .summary-box {
            display: flex;
            justify-content: space-around;
            margin-top: 15px;
        }
        .summary-item {
            text-align: center;
            flex: 1;
        }
        .summary-item h4 {
            font-size: 18px;
            margin-bottom: 8px;
            color: #228B22;
        }
        .summary-item p {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
        }
        .percentage {
            font-weight: bold;
            font-size: 20px;
            text-align: center;
            color: #0b3954;
            margin-top: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #228B22;
            color: white;
        }
        .status-present {
            color: green;
            font-weight: bold;
        }
        .status-absent {
            color: red;
            font-weight: bold;
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
            <h1>Karibu: <b><?= htmlspecialchars($name) ?></b></h1>
        </header>

        <!-- Muhtasari wa Mahudhurio -->
        <div class="info-box">
            <h3>Muhtasari wa Mahudhurio</h3>
            <div class="summary-box">
                <div class="summary-item">
                    <h4>Jumla ya Siku</h4>
                    <p><?= $total_days ?></p>
                </div>
                <div class="summary-item">
                    <h4>Alikuwepo</h4>
                    <p><?= $present ?></p>
                </div>
                <div class="summary-item">
                    <h4>Hakukuwepo</h4>
                    <p><?= $absent ?></p>
                </div>
            </div>
            <p class="percentage">Kiwango cha Mahudhurio: <?= $attendance_rate ?>%</p>
        </div>
        <!-- Muhtasari wa Matokeo ya Assessment -->
        <div class="info-box">
            <h3>Matokeo ya Assessment</h3>
            <div class="summary-box">
                <div class="summary-item">
                    <h4>Jumla ya Maswali</h4>
                    <p><?= $total_questions ?></p>
                </div>
                <div class="summary-item">
                    <h4>Sahihi</h4>
                    <p><?= $score ?></p>
                </div>
                <div class="summary-item">
                    <h4>Asilimia</h4>
                    <p><?= $score_percentage ?>%</p>
                </div>
            </div>
        </div>


        <!-- Rekodi za Mahudhurio kwa Kila Siku -->
        <div class="info-box">
            <h3>Rekodi za Mahudhurio kwa Kila Siku</h3>
            <table>
                <thead>
                    <tr>
                        <th>Tarehe</th>
                        <th>Hali</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($rows) > 0): ?>
                        <?php foreach ($rows as $day): ?>
                            <tr>
                                <td><?= date("d-M-Y", strtotime($day['date'])) ?></td>
                                <td>
                                    <span class="status-<?= strtolower($day['status']) ?>">
                                        <?= $day['status'] == 'Present' ? 'Ulikuwepo' : 'Hukukuwepo' ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="2">Hakuna data ya mahudhurio.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
</body>
</html>
