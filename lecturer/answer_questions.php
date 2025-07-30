<?php
include '../connection.php';
include '../session_check.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['question_id'])) {
    $answer = $_POST['answer'];
    $question_id = $_POST['question_id'];

    $sql = "UPDATE questions SET answer = ?, status = 'Answered' WHERE question_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $answer, $question_id);
    $stmt->execute();

    // Set success flag
    $_SESSION['answered_success'] = true;

    // Redirect to avoid resubmission
    header("Location: manage_questions.php");
    exit();
}

// ✅ Fetch pending questions
$result = $conn->query("SELECT * FROM questions WHERE status = 'Pending' ORDER BY date_asked DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Maswali Yanayohitaji Majibu</title>
    <style>
        button[type="submit"] {
            padding: 6px 12px;
            background-color: green;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        textarea {
            width: 100%;
            height: 80px;
            margin-bottom: 10px;
        }
       .question-box {
    border: 1px solid #ccc;
    padding: 15px;
    margin: 15px 0;
    border-radius: 10px; /* 🎯 Smooth corners */
    background-color: #f9f9f9;
    box-shadow: 2px 2px 6px rgba(0,0,0,0.1); /* ☁️ Soft shadow */
}

    </style>
</head>
<body>

<?php
// ✅ Show success alert only once
if (isset($_SESSION['answered_success']) && $_SESSION['answered_success'] === true) {
    echo "<script>alert('✅ Jibu limetumwa kwa mafanikio!');</script>";
    unset($_SESSION['answered_success']);
}
?>

<h2>Maswali Yanayohitaji Majibu</h2>

<?php 
$sn = 1;
while ($row = $result->fetch_assoc()): 
?>
    <div class="question-box">
        <strong><?= $sn++ ?>. <?= $row['full_name'] ?> (<?= $row['email'] ?>)</strong><br>
        <em>Swali:</em> <?= $row['content'] ?><br><br>

        <form method="post">
            <textarea name="answer" placeholder="Andika jibu hapa..." required></textarea><br>
            <input type="hidden" name="question_id" value="<?= $row['question_id'] ?>">
            <button type="submit">Tuma Jibu</button>
        </form>
    </div>
<?php endwhile; ?>

<?php if ($result->num_rows === 0): ?>
    <p>✅ Hakuna maswali yanayosubiri majibu kwa sasa.</p>
<?php endif; ?>

</div>
</div>
</body>
</html>

