<?php
include '../session_check.php';
include '../connection.php';

$score = null;

if (isset($_POST['submit_answers'])) {
    $score = 0;
    $total = 0;

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'q') === 0) {
            $question_id = str_replace('q', '', $key);
            $selected = $value;

            $query = $conn->query("SELECT correct_option FROM questions WHERE id = $question_id");
            $correct = $query->fetch_assoc()['correct_option'];

            if ($selected == $correct) {
                $score++;
            }
            $total++;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student - Fanya Mtihani</title>
</head>
<body>
    <h2>👨‍🎓 Karibu Mtihani</h2>

    <?php if ($score !== null): ?>
        <p style="color:blue;">✅ Umefaulu: <?= $score ?> kati ya <?= $total ?></p>
    <?php endif; ?>

    <form method="post">
        <?php
        $result = $conn->query("SELECT * FROM questions");
        if ($result->num_rows == 0) {
            echo "<p style='color:red;'>Hakuna maswali bado!</p>";
        } else {
            while ($row = $result->fetch_assoc()) {
                echo "<p><strong>{$row['question_text']}</strong></p>";
                echo "<input type='radio' name='q{$row['id']}' value='A' required> A. {$row['option_a']}<br>";
                echo "<input type='radio' name='q{$row['id']}' value='B'> B. {$row['option_b']}<br>";
                echo "<input type='radio' name='q{$row['id']}' value='C'> C. {$row['option_c']}<br>";
                echo "<input type='radio' name='q{$row['id']}' value='D'> D. {$row['option_d']}<br><br>";
            }

            echo '<input type="submit" name="submit_answers" value="Tuma Majibu">';
        }
        ?>
    </form>
</body>
</html>
