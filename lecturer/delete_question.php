<?php
include '../connection.php'; // DB connection
include '../session_check.php'; // Session check

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $question_id = (int)$_GET['id']; // explicit cast to int

    $stmt = $conn->prepare("DELETE FROM questions WHERE question_id = ?");
    $stmt->bind_param("i", $question_id);

    if ($stmt->execute()) {
        header("Location: manage_questions.php?deleted=1");
        exit();
    } else {
        echo "❌ Hitilafu wakati wa kufuta swali.";
    }

    $stmt->close();
} else {
    echo "⚠️ Ombi batili.";
}

$conn->close();
?>
