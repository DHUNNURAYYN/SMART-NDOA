<?php
include '../connection.php'; // Your connection file
include '../session_check.php';

if (!isset($_GET['id'])) {
    echo "User ID not provided.";
    exit;
}

$user_id = $_GET['id'];

$query = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: manage_users.php");
    exit;
} else {
    echo "Failed to delete user.";
}
?>
