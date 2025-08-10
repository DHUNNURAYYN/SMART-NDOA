<?php
session_start();
include '../connection.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';

    if (empty($email)) {
        $errors[] = "Tafadhali ingiza barua pepe yako.";
    } else {
        // Check if email exists in users table
        $stmt = $conn->prepare("SELECT user_id, full_name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            $errors[] = "Hakuna akaunti yenye barua pepe hiyo.";
        } else {
            // Generate reset code
            $code = random_int(100000, 999999);
            $code_hash = password_hash((string)$code, PASSWORD_DEFAULT);
            $created_at = date('Y-m-d H:i:s');
            $expires_at = date('Y-m-d H:i:s', time() + 600); // 10 minutes

            // Delete old codes for this email
            $safe_email = $conn->real_escape_string($email);
            $conn->query("DELETE FROM password_resets WHERE email = '{$safe_email}' AND used = 0");

            // Insert new code
            $ins = $conn->prepare("INSERT INTO password_resets (email, code_hash, created_at, expires_at) VALUES (?, ?, ?, ?)");
            $ins->bind_param("ssss", $email, $code_hash, $created_at, $expires_at);
            $ins->execute();

            // Send code via Gmail SMTP
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'othmanhamad130@gmail.com'; // your Gmail
                $mail->Password   = 'bczw ggnp dcmt wpjh';   // your App password
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                $mail->setFrom('othmanhamad130@gmail.com', 'SMART NDOA - Urejeshaji Nenosiri');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Nambari Yako ya Urejeshaji Nenosiri';
                $mail->Body    = "Nambari yako ya kurejesha nenosiri ni: <b>{$code}</b><br>Nambari hii itaisha muda wake ndani ya dakika 10.";

                $mail->send();

                $_SESSION['reset_email'] = $email;
                $_SESSION['msg'] = "Nambari ya urejeshaji imetumwa kwenye barua pepe yako.";
                header("Location: verify_code.php");
                exit();
            } catch (Exception $e) {
                $errors[] = "Barua pepe haikuweza kutumwa. Tatizo la kisafirisha barua: {$mail->ErrorInfo}";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Kusahau Nenosiri | SMART NDOA SYSTEM</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <div class="login-container">
        <div class="user-logo">
            <img src="../Logo/logo.JPG" alt="Nembo ya Mufti">
        </div>

        <h3 style="text-align:center;">Umesahau Nenosiri</h3>

        <!--  Show error messages -->
        <?php if (!empty($errors)): ?>
            <script>
                alert("<?= htmlspecialchars(implode('\n', $errors), ENT_QUOTES) ?>");
            </script>
        <?php endif; ?>

        <!--    Show success messages -->
        <?php if (!empty($_SESSION['msg'])): ?>
            <script>
                alert("<?= htmlspecialchars($_SESSION['msg'], ENT_QUOTES) ?>");
            </script>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <form action="" method="post">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Ingiza barua pepe yako" required>
            </div>

            <button type="submit" name="submit">Tuma Nambari ya Urejeshaji</button>
        </form>

        <p class="register-link"><a href="login.php">Rudi kwenye Kuingia</a></p>
    </div>

</body>
</html>
