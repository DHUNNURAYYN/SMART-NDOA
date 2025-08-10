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
            <h2>Paneli ya Admin</h2>
        </div>

        <ul class="sidebar-menu">
            <li><a href="/admin/admin_dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashibodi</a></li>
            <li><a href="/admin/manage_users.php"><i class="fas fa-users"></i>Dhibiti Watumiaji</a></li>


            <li>
                <a href="/admin/manage_applications.php">
                    <i class="fas fa-file-alt"></i>Dhibiti Maombi
                    <?php if ($app_count > 0): ?>
                        <span style="background: red; color: white; font-size: 12px; padding: 2px 8px; border-radius: 12px; margin-left: 6px;">
                            <?= $app_count ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>
            <li><a href="/admin/answer_questions.php"><i class="fas fa-tasks"></i>Dhibiti Maswali</a></li>
            <li class="dropdown">
                <li>
                    <a href="#"><i class="fas fa-newspaper"></i> Dhibiti Habari <i class="fas fa-angle-right submenu-arrow"></i></a>
                    <ul class="submenu">
                        <li><a href="/admin/post_news.php"><i class="fas fa-plus"></i> Weka Habari</a></li>
                        <li><a href="/admin/view_news.php"><i class="fas fa-check-square"></i> Tazama Habari Zilizowekwa</a></li>
                    </ul>
                </li>
            </li>
            <li><a href="/admin/view_attendance_records.php"><i class="fas fa-check-square"></i> Rekodi za Mahudhurio</a></li>
            <li><a href="/admin/manage_assessment.php"><i class="fas fa-tachometer-alt"></i>Dhibiti Tathmini</a></li>
            <li><a href="/admin/manage_certificates.php"><i class="fas fa-certificate"></i>Dhibiti Vyeti</a></li>
            <li><a href="/admin/set_training_date.php"><i class="fas fa-users"></i>Dhibiti Fomu</a></li>
            <li><a href="/admin/generate_report.php"><i class="fas fa-users"></i>Tengeneza report </a></li>

        </ul>

        <div class="logout-section">
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Toka</a>
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
            <h2>Paneli ya Mwalimu</h2>
        </div>

        <ul class="sidebar-menu">
            <li><a href="/lecturer/lecturer_dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashibodi</a></li>
            <li><a href="/lecturer/manage_books.php"><i class="fas fa-book"></i>Dhibiti Vitabu</a></li>
            <li class="dropdown">
                <li>
                    <a href="#"><i class="fas fa-newspaper"></i> Dhibiti Mahudhurio <i class="fas fa-angle-right submenu-arrow"></i></a>
                    <ul class="submenu">
                        <li><a href="/lecturer/manage_attendances.php"><i class="fas fa-check-square"></i>Weka Mahudhurio</a></li>
                        <li><a href="../admin/view_attendance_records.php"><i class="fas fa-check-square"></i>Tazama Rekodi za Mahudhurio</a></li>
                    </ul>
                </li>
            </li>
        </ul>

        <div class="logout-section">
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Toka</a>
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
            <h2>Paneli ya Mwanafunzi</h2>
        </div>

        <ul class="sidebar-menu">
            <li><a href="student_dashboard.php"><i class="fas fa-tachometer-alt"></i>Dashibodi</a></li>
            <li><a href="student_profile.php"><i class="fas fa-user"></i>Wasifu Wangu</a></li>
            <li><a href="view_notes.php"><i class="fas fa-book-open"></i>Madarasa Yangu</a></li>
            <li><a href="attendance.php"><i class="fas fa-calendar-check"></i>Cheti</a></li>
        </ul>

        <div class="logout-section">
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Toka</a>
        </div>
    </div>
<?php
} else { ?>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="../Logo/logo.JPG" alt="Logo" class="sidebar-logo-img">
            <h2>Paneli ya Mwombaji</h2>
        </div>

        <ul class="sidebar-menu">
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i>Toka</a></li>
        </ul>
    </div>
<?php } ?>
