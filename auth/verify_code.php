<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = isset($_POST['code']) ? trim($_POST['code']) : '';
    if (empty($code)) {
        $errors[] = "Please enter the code sent to your email.";
    } else {
        // Get the latest unused reset record for this email
        $stmt = $conn->prepare("SELECT id, code_hash, expires_at FROM password_resets WHERE email = ? AND used = 0 ORDER BY created_at DESC LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            $errors[] = "No reset request found. Please request a new code.";
        } else {
            $row = $res->fetch_assoc();
            // Check if expired
            if (strtotime($row['expires_at']) < time()) {
                $errors[] = "The code has expired. Please request a new code.";
            } else {
                // Verify code
                if (password_verify((string)$code, $row['code_hash'])) {
                    // Mark code as used
                    $upd = $conn->prepare("UPDATE password_resets SET used = 1 WHERE id = ?");
                    $upd->bind_param("i", $row['id']);
                    $upd->execute();

                    $_SESSION['reset_confirmed'] = true;
                    header("Location: reset_password.php");
                    exit();
                } else {
                    $errors[] = "Invalid code. Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Verify Reset Code | SMART NDOA SYSTEM</title>
    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <div class="login-container">
        <div class="user-logo">
            <img src="../Logo/logo.JPG" alt="Mufti Logo" />
        </div>

        <h3 style="text-align:center;">Verify Reset Code</h3>

        <?php if (!empty($errors)): ?>
            <script>
                alert("<?= htmlspecialchars(implode('\n', $errors), ENT_QUOTES) ?>");
            </script>
        <?php endif; ?>

        <form method="post" action="">
            <div class="input-group">
                <input
                    type="text"
                    name="code"
                    maxlength="6"
                    placeholder="Enter 6-digit code"
                    required
                    pattern="\d{6}"
                    title="Please enter exactly 6 digits"
                />
            </div>

            <button type="submit">Verify Code</button>
        </form>

        <p class="register-link">
            Didn't get a code? <a href="forgot_password.php">Request again</a>
        </p>
    </div>
</body>

</html>
