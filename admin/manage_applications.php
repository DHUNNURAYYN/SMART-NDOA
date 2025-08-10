<?php
include '../connection.php';
include '../session_check.php';
include 'send_email.php'; // ✅ Added this line

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $deleteStmt = $conn->prepare("DELETE FROM application_form WHERE form_id = ?");
    $deleteStmt->bind_param("i", $deleteId);
    $deleteStmt->execute();
    $deleteStmt->close();
}

// Handle Approve / Reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['form_id'])) {
    $formId = intval($_POST['form_id']);
    $status = $_POST['action'] === 'approve' ? 'approved' : 'rejected';

    // Update application status
    $stmt = $conn->prepare("UPDATE application_form SET status = ? WHERE form_id = ?");
    $stmt->bind_param("si", $status, $formId);
    $stmt->execute();
    $stmt->close();

    // Fetch student's email & name & phone
    $infoStmt = $conn->prepare("SELECT email, full_name, phone FROM application_form WHERE form_id = ?");
    $infoStmt->bind_param("i", $formId);
    $infoStmt->execute();
    $infoStmt->bind_result($email, $full_name, $phone);
    $infoStmt->fetch();
    $infoStmt->close();

    // Send email notification — **pass $conn as 4th argument**
    sendNotification($email, $full_name, $status, $conn);

    // If approved, update user role to 'student'
    if ($status === 'approved') {
        $getUserStmt = $conn->prepare("SELECT user_id FROM users WHERE phone = ?");
        $getUserStmt->bind_param("s", $phone);
        $getUserStmt->execute();
        $getUserStmt->bind_result($userId);
        $getUserStmt->fetch();
        $getUserStmt->close();

        if (!empty($userId)) {
            $updateRoleStmt = $conn->prepare("UPDATE users SET role = 'student' WHERE user_id = ?");
            $updateRoleStmt->bind_param("i", $userId);
            $updateRoleStmt->execute();
            $updateRoleStmt->close();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8" />
    <title>Shuhulikia  Maombi</title>
    <link rel="stylesheet" href="../dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .applications {
            margin-top: 20px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        thead {
            background: #228B22;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            color: white;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .status-text {
            font-weight: bold;
            text-transform: capitalize;
        }

        .icon-action {
            font-size: 18px;
            margin-right: 10px;
            cursor: pointer;
            text-decoration: none;
        }
        .icon-approve {
            color: #27ae60;
        }
        .icon-view {
            color: #2980b9;
        }
        .icon-delete {
            color: #e74c3c;
        }
        .icon-approve:hover,
        .icon-view:hover,
        .icon-delete:hover {
            opacity: 0.7;
        }

        form.inline-form {
            display: inline;
        }
        button.icon-button {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <?php include '../sidebar.php'; ?>

    <div class="main-content">
        <header>
            <h1>Shuhulikia Maombi</h1>
        </header>

        <div class="applications">
            <table>
                <thead>
                    <tr>
                        <th>Nambari</th>
                        <th>Jina Kamili</th>
                        <th>Simu</th>
                        <th>Shehia</th>
                        <th>Wilaya</th>
                        <th>Umeajiriwa?</th>
                        <th>Hali</th>
                        <th>Hatua</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM application_form ORDER BY form_id DESC";
                $result = $conn->query($sql);
                $sn = 1;

                if ($result && $result->num_rows > 0):
                    while($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= $sn++ ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['shehia']) ?></td>
                        <td><?= htmlspecialchars($row['district']) ?></td>
                        <td><?= htmlspecialchars($row['employed']) ?></td>
                        <td class="status-text"><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <?php if ($row['status'] === 'pending'): ?>
                                <form method="post" class="inline-form" style="margin-right:10px;">
                                    <input type="hidden" name="form_id" value="<?= $row['form_id'] ?>" />
                                    <input type="hidden" name="action" value="approve" />
                                    <button type="submit" class="icon-action icon-approve icon-button" title="Kubali">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="color:gray; font-weight:bold;">Imekubaliwa</span>
                            <?php endif; ?>

                            <a href="view_application.php?id=<?= $row['form_id'] ?>" 
                               title="Angalia" 
                               class="icon-action icon-view" target="_blank">
                                <i class="fas fa-eye"></i>
                            </a>

                            <form method="post" onsubmit="return confirm('Una uhakika unataka kufuta maombi haya?');" class="inline-form">
                                <input type="hidden" name="delete_id" value="<?= $row['form_id'] ?>" />
                                <button type="submit" name="delete" title="Futa" class="icon-action icon-delete icon-button">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                    echo "<tr><td colspan='8'>Hakuna maombi yaliyopatikana.</td></tr>";
                endif;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
<?php $conn->close(); ?>
