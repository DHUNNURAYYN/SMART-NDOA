<?php
include '../connection.php';
include '../session_check.php';

$message = "";
$latestBatch = "XX"; // Default batch number if none in DB

// Fetch latest batch number
$getBatch = $conn->query("SELECT batch_number FROM training_schedule ORDER BY id DESC LIMIT 1");
if ($getBatch && $getBatch->num_rows > 0) {
    $row = $getBatch->fetch_assoc();
    $latestBatch = $row['batch_number'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $batch_number = $_POST['batch_number'];

    $sql = "INSERT INTO training_schedule (start_date, batch_number) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $start_date, $batch_number);

    if ($stmt->execute()) {
        $message = "<div class='success'>✅ Tarehe na mkupuo zimehifadhiwa kikamilifu!</div>";
        $latestBatch = $batch_number; // Update immediately
    } else {
        $message = "<div class='error'>❌ Imeshindikana kuhifadhi taarifa.</div>";
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Set Batch & Training Date</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            width: 50%;
            margin: 40px auto;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }

        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background: rgb(5, 80, 131);
        }

        .success, .error {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
            width: 50%;
            margin-left: auto;
            margin-right: auto;
        }

        .success {
            background-color: #e0ffe0;
            color: #006400;
        }

        .error {
            background-color: #ffe0e0;
            color: #a00000;
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
            <h1>Manage Trainee</h1>
        </header>

        <div class="form-container">
            <h2>Set Batch and Training Start Date</h2>

            <h3 style="text-align:center; color:#228B22;">Current Batch: <?= htmlspecialchars($latestBatch) ?></h3>

            <?= $message ?>

            <form method="POST">
                <label for="batch_number">Batch Number:</label>
                <input type="number" name="batch_number" id="batch_number" required>

                <label for="start_date">Training Start Date:</label>
                <input type="date" name="start_date" id="start_date" required>

                <button type="submit"><i class="fas fa-save"></i> Save Information</button>
            </form>
        </div>
    </div>

</div>  
</body>
</html>

<?php $conn->close(); ?>
