<?php
include 'connection.php';
include 'session_check.php';
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input and sanitize
$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$content = mysqli_real_escape_string($conn, $_POST['content']);

// Insert into database
$sql = "INSERT INTO questions (full_name, email, content) VALUES ('$full_name', '$email', '$content')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Swali lako limetumwa kikamilifu!'); window.location.href='ask-question.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
