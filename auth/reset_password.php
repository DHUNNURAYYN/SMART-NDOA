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
        $errors[] = "Sehemu zote za nenosiri zinahitajika.";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "Manenosiri hayalingani.";
    } elseif (strlen($new_password) < 6) {
        $errors[] = "Nenosiri lazima liwe na angalau herufi 6.";
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

            $success = "Nenosiri limeboreshwa kwa mafanikio.";
        } else {
            $errors[] = "Imeshindikana kuboresha nenosiri. Tafadhali jaribu tena baadaye.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sw">

<head>
    <meta charset="UTF-8" />
    <title>Weka Nenosiri Jipya | SMART NDOA SYSTEM</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <div class="login-container">
        <div class="user-logo">
            <img src="../Logo/logo.JPG" alt="Nembo ya Mufti" />
        </div>

        <h3 style="text-align:center;">Weka Nenosiri Jipya</h3>

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
            <input type="password" name="new_password" placeholder="Nenosiri Jipya" required minlength="6" />
        </div>

        <div class="input-group">
            <input type="password" name="confirm_password" placeholder="Thibitisha Nenosiri Jipya" required minlength="6" />
        </div>

        <button type="submit">Weka Nenosiri</button>
    </form>
<?php endif; ?>


    </div>
</body>

</html>
