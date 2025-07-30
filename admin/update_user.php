<?php
include '../connection.php';
include '../session_check.php';

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Retrieve submitted data safely
    $user_id   = $_POST['user_id'];
    $full_name = $_POST['full_name'];
    $email     = $_POST['email'];
    $role      = $_POST['role'];

    // Validate input
    if (empty($user_id) || empty($full_name) || empty($email) || empty($role)) {
        echo "⚠️ All fields are required.";
        exit;
    }

    // Prepare update query
    $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, role = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $full_name, $email, $role, $user_id);

    if ($stmt->execute()) {
        // Redirect to manage users page or confirmation page
        header("Location: manage_users.php?success=1");
        exit;
    } else {
        echo "❌ Failed to update user: " . $stmt->error;
    }

} else {
    echo "Invalid request.";
    exit;
}
?>
