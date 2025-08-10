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
        $errors[] = "Tafadhali ingiza nambari iliyotumwa kwenye barua pepe yako.";
    } else {
        // Get the latest unused reset record for this email
        $stmt = $conn->prepare("SELECT id, code_hash, expires_at FROM password_resets WHERE email = ? AND used = 0 ORDER BY created_at DESC LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
            $errors[] = "Hakuna ombi la kurekebisha nenosiri lililopatikana. Tafadhali omba nambari mpya.";
        } else {
            $row = $res->fetch_assoc();
            // Check if expired
            if (strtotime($row['expires_at']) < time()) {
                $errors[] = "Nambari imekwisha muda wake. Tafadhali omba nambari mpya.";
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
                    $errors[] = "Nambari si sahihi. Tafadhali jaribu tena.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sw">

<head>
    <meta charset="UTF-8" />
    <title>Thibitisha Nambari ya Urejeshaji | SMART NDOA SYSTEM</title>
    <link rel="stylesheet" href="../style.css" />
</head>

<body>
    <div class="login-container">
        <div class="user-logo">
            <img src="../Logo/logo.JPG" alt="Nembo ya Mufti" />
        </div>

        <h3 style="text-align:center;">Thibitisha Nambari ya Urejeshaji</h3>

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
                    placeholder="Ingiza nambari ya tarakimu 6"
                    required
                    pattern="\d{6}"
                    title="Tafadhali ingiza tarakimu 6 pekee"
                />
            </div>

            <button type="submit">Thibitisha Nambari</button>
        </form>

        <p class="register-link">
            Hukupokea nambari? <a href="forgot_password.php">Omba tena</a>
        </p>
    </div>
</body>

</html>
