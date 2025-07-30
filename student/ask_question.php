<?php
include '../connection.php';
include '../session_check.php';

$user_id = $_SESSION['user']; // 🧑 student ID from login session
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $content = $_POST['content'];

    $sql = "INSERT INTO questions (user_id, full_name, email, content) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $full_name, $email, $content);

    if ($stmt->execute()) {
        $success = true; // Flag to trigger alert later
    } else {
        $message = " Tatizo limetokea: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ask Question</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        form {
            margin: 50px auto;
            width: 90%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="email"],
        form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #aaa;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
            color: green;
        }
        .message.error {
            color: red;
        }
    </style>
</head>
<body>
<?php if ($success): ?>
    <script>
        alert("✅ Swali limewasilishwa kwa mafanikio!");
        window.location.href = "student_dashboard.php"; // Reload to prevent resubmission
    </script>
<?php endif; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php include "../sidebar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Uliza Swali Hapa</h1>
        </header>

        <?php if (!empty($message)): ?>
            <div class="message error"><?= $message ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Jina Kamili:</label>
            <input type="text" name="full_name" placeholder="Jina Kamili" required>

            <label>Barua Pepe:</label>
            <input type="email" name="email" placeholder="Barua Pepe" required>

            <label>Swali Lako:</label>
            <textarea name="content" placeholder="Andika swali hapa..." rows="5" required></textarea>

            <button type="submit">Tuma Swali</button>
        </form>
    </div>
</div>
</body>
</html>
