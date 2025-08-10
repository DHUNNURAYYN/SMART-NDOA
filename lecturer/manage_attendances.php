<?php
include '../connection.php';
include '../session_check.php';

$today = date('Y-m-d');
$search_query = '';

// Handle Search
if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
}

// Handle Attendance Toggle
if (isset($_POST['toggle_status'])) {
    $user_id = intval($_POST['user_id']);
    $new_status = $_POST['new_status'] === 'Present' ? 'Present' : 'Absent';

    $check_stmt = $conn->prepare("SELECT attendance_id FROM attendances WHERE user_id = ? AND date = ?");
    $check_stmt->bind_param("is", $user_id, $today);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $update_stmt = $conn->prepare("UPDATE attendances SET status = ? WHERE user_id = ? AND date = ?");
        $update_stmt->bind_param("sis", $new_status, $user_id, $today);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO attendances (user_id, date, status) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("iss", $user_id, $today, $new_status);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    $check_stmt->close();
}

// Query to fetch students and attendance
$sql = "
    SELECT u.user_id, u.full_name, 
           IFNULL(a.status, 'Absent') AS status
    FROM users u
    LEFT JOIN attendances a ON u.user_id = a.user_id AND a.date = ?
    WHERE u.role = 'student' AND u.full_name LIKE ?
    ORDER BY u.full_name ASC
";

$stmt = $conn->prepare($sql);
$like_search = '%' . $search_query . '%';
$stmt->bind_param("ss", $today, $like_search);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Weka Mahudhurio</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .attendance-container {
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        form.search-form {
            text-align: center;
            margin-bottom: 15px;
        }
        input[type="text"] {
            padding: 8px;
            width: 250px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button.search-btn {
            padding: 8px 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button.search-btn:hover {
            background-color: #2980b9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #228B22;
            color: white;
        }
        .btn {
            padding: 6px 14px;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background: #1e8449;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <?php include '../sidebar.php'; ?>

    <div class="main-content">
        <header>
            <h1>Weka Mahudhurio (<?= htmlspecialchars($today) ?>)</h1>
        </header>

        <div class="attendance-container">
            <form class="search-form" method="GET">
                <input type="text" name="search" value="<?= htmlspecialchars($search_query) ?>" placeholder="Tafuta mwanafunzi kwa jina..." />
                <button class="search-btn" type="submit"><i class="fas fa-search"></i> Tafuta</button>
            </form>

            <table>
                <thead>
                <tr>
                    <th>Nambari</th>
                    <th>Jina la Mwanafunzi</th>
                    <th>Kozi</th>
                    <th>Tarehe</th>
                    <th>Hali</th>
                    <th>Kitendo</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td>Maadili ya Ndoa</td>
                        <td><?= htmlspecialchars($today) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <form method="POST" style="margin:0;">
                                <input type="hidden" name="user_id" value="<?= (int)$row['user_id'] ?>" />
                                <input type="hidden" name="new_status" value="<?= $row['status'] === 'Present' ? 'Absent' : 'Present' ?>" />
                                <button type="submit" name="toggle_status" class="btn">
                                    <?= $row['status'] === 'Present' ? 'Weka Hayupo' : 'Weka Yupo' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr><td colspan="6">Hakuna wanafunzi waliopatikana.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
