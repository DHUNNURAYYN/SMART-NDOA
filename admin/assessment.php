<?php
include '../connection.php'; // DB connection
include '../session_check.php'; // Session check (if needed)

// ✅ Auto create table kama haipo
$conn->query("CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255),
    option_b VARCHAR(255),
    option_c VARCHAR(255),
    option_d VARCHAR(255),
    correct_option CHAR(1) NOT NULL
)");

if (isset($_POST['add_question'])) {
    // Chukua data safely (avoid SQL injection, later you can use prepared statements)
    $q = $conn->real_escape_string($_POST['question_text']);
    $a = $conn->real_escape_string($_POST['option_a']);
    $b = $conn->real_escape_string($_POST['option_b']);
    $c = $conn->real_escape_string($_POST['option_c']);
    $d = $conn->real_escape_string($_POST['option_d']);
    $correct = $_POST['correct_option'];

    if (!empty($q) && !empty($a) && !empty($b) && !empty($c) && !empty($d) && !empty($correct)) {
        $insert = "INSERT INTO questions (question_text, option_a, option_b, option_c, option_d, correct_option)
                   VALUES ('$q', '$a', '$b', '$c', '$d', '$correct')";

        if ($conn->query($insert)) {
            echo "<p style='color:green;'>✅ Swali limeongezwa kwa mafanikio!</p>";
        } else {
            echo "<p style='color:red;'>❌ Error: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>⚠️ Tafadhali jaza sehemu zote!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Weka Maswali</title>
</head>
<body>
    <h2>🧑‍💼 Admin Panel - Weka Swali Jipya</h2>
    <form method="post">
        <textarea name="question_text" placeholder="Andika swali hapa..." required></textarea><br>
        <input type="text" name="option_a" placeholder="Jibu A" required><br>
        <input type="text" name="option_b" placeholder="Jibu B" required><br>
        <input type="text" name="option_c" placeholder="Jibu C" required><br>
        <input type="text" name="option_d" placeholder="Jibu D" required><br>

        <label>Jibu Sahihi:</label>
        <select name="correct_option" required>
            <option value="">-- Chagua --</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
        </select><br><br>

        <input type="submit" name="add_question" value="💾 Hifadhi Swali">
    </form>
</body>
</html>
