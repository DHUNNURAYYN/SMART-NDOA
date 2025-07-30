<?php
include '../connection.php';

if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {

    // Count applications
    $app_count = 0;
    $app_query = $conn->query("SELECT COUNT(*) AS total FROM application_form WHERE status = 'pending'");
    if ($app_query) {
        $app_row = $app_query->fetch_assoc();
        $app_count = $app_row['total'];
    }

    // Count questions
    $q_count = 0;
    $q_query = $conn->query("SELECT COUNT(*) AS total FROM questions");
    if ($q_query) {
        $q_row = $q_query->fetch_assoc();
        $q_count = $q_row['total'];
    }
?>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../logo/logo.JPG" alt="logo" class="sidebar-logo-img">
            <h2>Admin Panel</h2>
        </div>

        <ul class="sidebar-menu">
            <li><a href="/admin/admin_dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashboard </a></li>

            <li><a href="/admin/manage_users.php"><i class="fas fa-users"></i> Manage Users</a></li>
            
            <li>
                <a href="/admin/manage_applications.php">
                    <i class="fas fa-file-alt"></i> Manage Applications
                    <span style="background: red; color: white; font-size: 12px; padding: 2px 8px; border-radius: 12px; margin-left: 6px;">
                        <?= $app_count ?>
                    </span>
                </a>
            </li>
            
            <li>
                <a href="/Lecturer/manage_questions.php">
                    <i class="fas fa-question-circle"></i> Manage Questions
                    <span style="background: red; color: white; font-size: 12px; padding: 2px 8px; border-radius: 12px; margin-left: 6px;">
                        <?= $q_count ?>
                    </span>
                </a>
            </li>

            <li><a href="/admin/post_news.php"><i class="fas fa-newspaper"></i> Post News</a></li>
            <li><a href="/admin/view_news.php"><i class="fas fa-check-square"></i> View Posted News</a></li>
            <li><a href="/admin/view_attendance_records.php"><i class="fas fa-check-square"></i> View Attendance Record</a></li>
            <li><a href="/admin/manage_certificates.php"><i class="fas fa-certificate"></i> Manage Certificates</a></li>
        </ul>

        <div class="logout-section">
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

<?php
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'lecturer') {

        // Count unanswered questions only if questions exist
        $question_count = 0;
        $check_q = $conn->query("SELECT COUNT(*) AS total FROM questions");

        if ($check_q) {
            $row_check = $check_q->fetch_assoc();
            if ($row_check['total'] > 0) {
                // Now count only those not yet answered
                $q = $conn->query("SELECT COUNT(*) AS total FROM questions WHERE status != 'answered'");
                if ($q) {
                    $r = $q->fetch_assoc();
                    $question_count = $r['total'];
                }
            }
        }

?>
<div class="sidebar">
    <div class="sidebar-header">
        <img src="../logo/logo.JPG" alt="logo" class="sidebar-logo-img">
        <h2>Lecturer Panel</h2>
    </div>

    <ul class="sidebar-menu">
        <li><a href="lecturer_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard </a></li>
        <li><a href="answer_questions.php"><i class="fas fa-check-square"></i> Answer Questions</a></li>
        <li><a href="manage_books.php"><i class="fas fa-book"></i> Manage Books</a></li>

          <li>
        <a href="manage_questions.php">
            <i class="fas fa-question-circle"></i> Manage Questions
            <?php if ($question_count > 0): ?>
                <span style="background: red; color: white; font-size: 12px; padding: 2px 8px; border-radius: 12px; margin-left: 6px;">
                    <?= $question_count ?>
                </span>
            <?php endif; ?>
        </a>
</li>

        </li>

        <li><a href="view_news.php"><i class="fas fa-check-square"></i> View Posted News</a></li>
        <li><a href="manage_attendances.php"><i class="fas fa-check-square"></i> Mark Attendance</a></li>
        <li><a href="../admin/view_attendance_records.php"><i class="fas fa-check-square"></i> View Attendance Record</a></li>
    </ul>

    <div class="logout-section">
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>
<?php 
} elseif (isset($_SESSION['role']) && $_SESSION['role'] == 'student') {

    // Get current student's ID
    $user_id = $_SESSION['user'];

    // Count unviewed answers
    $notif_sql = "SELECT COUNT(*) AS new_answers FROM questions WHERE user_id = ? AND viewed = 'No'";
    $stmt = $conn->prepare($notif_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($new_answers);
    $stmt->fetch();
    $stmt->close();
?>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../Logo/logo.JPG" alt="Logo" class="sidebar-logo-img">
            <h2>Student Panel</h2>
        </div>

        <ul class="sidebar-menu">
            <li><a href="student_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="student_profile.php"><i class="fas fa-user"></i> My Profile</a></li>
            <li><a href="ask_question.php"><i class="fas fa-check-square"></i> Ask Question</a></li>
            <li>
                <a href="view_answer.php">
                    <i class="fas fa-check-square"></i> Answered Question
                    <?php if ($new_answers > 0): ?>
        <span style="display: inline-block; width: 10px; height: 10px; background-color: green; border-radius: 50%; margin-left: 8px;"></span>
    <?php endif; ?>

                </a>
            </li>
            <li><a href="view_notes.php"><i class="fas fa-book-open"></i> My Classes</a></li>
            <li><a href="attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
        </ul>

        <div class="logout-section">
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
            <!-- <li><a href="applicant_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li> -->
            <li><a href="form.php"><i class="fas fa-tachometer-alt"></i> Form</a></li>
            <!-- <li><a href="status.php"><i class="fas fa-user"></i> Status</a></li> -->
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
<?php } ?>
