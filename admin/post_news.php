<?php

include '../connection.php';
include '../session_check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['description']);
    $imagePath = null;

    // Check if image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = basename($_FILES["image"]["name"]);
        $targetDir = "uploads/";
        $targetFile = $targetDir . time() . "_" . $imageName;

        // Create directory if not exists
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    if (!empty($title) && !empty($content)) {
        $stmt = $conn->prepare("INSERT INTO news (image, title, content) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $imagePath, $title, $content);

        if ($stmt->execute()) {
            echo "<script>alert(' News with image posted successfully!'); window.location.href='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert(' Error posting news.'); window.history.back();</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('!Please fill in all fields.'); window.history.back();</script>";
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post News</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
       .post-news {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    width: 50%;
    margin: 40px auto;
}

.post-news h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #2c3e50;
}

label {
    font-weight: bold;
    display: block;
    margin: 10px 0 5px;
}

input[type="text"], textarea, input[type="file"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
}

button {
    margin-top: 20px;
    padding: 10px 20px;
    background: #2980b9;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
}

button:hover {
    background: rgb(5, 80, 131);
}

    </style>
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
   <?php
    
      include '../sidebar.php';
      ?>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Post News</h1>
        </header>
        <div class="post-news">
            <h2>New News Post</h2>
          <form action="post_news.php" method="POST" enctype="multipart/form-data">
                <label for="title">News Title:</label>
                <input type="text" id="title" name="title" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="6" required></textarea>

                <label for="image">News Image:</label>
                <input type="file" id="image" name="image" accept="image/*">

                <button type="submit"><i class="fas fa-paper-plane"></i> Post News</button>
            </form>

        </div>
    </div>
</div>


</body>
</html>
