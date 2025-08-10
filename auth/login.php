<?php
include("../connection.php");
session_start();

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT user_id, full_name, role, password FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../admin/admin_dashboard.php");
            } elseif ($user['role'] === 'student') {
                header("Location: ../student/student_dashboard.php");
            } elseif ($user['role'] === 'lecturer') {
                header("Location: ../lecturer/lecturer_dashboard.php");
            } else {
                header("Location: ../applicant/applicant_dashboard.php");
            }
            exit();
        } else {
            $message = "Namba ya simu au nenosiri si sahihi.";
        }
    } else {
        $message = "Hakuna akaunti iliyosajiliwa kwa namba hiyo ya simu.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="sw">

<head>
    <meta charset="UTF-8">
    <title>Kuingia | MFUMO MAHUSUSI WA NDOA</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        function validateForm() {
            var phone = document.forms["loginForm"]["phone"].value;
            var password = document.forms["loginForm"]["password"].value;

            if (phone.trim() === "") {
                alert("Tafadhali weka namba ya simu.");
                return false;
            }

            var phonePattern = /^[0-9]{7,15}$/;
            if (!phonePattern.test(phone)) {
                alert("Weka namba halali ya simu (tarakimu 7–15).");
                return false;
            }

            if (password === "") {
                alert("Tafadhali weka nenosiri.");
                return false;
            }

            if (password.length < 6) {
                alert("Nenosiri lazima liwe angalau herufi 6.");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>

    <!-- ✅ JavaScript alert kwa mafanikio ya usajili -->
    <?php if (isset($_GET['sms'])): ?>
        <script>
            alert("<?= htmlspecialchars($_GET['sms'], ENT_QUOTES) ?>");
        </script>
    <?php endif; ?>

    <!-- ✅ JavaScript alert kwa makosa ya kuingia -->
    <?php if (!empty($message)): ?>
        <script>
            alert("<?= htmlspecialchars($message, ENT_QUOTES) ?>");
        </script>
    <?php endif; ?>

    <div class="login-container">
        <div class="user-logo">
            <img src="../Logo/logo.JPG" alt="Nembo ya Mufti">
        </div>

        <form name="loginForm" action="login.php" method="post" onsubmit="return validateForm()">
            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="phone" placeholder="Namba ya Simu" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Nenosiri" required>
            </div>

            <div class="options">
                <label><input type="checkbox" name="remember"> Nikumbuke</label>
                <a href="forgot_password.php">Umesahau Nenosiri?</a>
            </div>

            <button type="submit" name="submit">Ingia</button>
        </form>

        <p class="register-link">Huna akaunti? <a href="register.php">Tengeneza Akaunti</a></p>
    </div>
</body>
</html>
