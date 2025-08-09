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
        $errors[] = "Please enter your email address.";
    } else {
        // Check if email exists in users table
        $stmt = $conn->prepare("SELECT user_id, full_name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            $errors[] = "No account found with that email.";
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

                $mail->setFrom('othmanhamad130@gmail.com', 'SMART NDOA - Password Reset');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Your Password Reset Code';
                $mail->Body    = "Your reset code is: <b>{$code}</b><br>This code will expire in 10 minutes.";

                $mail->send();

                $_SESSION['reset_email'] = $email;
                $_SESSION['msg'] = "A reset code was sent to your email.";
                header("Location: verify_code.php");
                exit();
            } catch (Exception $e) {
                $errors[] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | SMART NDOA SYSTEM</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <div class="login-container">
        <div class="user-logo">
            <img src="../Logo/logo.JPG" alt="Mufti Logo">
        </div>

        <h3 style="text-align:center;">Forgot Password</h3>

        <!-- ✅ Show error messages -->
        <?php if (!empty($errors)): ?>
            <script>
                alert("<?= htmlspecialchars(implode('\n', $errors), ENT_QUOTES) ?>");
            </script>
        <?php endif; ?>

        <!-- ✅ Show success messages -->
        <?php if (!empty($_SESSION['msg'])): ?>
            <script>
                alert("<?= htmlspecialchars($_SESSION['msg'], ENT_QUOTES) ?>");
            </script>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <form action="" method="post">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <button type="submit" name="submit">Send Reset Code</button>
        </form>

        <p class="register-link"><a href="login.php">Back to Login</a></p>
    </div>

</body>
</html>  