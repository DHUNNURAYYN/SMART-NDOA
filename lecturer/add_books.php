<?php

include '../connection.php';
include '../session_check.php';

$message = "";

// Debug: Check if session is set
if (!isset($_SESSION['user'])) {
    die(" Session error: No user logged in.");
}

$uploaded_by = (int)$_SESSION['user'];

// Check if user exists in database
$check_user = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
$check_user->bind_param("i", $uploaded_by);
$check_user->execute();
$check_user->store_result();

if ($check_user->num_rows === 0) {
    die(" Error: User ID $uploaded_by does not exist in the database.");
}
$check_user->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $category = $conn->real_escape_string($_POST['category']);

    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
        $fileName = basename($_FILES['pdf_file']['name']);
        $targetDir = "uploads/";

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetFile = $targetDir . time() . "_" . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if ($fileType === "pdf") {
            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $targetFile)) {
                $stmt = $conn->prepare("INSERT INTO books (title, author, category, file_path, uploaded_by) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssi", $title, $author, $category, $targetFile, $uploaded_by);

                if ($stmt->execute()) {
                    $message = " Book uploaded successfully!";
                } else {
                    $message = " Database error: " . $stmt->error;
                    unlink($targetFile);
                }
                $stmt->close();
            } else {
                $message = " Failed to move uploaded file.";
            }
        } else {
            $message = " Only PDF files are allowed!";
        }
    } else {
        $message = " Please upload a PDF file.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Books</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
       form {
    margin: 50px auto; /* centers the form horizontally */
    width: 50%;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    background-color: #fff;
}

/* Label and input spacing */
form label {
    display: block;
    margin-top: 15px;
    font-weight: bold;
}

form input[type="text"],
form input[type="file"],
form textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #aaa;
    border-radius: 5px;
    box-sizing: border-box;
}

/* Submit button styling */
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
    <div class="dashboard-container">
        <div class="sidebar">
            <!-- Sidebar -->
            <?php include '../sidebar.php'; ?>
        </div>

        <div class="main-content">
            <header>
                <h1>Add Books</h1>
            </header>
        <?php if ($message): ?>
            <div class="message <?php echo (strpos($message, '') === 0) ? 'error' : ''; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Title:</label>
            <input type="text" name="title" required />

            <label>Author:</label>
            <input type="text" name="author" required />

            <label>Category:</label>
            <input type="text" name="category" required />

            <label>Upload PDF File:</label>
            <input type="file" name="pdf_file" accept=".pdf" required />

            <button type="submit" class="btn-submit">Upload Book</button>
        </form>
    </div>
</body>
</html>
