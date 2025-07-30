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

        if ($password === $user['password']) {
            $_SESSION['user'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../admin/admin_dashboard.php");
            } else if($user['role'] === 'student') {
                header("Location: ../student/student_dashboard.php");
            } elseif($user['role'] === 'lecturer') {
                header("Location: ../lecturer/lecturer_dashboard.php"); 
            } else {
                header("Location: ../applicant/applicant_dashboard.php");
            } 
            exit();
        } else {
            $message = "Incorrect phone number or password.";
        }
    } else {
        $message = "No account found with that phone number.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | SMART NDOA SYSTEM</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script>
        function validateForm() {
          var phone = document.forms["loginForm"]["phone"].value;
            var password = document.forms["loginForm"]["password"].value;

            if (phone.trim() === "") {
                alert("Phone number is required.");
                return false;
            }

            var phonePattern = /^[0-9]{7,15}$/;
            if (!phonePattern.test(phone)) {
                alert("Enter a valid phone number (7–15 digits).");
                return false;
            }

            if (password == "") {
                alert("Password is required.");
                return false;
            }

            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>
    <div class="login-container">
        <!-- Official-style error message -->
        <?php if (!empty($message)): ?>
            <div style="background-color:#f8d7da; color:#721c24; border:1px solid #f5c6cb; padding:10px; border-radius:5px; margin-bottom:15px; text-align:center;">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET["sms"])): ?>
            <div style="color:green; text-align:center; margin-bottom:15px; padding: 10px;">
                <?php echo $_GET["sms"]; ?>
            </div>
        <?php endif; ?>

        <div class="user-logo">
            <img src="../Logo/logo.JPG" alt="Mufti Logo">
        </div>

        <form name="loginForm" action="login.php" method="post" onsubmit="return validateForm()">
            <div class="input-group">
            <i class="fas fa-phone"></i>
            <input type="text" name="phone" placeholder="Phone Number" required>
        </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="options">
                <label><input type="checkbox" name="remember"> Remember me</label>
                <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" name="submit">Login</button>
        </form>

        <p class="register-link">Don’t have an account? <a href="register.php">Create Account</a></p>
    </div>
</body>

</html>
