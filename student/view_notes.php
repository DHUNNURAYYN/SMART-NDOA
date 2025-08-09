<?php
include "../connection.php";
include "../session_check.php";

// Fetch the student's full name using their session user ID
$user_id = $_SESSION['user'];
$name = "Student"; // Default fallback

$sql = "SELECT full_name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $name = $row['full_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - Smart Ndoa 🎓</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    .assessment-btn {
    display: inline-block;
    padding: 12px 24px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: background-color 0.3s ease;
}

.assessment-btn:hover {
    background-color: #0056b3;
}

</style>
</head>
<body>
    <div class="dashboard-container">

        <!-- Sidebar -->
        <?php include "../sidebar.php"; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Welcome, <b><?= htmlspecialchars($name) ?></b> </h1>
            </header>

            <!-- Notes Section -->
            <div class="section" id="notes">
                <div class="info-box">
                    <h3> Course Notes</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch books from the database
                            $sql = "SELECT * FROM books ORDER BY uploaded_on DESC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($row['title']) ?> by <?= htmlspecialchars($row['author']) ?></td>
                                <td>
                                    <a href="../lecture<?= htmlspecialchars($row['file_path']) ?>" download>
                                        <button>Download PDF</button>
                                    </a>
                                </td>
                            </tr>
                            <?php
                                endwhile;
                            else:
                            ?>
                            <tr><td colspan="2">No notes available yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <div style="margin-top: 30px;">
            <a href="receive_assessment.php" class="assessment-btn"> Take Assessment</a>
        </div>


        </div> <!-- End of main-content -->
    </div> <!-- End of dashboard-container -->
</body>
</html>
