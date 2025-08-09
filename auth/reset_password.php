<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['reset_confirmed'], $_SESSION['reset_email']) || $_SESSION['reset_confirmed'] !== true) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

    // Validations
    if (empty($new_password) || empty($confirm_password)) {
        $errors[] = "Both password fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    } else {
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $password_hash, $email);

        if ($stmt->execute()) {
            // Delete any remaining reset tokens
            $safe_email = $conn->real_escape_string($email);
            $conn->query("DELETE FROM password_resets WHERE email = '{$safe_email}'");

            // Clear session vars
            unset($_SESSION['reset_confirmed']);
            unset($_SESSION['reset_email']);

            $success = "Password updated successfully.";
        } else {
            $errors[] = "Failed to update password. Try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Set New Password | SMART NDOA SYSTEM</title>
    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <div class="login-container">
        <div class="user-logo">
            <img src="../Logo/logo.JPG" alt="Mufti Logo" />
        </div>

        <h3 style="text-align:center;">Set New Password</h3>

        <?php if (!empty($errors)): ?>
            <script>
                alert("<?= htmlspecialchars(implode('\n', $errors), ENT_QUOTES) ?>");
            </script>
        <?php endif; ?>

      <?php if ($success): ?>
    <script>
        alert("<?= addslashes($success) ?>");
        window.location.href = 'login.php';
    </script>
<?php else: ?>
    <form method="post" action="">
        <div class="input-group">
            <input type="password" name="new_password" placeholder="New Password" required minlength="6" />
        </div>

        <div class="input-group">
            <input type="password" name="confirm_password" placeholder="Confirm New Password" required minlength="6" />
        </div>

        <button type="submit">Set Password</button>
    </form>
<?php endif; ?>


    </div>
</body>

</html>
