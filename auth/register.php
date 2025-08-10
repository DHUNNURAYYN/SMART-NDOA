<?php
include('../connection.php');

if (isset($_POST['submit'])) {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password_plain = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password_plain !== $confirm_password) {
        header("location: register.php?sms=Nenosiri hayalingani");
        exit();
    }

    // Check if user exists
    $check_query = "SELECT * FROM users WHERE email = '$email' OR phone = '$phone'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        header("location: register.php?sms=Samahani, mtumiaji tayari yupo");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

    // Insert into database
    $sql = "INSERT INTO users (full_name, phone, email, password, role)
            VALUES ('$fullname', '$phone', '$email', '$hashed_password', 'applicant')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("location: login.php?sms=Usajili umefanikiwa");
    } else {
        header("location: register.php?sms=Usajili umeshindikana");
    }
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Jisajili</title>
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script>
        function validateForm() {
            var fullname = document.forms["registerForm"]["fullname"].value;
            var email = document.forms["registerForm"]["email"].value;
            var password = document.forms["registerForm"]["password"].value;
            var confirm_password = document.forms["registerForm"]["confirm_password"].value;
            var terms = document.forms["registerForm"]["terms"].checked;
            var phone = document.forms["registerForm"]["phone"].value;

            if (fullname.trim() === "") {
                alert("Jina kamili linahitajika.");
                return false;
            }

            if (phone.trim() === "") {
                alert("Namba ya simu inahitajika.");
                return false;
            }

            var phonePattern = /^[0-9]{7,15}$/;
            if (!phonePattern.test(phone)) {
                alert("Weka namba ya simu sahihi (tarakimu 7 hadi 15).");
                return false;
            }

            if (email.trim() === "") {
                alert("Barua pepe inahitajika.");
                return false;
            }

            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                alert("Tafadhali weka barua pepe sahihi.");
                return false;
            }

            if (password === "") {
                alert("Nenosiri linahitajika.");
                return false;
            }

            if (password.length < 6) {
                alert("Nenosiri lazima liwe na angalau herufi 6.");
                return false;
            }

            if (confirm_password === "") {
                alert("Tafadhali thibitisha nenosiri.");
                return false;
            }

            if (password !== confirm_password) {
                alert("Nenosiri hayalingani.");
                return false;
            }

            if (!terms) {
                alert("Lazima ukubali masharti.");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>
    <div class="register-container">
        <?php 
        // Replace message with alert if set
        if(isset($_GET["sms"])) {
            $msg = htmlspecialchars($_GET["sms"], ENT_QUOTES, 'UTF-8');
            echo "<script>alert('$msg');</script>";
        }
        ?>

        <div class="user-logo">
            <img src="../Logo/logo.JPG" alt="Nembo ya Mufti">
        </div>

        <form name="registerForm" action="register.php" method="POST" onsubmit="return validateForm();">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fullname" placeholder="Jina Kamili" required>
            </div>

            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="phone" placeholder="Namba ya Simu" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Barua Pepe" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Nenosiri" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" placeholder="Thibitisha Nenosiri" required>
            </div>

            <div class="options">
                <label><input type="checkbox" name="terms"> Nakubali masharti</label>
            </div>

            <button type="submit" name="submit">JISAJILI</button>
        </form>

        <p class="register-link">Tayari una akaunti? <a href="login.php">Ingia</a></p>
    </div>
</body>
</html>
