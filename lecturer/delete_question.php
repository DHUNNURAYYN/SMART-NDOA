<?php
include '../connection.php'; // DB connection
include '../session_check.php'; // Session check

// Check if ID is provided in the query string
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $question_id = $_GET['id'];

    // Prepare delete statement
    $stmt = $conn->prepare("DELETE FROM questions WHERE question_id = ?");
    $stmt->bind_param("i", $question_id);

    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: manage_questions.php?deleted=1");
        exit();
    } else {
        echo "Error deleting question.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
