<?php
include("connection.php");
session_start();

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Use prepared statement for security
    $stmt = $conn->prepare("SELECT user_id, full_name, role, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // For now comparing plain text (consider password_hash for security)
        if ($password === $user['password']) {
            $_SESSION['user'] = $user['user_id']; // ✅ Store user_id in session
            $_SESSION['role'] = $user['role'];

            // Redirect based on role (optional)
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: student_dashboard.php");
            }
            exit();
        } else {
            $message = "❌ Invalid password.";
        }
    } else {
        $message = "❌ No user found with this email.";
    }

    $stmt->close();
}
$conn->close();
?>

<!-- Optional HTML for showing message -->
<?php if (!empty($message)): ?>
    <p style="color:red;"><?php echo $message; ?></p>
<?php endif; ?>
