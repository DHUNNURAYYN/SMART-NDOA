<?php
include '../connection.php'; // Include DB connection
include '../session_check.php';

// Get logged-in lecturer ID
$user_id = $_SESSION['user'];

// Get admin name
$name = "Admin"; // default
$name_query = "SELECT full_name FROM users WHERE user_id = ?";
$name_stmt = $conn->prepare($name_query);
$name_stmt->bind_param("i", $user_id);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
if ($name_row = $name_result->fetch_assoc()) {
    $name = $name_row['full_name'];
}

// Count total students
$studentQuery = $conn->query("SELECT COUNT(*) AS total_students FROM users WHERE role = 'student'");
$studentData = $studentQuery->fetch_assoc();

// Count total lecturers
$lecturerQuery = $conn->query("SELECT COUNT(*) AS total_lecturers FROM users WHERE role = 'lecturer'");
$lecturerData = $lecturerQuery->fetch_assoc();

// Count total admins
$adminQuery = $conn->query("SELECT COUNT(*) AS total_admins FROM users WHERE role = 'admin'");
$adminData = $adminQuery->fetch_assoc();

// Count total applications
$appQuery = $conn->query("SELECT COUNT(*) AS total_apps FROM application_form");
$appData = $appQuery->fetch_assoc();

// Count total questions
$bookQuery = $conn->query("SELECT COUNT(*) AS total_questions FROM questions");
$bookData = $bookQuery->fetch_assoc();

// Count total news posts
$newsQuery = $conn->query("SELECT COUNT(*) AS total_news FROM news");
$newsData = $newsQuery->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashibodi ya Admin</title>
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
                <h1>Karibu: <b><?= htmlspecialchars($name) ?></b></h1> 
            </header>

            <div class="cards-container">
                <div class="cards">
                    <div class="card">
                        <i class="fas fa-user-graduate"></i>
                        <h3>Jumla ya Wanafunzi</h3>
                        <p><?= $studentData['total_students'] ?></p>
                    </div>
                    <div class="card">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <h3>Jumla ya Walimu</h3>
                        <p><?= $lecturerData['total_lecturers'] ?></p>
                    </div>
                    <div class="card">
                        <i class="fas fa-user-shield"></i>
                        <h3>Jumla ya Wasimamizi</h3>
                        <p><?= $adminData['total_admins'] ?></p>
                    </div>
                    <div class="card">
                        <i class="fas fa-file-alt"></i>
                        <h3>Maombi</h3>
                        <p><?= $appData['total_apps'] ?></p>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</body>
</html>
