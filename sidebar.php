<?php
include '../connection.php';
 // Ensure session is started

if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {

    // Count applications (ensure 'status' column exists in application_form)
    $app_count = 0;
    if ($conn->query("SHOW COLUMNS FROM application_form LIKE 'status'")->num_rows > 0) {
        $app_query = $conn->query("SELECT COUNT(*) AS total FROM application_form WHERE status = 'pending'");
        if ($app_query) {
            $app_row = $app_query->fetch_assoc();
            $app_count = $app_row['total'];
        }
    }

    // Count unanswered questions (ensure 'status' exists in questions)
    $question_count = 0;
    if ($conn->query("SHOW COLUMNS FROM questions LIKE 'status'")->num_rows > 0) {
        $q = $conn->query("SELECT COUNT(*) AS total FROM questions WHERE status != 'answered'");
        if ($q) {
            $r = $q->fetch_assoc();
            $question_count = $r['total'];
        }
    }

    ?>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../logo/logo.JPG" alt="logo" class="sidebar-logo-img">
            <h2>Admin Panel</h2>
        </div>

        <ul class="sidebar-menu">
            <li><a href="/admin/admin_dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li><a href="/admin/manage_users.php"><i class="fas fa-users"></i>Manage Users</a></li>
              <li><a href="/admin/assessment.php"><i class="fas fa-tachometer-alt"></i>Manage Assessment</a></li>


            <li>
                <a href="/admin/manage_applications.php">
                    <i class="fas fa-file-alt"></i>Manage Applications
                    <?php if ($app_count > 0): ?>
                        <span style="background: red; color: white; font-size: 12px; padding: 2px 8px; border-radius: 12px; margin-left: 6px;">
                            <?= $app_count ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="/admin/answer_questions.php"><i class="fas fa-tasks"></i>Manage Questions</a></li>
           <li class="dropdown">
                         <li>
                        <a href="#"><i class="fas fa-newspaper"></i> Manage News <i class="fas fa-angle-right submenu-arrow"></i></a>
                        <ul class="submenu">
                            <li><a href="/admin/post_news.php"><i class="fas fa-plus"></i> Post News</a></li>
                            <li><a href="/admin/view_news.php"><i class="fas fa-check-square"></i> View Posted News</a></li>
                        </ul>
                        </li>
            <li><a href="/admin/view_attendance_records.php"><i class="fas fa-check-square"></i>View Attendance Record</a></li>
            <li><a href="/admin/manage_certificates.php"><i class="fas fa-certificate"></i>Manage Certificates</a></li>
            <li><a href="/admin/set_training_date.php"><i class="fas fa-users"></i>Manage Trainees</a></li>
        </ul>

        <div class="logout-section">
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </div>
<?php
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'lecturer') {

    $question_count = 0;
    if ($conn->query("SHOW COLUMNS FROM questions LIKE 'status'")->num_rows > 0) {
        $q = $conn->query("SELECT COUNT(*) AS total FROM questions WHERE status != 'answered'");
        if ($q) {
            $r = $q->fetch_assoc();
            $question_count = $r['total'];
        }
    }
?>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../logo/logo.JPG" alt="logo" class="sidebar-logo-img">
            <h2>Lecturer Panel</h2>
        </div>

        <ul class="sidebar-menu">
            <li><a href="/lecturer/lecturer_dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>

            <li><a href="/lecturer/manage_books.php"><i class="fas fa-book"></i>Manage Books</a></li>
            <li class="dropdown">
                         <li>
                        <a href="#"><i class="fas fa-newspaper"></i> Manage Attendances <i class="fas fa-angle-right submenu-arrow"></i></a>
                        <ul class="submenu">
                            
                        <li><a href="/lecturer/manage_attendances.php"><i class="fas fa-check-square"></i>Mark Attendance</a></li>
                         <li><a href="../admin/view_attendance_records.php"><i class="fas fa-check-square"></i>View Attendance Record</a></li>
                        </ul>
                        </li>
        </ul>

        <div class="logout-section">
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </div>
<?php
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'student') {

    $user_id = $_SESSION['user'];
    $new_answers = 0;

    if ($conn->query("SHOW COLUMNS FROM questions LIKE 'viewed'")->num_rows > 0) {
        $notif_sql = "SELECT COUNT(*) AS new_answers FROM questions WHERE user_id = ? AND viewed = 'No'";
        $stmt = $conn->prepare($notif_sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($new_answers);
        $stmt->fetch();
        $stmt->close();
    }
?>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../Logo/logo.JPG" alt="Logo" class="sidebar-logo-img">
            <h2>Student Panel</h2>
        </div>

        <ul class="sidebar-menu">
            <li><a href="student_dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
            <li><a href="student_profile.php"><i class="fas fa-user"></i>My Profile</a></li>
            <li><a href="view_notes.php"><i class="fas fa-book-open"></i>My Classes</a></li>
            <li><a href="attendance.php"><i class="fas fa-calendar-check"></i>Certificate</a></li>
        </ul>

        <div class="logout-section">
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
        </div>
    </div>
<?php
} else { ?>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../Logo/logo.JPG" alt="Logo" class="sidebar-logo-img">
            <h2>Applicant Panel</h2>
        </div>

        <ul class="sidebar-menu">
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
        </ul>
    </div>
<?php } ?>
