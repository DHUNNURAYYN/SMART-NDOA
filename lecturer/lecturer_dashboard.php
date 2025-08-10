<?php
include '../connection.php';
include '../session_check.php';

$user_id = (int)$_SESSION['user']; // cast to int for safety

// Get lecturer name
$name = "Lecturer";
$name_query = "SELECT full_name FROM users WHERE user_id = ?";
$name_stmt = $conn->prepare($name_query);
$name_stmt->bind_param("i", $user_id);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
if ($name_row = $name_result->fetch_assoc()) {
    $name = $name_row['full_name'];
}
$name_stmt->close();

// Count total students
$studentQuery = $conn->query("SELECT COUNT(*) AS total_students FROM users WHERE role = 'student'");
$studentData = $studentQuery ? $studentQuery->fetch_assoc() : ['total_students' => 0];

// Count present students
$presentQuery = $conn->query("
    SELECT COUNT(*) AS total_present 
    FROM attendances 
    INNER JOIN users ON attendances.user_id = users.user_id 
    WHERE attendances.status = 'Present' AND users.role = 'student'
");
$presentData = $presentQuery ? $presentQuery->fetch_assoc() : ['total_present' => 0];

// Count absent students
$absentQuery = $conn->query("
    SELECT COUNT(*) AS total_absent 
    FROM attendances 
    INNER JOIN users ON attendances.user_id = users.user_id 
    WHERE attendances.status = 'Absent' AND users.role = 'student'
");
$absentData = $absentQuery ? $absentQuery->fetch_assoc() : ['total_absent' => 0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Lecturer Dashboard</title>
    <link rel="stylesheet" href="../dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <div class="dashboard-container">
        <?php include '../sidebar.php'; ?>

        <div class="main-content">
            <header>
                <h1>Karibu: <b><?= htmlspecialchars($name) ?></b></h1>
            </header>

            <div class="cards-container">
                <div class="cards">
                    <div class="card">
                        <i class="fas fa-user-graduate"></i>
                        <h3>Wanafunzi Wote</h3>
                        <p><?= $studentData['total_students'] ?></p>
                    </div>

                    <div class="card">
                        <i class="fas fa-user-check"></i>
                        <h3>Wanafunzi Waliohudhuria</h3>
                        <p><?= $presentData['total_present'] ?></p>
                    </div>

                    <div class="card">
                        <i class="fas fa-user-times"></i>
                        <h3>Wanafunzi Waliokosekana</h3>
                        <p><?= $absentData['total_absent'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
