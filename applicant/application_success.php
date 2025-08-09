<?php
include '../session_check.php';
include '../connection.php';

// Get user's full name
$user_id = $_SESSION['user'];
$name = "applicant";
$stmt = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $name = $row['full_name'];
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Maombi Yamepokelewa</title>
    <link rel="stylesheet" href="../student/student_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .info-box {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
            margin-bottom: 30px;
            padding: 30px;
            text-align: center;
        }

        .info-box h1 {
            color: #006400;
            font-size: 26px;
            margin-bottom: 10px;
        }

        .info-box p {
            font-size: 18px;
            margin: 10px 0;
            color: #333;
        }

        .info-box .icon-check {
            font-size: 50px;
            color: green;
            margin-bottom: 20px;
        }

        .back-btn {
            margin-top: 25px;
            display: inline-block;
            background-color: #228B22;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            text-decoration: none;
        }

        .back-btn:hover {
            background-color: #006400;
        }
    </style>
</head>
<body>
<div class="dashboard-container">

    <!-- Sidebar -->
    <?php include "../sidebar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="info-box">
            <i class="fas fa-check-circle icon-check"></i>
            <h1> Maombi Yamepokelewa</h1>
            <p>Hongera ndugu <b><?= htmlspecialchars($name) ?></b>, umefanikiwa kujaza fomu ya maombi ya kujiunga na mafunzo ya maadili ya ndoa.</p>
            <p>Subiri ujumbe kupitia <b>barua pepe yako (Gmail)</b> mara tu utakapokubaliwa rasmi.</p>

            <hr style="margin: 30px 0; border: 1px dashed #ccc;">

            <p><b>Masomo:</b> Ndoa kwa ukamilifu wake</p>
            <p><b>Ada:</b> Bure (wiki 10)</p>
            <p><b>Mafunzo:</b> Jumamosi & Jumapili, saa 2:00 - 6:00</p>

        </div>
    </div>
</div>
</body>
</html>
