<?php
include '../connection.php';
include '../session_check2.php';

require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Restrict to lecturer/admin
if (!isset($_SESSION['role']) || ($_SESSION['role'] != 'lecturer' && $_SESSION['role'] != 'admin')) {
    header("Location: ../auth/login.php");
    exit();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $question_id = intval($_POST['question_id'] ?? 0);
    $answer = trim($_POST['answer'] ?? '');

    if ($question_id <= 0 || empty($answer)) {
        $errors[] = "Tafadhali toa jibu la swali.";
    } else {
        // Save answer
        $stmt = $conn->prepare("UPDATE questions SET answer = ?, status = 'answered', answered_at = NOW() WHERE id = ?");
        $stmt->bind_param("si", $answer, $question_id);

        if ($stmt->execute()) {
            // Fetch question details
            $stmt2 = $conn->prepare("SELECT email, name, question FROM questions WHERE id = ?");
            $stmt2->bind_param("i", $question_id);
            $stmt2->execute();
            $result = $stmt2->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $recipient_email = $row['email'];
                $recipient_name = $row['name'] ?: 'Mpendwa Mtumiaji';
                $user_question = $row['question'];

                // Send email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'othmanhamad130@gmail.com';
                    $mail->Password = 'bczw ggnp dcmt wpjh';
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('othmanhamad130@gmail.com', 'SMART NDOA - Jibu la Swali');
                    $mail->addAddress($recipient_email, $recipient_name);

                    $mail->isHTML(true);
                    $mail->Subject = "Swali lako limejibiwa";
                    $mail->Body = "
                        <p>Habari {$recipient_name},</p>
                        <p><b>Swali lako:</b> {$user_question}</p>
                        <p><b>Jibu letu:</b> {$answer}</p>
                        <p>Tafadhali jisikie huru kuuliza maswali zaidi wakati wowote.</p>
                        <br>
                        <p>Timuu ya SMART NDOA</p>
                    ";

                    $mail->send();
                    
                    $success = "Jibu limehifadhiwa na kutumwa kwa mtumiaji.";
                } catch (Exception $e) {
                    $errors[] = "Barua pepe haikuweza kutumwa. Hitilafu: {$mail->ErrorInfo}";
                }
            } else {
                $errors[] = "Swali halikuonekana.";
            }
            $stmt2->close();
        } else {
            $errors[] = "Imeshindikana kuhifadhi jibu.";
        }
        $stmt->close();
    }
}

// Fetch pending questions
$questions = [];
$qres = $conn->query("SELECT id, name, email, question, status FROM questions WHERE status = 'pending' ORDER BY created_at DESC");
if ($qres) {
    while ($row = $qres->fetch_assoc()) {
        $questions[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Jibu Maswali</title>
<link rel="stylesheet" href="../dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
.answer-container {
    width: 90%;
    margin: 30px auto;
    padding: 25px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}
.answer-box {
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 20px;
    background: #fafafa;
    border-radius: 8px;
}
.answer-box p {
    margin: 5px 0;
}

textarea {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}
.answer-box button {
    margin-top: 10px;
    padding: 10px 20px;
    background: #007bff;
    border: none;
    color: white;
    border-radius: 5px;
    font-weight: bold;
    cursor: pointer;
}
.answer-box button:hover {
    background: #0056b3;
}
</style>
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
    <?php include '../sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Maswali Yanayosubiri Jibu</h1>
        </header>

        <div class="answer-container">
            <?php if (!empty($errors)): ?>
                <div style="color:red;">
                    <?php foreach ($errors as $e) echo "<div>$e</div>"; ?>
                </div>
            <?php endif; ?>

            <?php if (count($questions) === 0): ?>
                <p>Hakuna maswali ya kujibu.</p>
            <?php else: ?>
                <?php foreach ($questions as $q): ?>
                    <div class="answer-box">
                        <p><b>Mtumiaji:</b> <?= htmlspecialchars($q['name'] ?: 'Mgeni') ?> (<?= htmlspecialchars($q['email']) ?>)</p>
                        <p><b>Swali:</b> <?= htmlspecialchars($q['question']) ?></p>
                        <form method="post">
                            <input type="hidden" name="question_id" value="<?= $q['id'] ?>">
                            <label>Jibu Lako:</label>
                            <textarea name="answer" rows="4" required></textarea>
                            <button type="submit">Tuma Jibu</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (!empty($success)): ?>
<script>
    alert("<?= addslashes($success) ?>");
</script>
<?php endif; ?>

</body>
</html>
