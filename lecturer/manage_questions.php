<?php
include '../connection.php'; // Include DB connection
include '../session_check2.php';

// Fetch questions from the database
$sql = "SELECT * FROM questions ORDER BY date_asked DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Questions</title>
    <link rel="stylesheet" href="../dashboard.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .questions { }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #228B22;
            color: white;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .btn-answer, .btn-delete {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            font-size: 14px;
            text-decoration: none;
        }
        .btn-answer {
            background-color: #16a085;
        }
        .btn-delete {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <!-- Sidebar -->
    <?php include '../sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <header>
            <h1>Manage Questions</h1>
        </header>
        <div class="questions">
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Student Name</th>
                        <th>Student Email</th>
                        <th>Question</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0):
                        $serial = 0;
                        while ($row = $result->fetch_assoc()):
                            $serial++;
                    ?>
                            <tr>
                                <td><?php echo $serial; ?></td>
                                <td><?php echo $row['full_name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['content']; ?></td>
                                <td><?php echo $row['date_asked']; ?></td>
                                <td><?php echo $row['status'] ?? 'Pending'; ?></td>
                                <td>
                                <?php if (strtolower($row['status']) != 'answered'): ?>
                                    <a href="answer_questions.php?id=<?php echo $row['question_id']; ?>" class="btn-answer">
                                        <i class="fas fa-reply"></i> Answer
                                    </a>
                                <?php endif; ?>
                                <a href="delete_question.php?id=<?php echo $row['question_id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this question?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>

                            </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                        <tr>
                            <td colspan="7">No questions found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

<?php
$conn->close();
?>
