<?php
include '../connection.php';
include '../session_check.php';

// Get user ID
if (!isset($_GET['id'])) {
    echo " User ID not provided.";
    exit;
}

$user_id = $_GET['id'];

// Fetch user data
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo " User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
      <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
      

       body, html {
    height: 100%;
    margin: 0;
    display: flex;
    justify-content: center; /* horizontal center */
    align-items: center;     /* vertical center */
    background: #f7f7f7;     /* keep your background */
}

.container {
    width: 750px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    padding: 30px;
    /* remove margin: 50px auto; */
}


        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
input[type="email"],
input[type="password"],
select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 16px;
    height: 42px;          /* fixed height to match inputs */
    line-height: 1.2;      /* adjusts vertical text alignment */
    box-sizing: border-box; /* ensures padding included in height */
}


        input[disabled] {
            background-color: #eee;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>


<div class="container">
    <h2>Hariri Maelezo ya Mtumiaji</h2>
    <form action="update_user.php" method="post">
        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">

        <label>Jina Kamili:</label>
        <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>

        <label>Barua Pepe (Email):</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label>Jukumu (Role):</label>
        <select name="role" required>
            <option value="">-- Chagua Jukumu --</option>
            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Msimamizi (Admin)</option>
            <option value="student" <?= $user['role'] == 'student' ? 'selected' : '' ?>>Mwanafunzi</option>
            <option value="lecturer" <?= $user['role'] == 'lecturer' ? 'selected' : '' ?>>Mwalimu</option>
        </select>

        <button type="submit">Hifadhi Mabadiliko</button>
    </form>
</div>
    </div>
    </div>

</body>
</html>
