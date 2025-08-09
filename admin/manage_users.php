<?php
include '../connection.php';
include '../session_check.php';

// Fetch all users without password column
$sql = "SELECT user_id, full_name, email, role FROM users";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die("Error fetching users: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .users {
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

        .icon-action {
            font-size: 18px;
            margin-right: 10px;
        }

        .icon-edit {
            color: #2980b9;
        }

        .icon-delete {
            color: #e74c3c;
        }

        .icon-edit:hover, .icon-delete:hover {
            opacity: 0.7;
        }
    </style>
    <script>
        if (window.location.search.includes('success=1')) {
            const url = new URL(window.location);
            url.searchParams.delete('success');
            window.history.replaceState({}, document.title, url.toString());
        }
    </script>
</head>
<body>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<script>alert("User data updated successfully!");</script>
<?php endif; ?>

<div class="dashboard-container">
    <?php include '../sidebar.php'; ?>
    <div class="main-content">
        <header><h1>Manage Users</h1></header>

        <div class="users">
            <table>
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $sn = 1;
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr> 
                            <td><?= $sn++ ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['role']) ?></td>
                            <td>
                                <a href="edit_users.php?id=<?= $row['user_id'] ?>" 
                                   title="Edit" 
                                   class="icon-action icon-edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_users.php?id=<?= $row['user_id'] ?>" 
                                   title="Delete" 
                                   onclick="return confirm('Are you sure you want to delete this user?');" 
                                   class="icon-action icon-delete">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>

<?php mysqli_close($conn); ?>
