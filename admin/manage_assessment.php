<?php
include '../connection.php'; // DB connection
include '../session_check.php'; // Session check (if needed)

//  Create table if it doesn't exist (optional safeguard)
$conn->query("CREATE TABLE IF NOT EXISTS assessment (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_option CHAR(1) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

if (isset($_POST['add_question'])) {
    // Sanitize inputs
    $q = $conn->real_escape_string($_POST['question_text']);
    $a = $conn->real_escape_string($_POST['option_a']);
    $b = $conn->real_escape_string($_POST['option_b']);
    $c = $conn->real_escape_string($_POST['option_c']);
    $d = $conn->real_escape_string($_POST['option_d']);
    $correct = $conn->real_escape_string($_POST['correct_option']);
}

 if (!empty($q) && !empty($a) && !empty($b) && !empty($c) && !empty($d) && !empty($correct)) {
    $insert = "INSERT INTO assessment (question, option_a, option_b, option_c, option_d, correct_option)
               VALUES ('$q', '$a', '$b', '$c', '$d', '$correct')";

    if ($conn->query($insert)) {
        echo "<script>alert(' Swali limeongezwa kwa mafanikio!');</script>";
    } else {
        $error = addslashes($conn->error);
        echo "<script>alert(' Error: $error');</script>";
    }
} else {
    echo "<script>alert(' Tafadhali jaza sehemu zote!');</script>";
}
?>


<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Admin - Weka Maswali</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
       .add-question {
           background: white;
           padding: 25px;
           border-radius: 10px;
           box-shadow: 0 2px 5px rgba(0,0,0,0.1);
           width: 60%;
           margin: 40px auto;
        
       }
       .add-question h2 {
           text-align: center;
           color: #2c3e50;
           margin-bottom: 25px;
       }
       label {
           font-weight: bold;
           display: block;
           margin: 15px 0 7px;
       }
       textarea, input[type="text"], select {
           width: 100%;
           padding: 10px;
           border: 1px solid #ccc;
           border-radius: 5px;
           box-sizing: border-box;
           font-size: 15px;
           resize: vertical;
       }
       select {
           cursor: pointer;
       }
       input[type="submit"] {
           margin-top: 25px;
           padding: 12px 0;
           background: #2980b9;
           color: white;
           border: none;
           border-radius: 5px;
           cursor: pointer;
           font-size: 17px;
           font-weight: bold;
           width: 100%;
           display: flex;
           align-items: center;
           justify-content: center;
           gap: 10px;
       }
       input[type="submit"]:hover {
           background: rgb(5, 80, 131);
       }
    </style>
</head>
<body>
<div class="dashboard-container">
    <?php include '../sidebar.php'; ?>

    <div class="main-content">
        <header>
            <h1>Andaa Maswali</h1>
        </header>

        <div class="add-question">
            <form method="post" action="add_question.php">
                <label for="question_text">Andika Swali Hapa:</label>
                <textarea id="question_text" name="question_text" rows="4" placeholder="Andika swali hapa..." required></textarea>

                <label for="option_a">Jibu A:</label>
                <input type="text" id="option_a" name="option_a" placeholder="Jibu A" required>

                <label for="option_b">Jibu B:</label>
                <input type="text" id="option_b" name="option_b" placeholder="Jibu B" required>

                <label for="option_c">Jibu C:</label>
                <input type="text" id="option_c" name="option_c" placeholder="Jibu C" required>

                <label for="option_d">Jibu D:</label>
                <input type="text" id="option_d" name="option_d" placeholder="Jibu D" required>

                <label for="correct_option">Jibu Sahihi:</label>
                <select id="correct_option" name="correct_option" required>
                    <option value="">-- Chagua --</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>

                <input type="submit" name="add_question" value=" Hifadhi ">
            </form>
        </div>
    </div>
</div>

<script>
function toggleSubmenu(e) {
    e.preventDefault();
    const parentLi = e.currentTarget.parentElement;
    parentLi.classList.toggle('open');
}
</script>
</body>
</html>
