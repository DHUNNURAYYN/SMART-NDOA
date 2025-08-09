<?php
include '../session_check.php';
include '../connection.php';

$user_id = $_SESSION['user'];

// Check if the user has applied
$has_applied = false;

$sql = "SELECT 1 FROM application_form WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $has_applied = true;
}
$stmt->close();

// Get user full name
$name = "applicant";
$name_sql = "SELECT full_name FROM users WHERE user_id = ?";
$name_stmt = $conn->prepare($name_sql);
$name_stmt->bind_param("i", $user_id);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
if ($row = $name_result->fetch_assoc()) {
    $name = $row['full_name'];
}
$name_stmt->close();
?>


        <?php
        if ($has_applied) {
            include("application_success.php");
        } else {
            include("application_form.php");
        }
        ?>
    </div>
</div>
