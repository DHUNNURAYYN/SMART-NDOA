<?php
include('../connection.php');

if (isset($_POST['submit'])) {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password_plain = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password_plain !== $confirm_password) {
        header("location: register.php?sms=Passwords do not match");
        exit();
    }

    // Check if user exists
    $check_query = "SELECT * FROM users WHERE email = '$email' OR phone = '$phone'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        header("location: register.php?sms=Sorry the user already exists");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password_plain, PASSWORD_DEFAULT);

    // Insert into database
    $sql = "INSERT INTO users (full_name, phone, email, password, role)
            VALUES ('$fullname', '$phone', '$email', '$hashed_password', 'applicant')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("location: login.php?sms=User Registered Successfully");
    } else {
        header("location: register.php?sms=Registration failed");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../register.css">
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
                alert("Full Name is required.");
                return false;
            }

            if (phone.trim() === "") {
                alert("Phone number is required.");
                return false;
            }

            var phonePattern = /^[0-9]{7,15}$/;
            if (!phonePattern.test(phone)) {
                alert("Enter a valid phone number (7 to 15 digits).");
                return false;
            }

            if (email.trim() === "") {
                alert("Email is required.");
                return false;
            }

            var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            if (password === "") {
                alert("Password is required.");
                return false;
            }

            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                return false;
            }

            if (confirm_password === "") {
                alert("Please confirm your password.");
                return false;
            }

            if (password !== confirm_password) {
                alert("Passwords do not match.");
                return false;
            }

            if (!terms) {
                alert("You must agree to the terms.");
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
            <img src="../Logo/logo.JPG" alt="Mufti Logo">
        </div>

        <form name="registerForm" action="register.php" method="POST" onsubmit="return validateForm();">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fullname" placeholder="Full Name" required>
            </div>

            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="phone" placeholder="Phone Number" required>
            </div>

            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email ID" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>

            <div class="options">
                <label><input type="checkbox" name="terms"> I agree to the terms</label>
            </div>

            <button type="submit" name="submit">REGISTER</button>
        </form>

        <p class="register-link">Already have an account! <a href="login.php">Login</a></p>
    </div>
</body>
</html>
