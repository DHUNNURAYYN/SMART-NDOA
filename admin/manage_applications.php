<?php
include '../connection.php';
include '../session_check.php';

// Handle Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);
    $deleteStmt = $conn->prepare("DELETE FROM application_form WHERE form_id = ?");
    $deleteStmt->bind_param("i", $deleteId);
    $deleteStmt->execute();
    $deleteStmt->close();
}

// Handle Approve/Reject
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['form_id'])) {
    $formId = intval($_POST['form_id']);
    $status = $_POST['action'] === 'approve' ? 'approved' : 'rejected';

    // Update application status
    $stmt = $conn->prepare("UPDATE application_form SET status = ? WHERE form_id = ?");
    $stmt->bind_param("si", $status, $formId);
    $stmt->execute();
    $stmt->close();

    // If approved, update user role to 'student'
    if ($status === 'approved') {
        // Get phone number from the application form
        $getPhoneStmt = $conn->prepare("SELECT phone FROM application_form WHERE form_id = ?");
        $getPhoneStmt->bind_param("i", $formId);
        $getPhoneStmt->execute();
        $getPhoneStmt->bind_result($phone);
        $getPhoneStmt->fetch();
        $getPhoneStmt->close();

        // Now get user_id from users table using the phone number
        $getUserStmt = $conn->prepare("SELECT user_id FROM users WHERE phone = ?");
        $getUserStmt->bind_param("s", $phone);
        $getUserStmt->execute();
        $getUserStmt->bind_result($userId);
        $getUserStmt->fetch();
        $getUserStmt->close();

        // If user_id is found, update role
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Applications</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            padding: 20px;
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
        .btn-approve, .btn-reject {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            font-size: 14px;
        }
        .btn-approve {
            background-color: #27ae60;
        }
        .btn-reject {
            background-color: #c0392b;
        }
        .status-text {
            font-weight: bold;
            text-transform: capitalize;
        }
        footer {
            text-align: center;
            padding: 10px;
            margin-top: 30px;
            color: #777;
            font-size: 14px;
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
            <h1>Manage Applications</h1>
        </header>

        <div class="applications">
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Full Name</th>
                        <th>Phone</th>
                        <th>Shehia</th>
                        <th>District</th>
                        <th>Employed</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM application_form";
                $result = $conn->query($sql);

                if ($result->num_rows > 0):
                    while($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['form_id']) ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['shehia']) ?></td>
                        <td><?= htmlspecialchars($row['district']) ?></td>
                        <td><?= htmlspecialchars($row['employed']) ?></td>
                        <td class="status-text"><?= htmlspecialchars($row['status']) ?></td>

                        <td>
                            <?php if ($row['status'] === 'pending'): ?>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="form_id" value="<?= $row['form_id'] ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn-approve"><i class="fas fa-check"></i> Approve</button>
                                </form>
                            <?php else: ?>
                                <span style="color:gray;">Approved</span>
                            <?php endif; ?>

                          <a href="view_application.php?id=<?= $row['form_id'] ?>" 
                        class="btn-view" 
                        style="background-color:#2980b9; color:#fff; padding:6px 12px; border:none; border-radius:5px; text-decoration:none; margin-left:5px; display:inline-block; font-size:14px;">
                            <i class="fas fa-eye"></i> View
                        </a>

                        <form method="post" 
                            onsubmit="return confirm('Are you sure you want to delete this application?');" 
                            style="display:inline;">
                            <input type="hidden" name="delete_id" value="<?= $row['form_id'] ?>">
                            <button type="submit" 
                                    name="delete" 
                                    class="btn-reject" 
                                    style="margin-left:5px; background-color:#c0392b; color:white; padding:6px 12px; border:none; border-radius:5px; font-size:14px;">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>



                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                    echo "<tr><td colspan='8'>No applications found.</td></tr>";
                endif;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
