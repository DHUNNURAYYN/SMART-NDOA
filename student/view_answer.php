<?php
include '../connection.php';
include '../session_check.php';

$user_id = $_SESSION['user']; // Current logged-in student

// Mark all unviewed answers as viewed
$update = $conn->prepare("UPDATE questions SET viewed = 'Yes' WHERE user_id = ? AND viewed = 'No'");
$update->bind_param("i", $user_id);
$update->execute();
$update->close();

// Fetch answered questions (ordered by latest question first)
$stmt = $conn->prepare("SELECT * FROM questions WHERE user_id = ? AND status = 'Answered' ORDER BY question_id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Majibu ya Maswali</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .main-content h1{
            text-align: center;
            padding: 15px;
           
        }
        .answered-container {
            margin: 30px auto;
            width: 90%;
            max-height: auto;
            overflow-y: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .answered-container h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        .answer-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .answer-number {
            width: 30px;
            font-weight: bold;
            font-size: 16px;
            color: #333;
            padding-top: 5px;
        }

        .answer-box {
            flex: 1;
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 10px;
            background-color: #f9f9f9;
            transition: background-color 0.3s ease;
        }

        .answer-box:hover {
            background-color: #f1f1f1;
        }

        .answer-box strong {
            color: #007bff;
        }

        .answer-box small {
            color: #666;
        }
        
    </style>
</head>
<body>
<div class="dashboard-container">
    <div class="sidebar">
        <?php include '../sidebar.php'; ?>
    </div>

     <div class="main-content">
            <h1>Majibu Ya Maswali </h1>
        <div class="answered-container">
            <?php 
            $i = 1;
            while ($row = $result->fetch_assoc()): 
            ?>
                <div class="answer-item">
                    <div class="answer-number"><?= $i ?>.</div>
                    <div class="answer-box">
                        <strong>Swali:</strong> <?= $row['content'] ?><br>
                        <strong>Jibu:</strong> <?= $row['answer'] ?><br>
                        <small><em>Tarehe: <?= $row['date_asked'] ?></em></small>
                    </div>
                </div>
            <?php 
                $i++;
               
            endwhile; 
            ?>
        </div>
    </div>
</div>
</body>
</html>
