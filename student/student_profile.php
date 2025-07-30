<?php
include "../connection.php";
include "../session_check.php";

$user_id = $_SESSION['user'];

// Step 1: Get user info from users table
$sql_user = "SELECT * FROM users WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$student = $result_user->fetch_assoc();

// Step 2: Get application info from application_form table using full_name
$sql_form = "SELECT * FROM application_form WHERE full_name = ?";
$stmt_form = $conn->prepare($sql_form);
$stmt_form->bind_param("s", $student['full_name']);
$stmt_form->execute();
$result_form = $stmt_form->get_result();
$form_data = $result_form->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - Smart Ndoa 🎓</title>
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
            <h1>Welcome, <b><?php echo ($student['full_name']); ?></b> 🎓</h1>
        </header>

        <!-- Profile Section -->
        <div class="section active">
            <div class="info-box">
                <h3>👤 My Profile</h3>
                <table>
                    <tr><th>Full Name</th><td><?php echo $student['full_name']; ?></td></tr>
                    <tr><th>Email</th><td><?php echo $student['email']; ?></td></tr>
                    <tr><th>Phone</th><td><?php echo $form_data['phone'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Gender</th><td><?php echo $form_data['gender'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Date of Birth</th><td><?php echo $form_data['dob'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Nationality</th><td><?php echo $form_data['nationality'] ?? 'N/A'; ?></td></tr>
                    <tr><th>District</th><td><?php echo $form_data['district'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Shehia</th><td><?php echo $form_data['shehia'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Employment</th><td><?php echo $form_data['employed'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Workplace</th><td><?php echo $form_data['workplace'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Marital Status</th><td><?php echo $form_data['marital_status'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Disability</th><td><?php echo $form_data['disability'] ?? 'None'; ?></td></tr>
                    <tr><th>Education Level</th><td><?php echo $form_data['education_level'] ?? 'N/A'; ?></td></tr>
                    <tr><th>Application Status</th><td><?php echo ucfirst($form_data['status'] ?? 'pending'); ?></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
