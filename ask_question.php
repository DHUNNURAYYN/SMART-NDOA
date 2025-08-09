<?php
session_start();
include 'connection.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $question = trim($_POST['question'] ?? '');

    // Validate inputs
    if (empty($name)) {
        $errors[] = "Tafadhali ingiza jina lako.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Tafadhali ingiza barua pepe sahihi.";
    }
    if (empty($question)) {
        $errors[] = "Tafadhali andika swali lako.";
    }

    if (empty($errors)) {
        // Insert question without user_id, store name and email
        $stmt = $conn->prepare("INSERT INTO questions (name, email, question, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
        $stmt->bind_param("sss", $name, $email, $question);

        if ($stmt->execute()) {
            // JS Alert on success
            echo "<script>
                alert('Umefanikiwa kuuliza swali. Hivyo tutakutuma jibu lako kupitia Gmail.');
                window.location.href = 'index.php';
            </script>";
            exit();
        } else {
            $errors[] = "Tatizo limejitokeza, tafadhali jaribu tena baadaye.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Uliza Swali | SMART NDOA</title>
<link rel="stylesheet" href="../dashboard.css">
<style>
    form {
        margin: 50px auto;
        width: 50%;
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
    <div class="main-content">
        <header>
            <h2>Uliza Swali </h2>
        </header>

        <?php if (!empty($errors)): ?>
            <div class="message error">
                <?php foreach ($errors as $e) echo "<div>$e</div>"; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <label>Jina lako:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>

            <label>Barua pepe:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>

            <label>Swali lako:</label>
            <textarea name="question" rows="5" required><?= htmlspecialchars($_POST['question'] ?? '') ?></textarea>

            <button type="submit">Tuma Swali</button>
                <button type="button" onclick="window.location.href='index.php'">Hairi Kutuma</button>

    </div>
</body>
</html>
