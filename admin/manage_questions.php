<?php
include '../connection.php';
include '../session_check2.php';

include '../connection.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'lecturer' && $_SESSION['role'] != 'admin')) {
    header("Location: ../auth/login.php");
    exit();
}

$errors = [];
$success = '';

// When lecturer submits an answer
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = intval($_POST['question_id'] ?? 0);
    $answer = trim($_POST['answer'] ?? '');

    if ($question_id <= 0 || empty($answer)) {
        $errors[] = "Tafadhali jibu swali zote.";
    } else {
        // Update question with answer and status
        $stmt = $conn->prepare("UPDATE questions SET answer = ?, status = 'answered', answered_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $answer, $question_id);
        if ($stmt->execute()) {
            // Get user's email to send answer
            $stmt2 = $conn->prepare("SELECT users.email, users.full_name, questions.question FROM users JOIN questions ON users.user_id = questions.user_id WHERE questions.id = ?");
            $stmt2->bind_param("i", $question_id);
            $stmt2->execute();
            $result = $stmt2->get_result();
            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();

                // Send answer email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'othmanhamad130@gmail.com';      // Your Gmail
                    $mail->Password = 'bczw ggnp dcmt wpjh';        // Your App password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('othmanhamad130@gmail.com', 'SMART NDOA - Answer');
                    $mail->addAddress($row['email'], $row['full_name']);

                    $mail->isHTML(true);
                    $mail->Subject = "Jibu la swali lako kutoka SMART NDOA";
                    $mail->Body = "
                        <p>Habari {$row['full_name']},</p>
                        <p>Swali ulilouliza: <b>{$row['question']}</b></p>
                        <p>Jibu letu: <b>{$answer}</b></p>
                        <p>Tafadhali jisikie huru kuuliza maswali zaidi!</p>
                        <br><br>
                        <p>SMART NDOA Team</p>
                    ";

                    $mail->send();

                    $success = "Jibu limehifadhiwa na kutumwa kwa mtumiaji kwa barua pepe.";
                } catch (Exception $e) {
                    $errors[] = "Barua pepe haikuweza kutumwa. Hitilafu: {$mail->ErrorInfo}";
                }
            } else {
                $errors[] = "Mtumiaji wa swali haipatikani.";
            }
            $stmt2->close();
        } else {
            $errors[] = "Imeshindikana kuhifadhi jibu.";
        }
        $stmt->close();
    }
}

// Get all pending questions for lecturer/admin
$questions = [];
$qres = $conn->query("SELECT questions.id, users.full_name, questions.question, questions.status, questions.answer FROM questions JOIN users ON questions.user_id = users.user_id WHERE questions.status = 'pending' ORDER BY questions.created_at DESC");
if ($qres) {
    while ($row = $qres->fetch_assoc()) {
        $questions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
<meta charset="UTF-8" />
<title>Jibu Maswali | SMART NDOA</title>
<link rel="stylesheet" href="../style.css" />
</head>
<body>
<h2>Maswali yasiyojibiwa</h2>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <?php foreach ($errors as $e) echo "<div>$e</div>"; ?>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div style="color:green;"><?= $success ?></div>
<?php endif; ?>

<?php if (count($questions) === 0): ?>
    <p>Hakuna swali lolote la kujibu.</p>
<?php else: ?>
    <?php foreach ($questions as $q): ?>
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;">
            <p><b>Mtumiaji:</b> <?= htmlspecialchars($q['full_name']) ?></p>
            <p><b>Swali:</b> <?= htmlspecialchars($q['question']) ?></p>

            <form method="post" action="">
                <input type="hidden" name="question_id" value="<?= $q['id'] ?>">
                <label>Jibu lako:</label><br>
                <textarea name="answer" rows="4" cols="50" required></textarea><br><br>
                <button type="submit">Tuma Jibu</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<p><a href="../admin/admin_dashboard.php">Rudi kwenye Dashibodi</a></p>
</body>
</html>
