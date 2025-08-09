<?php
include '../connection.php'; // DB connection
include '../session_check.php';

// Get logged-in lecturer ID
$user_id = $_SESSION['user'];

// Get lecturer's name
$name = "Lecturer"; // default
$name_query = "SELECT full_name FROM users WHERE user_id = ?";
$name_stmt = $conn->prepare($name_query);
$name_stmt->bind_param("i", $user_id);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
if ($name_row = $name_result->fetch_assoc()) {
    $name = $name_row['full_name'];
}

// Count total students only
$studentQuery = $conn->query("SELECT COUNT(*) AS total_students FROM users WHERE role = 'student'");
$studentData = $studentQuery->fetch_assoc();



// Count present students only
$presentQuery = $conn->query("
    SELECT COUNT(*) AS total_present 
    FROM attendances 
    INNER JOIN users ON attendances.user_id = users.user_id 
    WHERE attendances.status = 'Present' AND users.role = 'student'
");
$presentData = $presentQuery->fetch_assoc();

// Count absent students only
$absentQuery = $conn->query("
    SELECT COUNT(*) AS total_absent 
    FROM attendances 
    INNER JOIN users ON attendances.user_id = users.user_id 
    WHERE attendances.status = 'Absent' AND users.role = 'student'
");
$absentData = $absentQuery->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lecturer Dashboard</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include '../sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Welcome: <b><?= htmlspecialchars($name) ?></b></h1> 
            </header>

            <div class="cards-container">
                <div class="cards">
                    <div class="card">
                        <i class="fas fa-user-graduate"></i>
                        <h3>Total Students</h3>
                        <p><?= $studentData['total_students'] ?></p>
                    </div>

                    

                    <div class="card">
                        <i class="fas fa-user-check"></i>
                        <h3>Present Students</h3>
                        <p><?= $presentData['total_present'] ?></p>
                    </div>

                    <div class="card">
                        <i class="fas fa-user-times"></i>
                        <h3>Absent Students</h3>
                        <p><?= $absentData['total_absent'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
