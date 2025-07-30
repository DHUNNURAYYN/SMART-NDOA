<?php
include '../session_check.php';
include '../connection.php';

// Get the logged-in student ID
$user_id = $_SESSION['user'];

// Get student's full name from database
$name = "applicant"; // default
$name_query = "SELECT full_name FROM users WHERE user_id = ?";
$name_stmt = $conn->prepare($name_query);
$name_stmt->bind_param("i", $user_id);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
if ($name_row = $name_result->fetch_assoc()) {
    $name = $name_row['full_name'];
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - Smart Ndoa 🎓</title>
    <link rel="stylesheet" href="../student/student_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="dashboard-container">

    <!-- Sidebar -->
    <?php include "../sidebar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Welcome, <b><?=($name) ?></b> </h1>
        </header>

        <!-- Attendance Summary -->
        <div class="info-box">
            <h3>Tafadhali jaza Form ya Maombi</h3>
         </div>

    </div>
</div>
</body>
</html>
