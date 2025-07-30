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

// Count total users
$userQuery = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$userData = $userQuery->fetch_assoc();

// Count total applications
$appQuery = $conn->query("SELECT COUNT(*) AS total_apps FROM application_form");
$appData = $appQuery->fetch_assoc();

// Count total books
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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
   <?php
    include '../sidebar.php';

   ?>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                  <h1>Welcome: <b><?= htmlspecialchars($name) ?></b></h1> 
            </header>
            <div class="cards-container">
                <div class="cards">
                    <div class="card">
                        <i class="fas fa-users"></i>
                        <h3>Total Users</h3>
                        <p><?= $userData['total_users'] ?></p>
                    </div>
                    <div class="card">
                        <i class="fas fa-file-alt"></i>
                        <h3>Applications</h3>
                        <p><?= $appData['total_apps'] ?></p>
                    </div>
                    <div class="card">
                        <i class="fas fa-book"></i>
                        <h3>Questions</h3>
                        <p><?= $bookData['total_questions'] ?></p>
                    </div>
                    <div class="card">
                        <i class="fas fa-newspaper"></i>
                        <h3>News Posts</h3>
                        <p><?= $newsData['total_news'] ?></p>
                    </div>
                    
                </div>
            </div>
          
        </div>
    </div>
</body>
</html>
