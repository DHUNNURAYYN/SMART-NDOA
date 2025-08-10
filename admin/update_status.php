<?php
include '../connection.php';
include '../session_check.php';

if (!isset($_GET['form_id']) || !isset($_GET['status'])) {
    echo "⚠️ Ombi batili.";
    exit;
}

$form_id = intval($_GET['form_id']);
$status = $_GET['status'];

// Validate allowed statuses
$allowed_statuses = ['approved'];
if (!in_array($status, $allowed_statuses)) {
    echo "❌ Thamani ya status si halali.";
    exit;
}

// Step 1: Get user_id and full name for the application
$sql = "SELECT u.user_id, u.full_name 
        FROM application_form f 
        JOIN users u ON f.user_id = u.user_id 
        WHERE f.form_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $form_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows < 1) {
    echo "⚠️ Maombi hayajapatikana.";
    exit;
}

$data = $result->fetch_assoc();
$user_id = $data['user_id'];
$full_name = $data['full_name'];

// Step 2: Update application status
$update_sql = "UPDATE application_form SET status = ? WHERE form_id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("si", $status, $form_id);
$update_success = $update_stmt->execute();

// Step 3: Send message if update succeeded
if ($update_success) {
    $message_text = "Habari $full_name, maombi yako yamekubaliwa 🎉.";
    $insert_msg = "INSERT INTO messages (user_id, message, sent_at) VALUES (?, ?, NOW())";
    $msg_stmt = $conn->prepare($insert_msg);
    $msg_stmt->bind_param("is", $user_id, $message_text);
    $msg_stmt->execute();

    // Redirect with success message
    header("Location: manage_applications.php?sms=Maombi+yamekubaliwa+kikamilifu");
    exit;
} else {
    echo "❌ Imeshindikana kusasisha hali ya maombi.";
}
?>
