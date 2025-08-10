<?php
include '../connection.php';
include '../session_check.php';

// Fetch all attendance records joined with user names, sorted by date DESC
$sql = "
    SELECT a.attendance_id, u.full_name, a.date, a.status
    FROM attendances a
    JOIN users u ON a.user_id = u.user_id
    ORDER BY a.date DESC, u.full_name ASC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance History</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
            background: white;
        }
        th {
            background: #228B22;
            color: white;
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
            <h1>Attendance History</h1>
        </header>

        <div class="attendance">
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Student Name</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result && $result->num_rows > 0):
                    $i = 1;
                    while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="4">Hakuna rekodi za mahudhurio.</td>
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
