<?php
include('../connection.php');

if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone']; // <-- new
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        header("location: register.php?sms=Passwords do not match");
        exit();
    }

    // Save user with phone
    $sql = "INSERT INTO users (full_name, phone, email, password, role)
            VALUES ('$fullname', '$phone', '$email', '$password', 'applicant')";

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

            if (fullname.trim() === "") {
                alert("Full Name is required.");
                return false;
            }
            var phone = document.forms["registerForm"]["phone"].value;
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
        <?php if(isset($_GET["sms"])) { ?>
            <p style="color:red; text-align:center">
                <?php echo $_GET["sms"]; ?>
            </p>
        <?php } ?>

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
