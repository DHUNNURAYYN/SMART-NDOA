<?php
include "../connection.php";
include "../session_check.php";

$user_id = $_SESSION['user'];

// Hatua 1: Pata taarifa za mtumiaji kutoka users table
$sql_user = "SELECT * FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$student = $result_user->fetch_assoc();

// Hatua 2: Pata taarifa za fomu ya maombi kutoka application_form kwa kutumia full_name
$sql_form = "SELECT * FROM application_form WHERE full_name = ?";
$stmt_form = $conn->prepare($sql_form);
$stmt_form->bind_param("s", $student['full_name']);
$stmt_form->execute();
$result_form = $stmt_form->get_result();
$form_data = $result_form->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Dashboard ya Mwanafunzi - Smart Ndoa 🎓</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .info-box {
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            border-radius: 8px;
        }
        .info-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-box th, .info-box td {
            text-align: left;
            padding: 8px 12px;
            border-bottom: 1px solid #eee;
        }
        .main-content header h1 {
            margin-bottom: 20px;
            color: #0b3954;
        }
        h3 {
            color: #228B22;
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
            <h1>Karibu, <b><?php echo htmlspecialchars($student['full_name']); ?></b> 🎓</h1>
        </header>

        <!-- Sehemu ya Profaili -->
        <div class="section active">
            <div class="info-box">
                <h3>👤 Profaili Yangu</h3>
                <table>
                    <tr><th>Jina Kamili</th><td><?php echo htmlspecialchars($student['full_name']); ?></td></tr>
                    <tr><th>Barua Pepe (Email)</th><td><?php echo htmlspecialchars($student['email']); ?></td></tr>
                    <tr><th>Simu</th><td><?php echo htmlspecialchars($form_data['phone'] ?? 'Haipo'); ?></td></tr>
                    <tr><th>Jinsia</th><td><?php echo htmlspecialchars($form_data['gender'] ?? 'Haipo'); ?></td></tr>
                    <tr><th>Tarehe ya Kuzaliwa</th><td><?php echo htmlspecialchars($form_data['dob'] ?? 'Haipo'); ?></td></tr>
                    <tr><th>Uraia</th><td><?php echo htmlspecialchars($form_data['nationality'] ?? 'Haipo'); ?></td></tr>
                    <tr><th>Wilaya</th><td><?php echo htmlspecialchars($form_data['district'] ?? 'Haipo'); ?></td></tr>
                    <tr><th>Shehia</th><td><?php echo htmlspecialchars($form_data['shehia'] ?? 'Haipo'); ?></td></tr>
                    <tr><th>Kazi</th><td><?php echo htmlspecialchars($form_data['employed'] ?? 'Haipo'); ?></td></tr>
                    <tr><th>Mahali pa Kazi</th><td><?php echo htmlspecialchars($form_data['workplace'] ?? 'Haipo'); ?></td></tr>
                    <tr><th>Hali ya Ndoa</th><td><?php echo htmlspecialchars($form_data['marital_status'] ?? 'Haipo'); ?></td></tr>
                    <tr><th>Ulemavu</th><td><?php echo htmlspecialchars($form_data['disability'] ?? 'Hakuna'); ?></td></tr>
                    <tr><th>Elimu</th><td><?php echo htmlspecialchars($form_data['education_level'] ?? 'Haipo'); ?></td></tr>
                    <tr><th>Hali ya Maombi</th><td><?php echo ucfirst(htmlspecialchars($form_data['status'] ?? 'inayosubiri')); ?></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
