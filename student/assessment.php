<?php
include '../connection.php';
include '../session_check.php';

$user_id = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['answers'])) {
    // Step 1: Check if student already did assessment
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM assessment_answers WHERE user_id = ?");
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_stmt->bind_result($answer_count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($answer_count > 0) {
        echo "<script>
        alert('Tayari umeshajibu maswali haya. Huwezi kuyajibu tena.');       
             window.location.href = 'student_dashboard.php';
        </script>";
        exit();
    }

    // Step 2: Save new answers & count results
    $total_questions = count($_POST['answers']);
    $correct_count = 0;

    $insert_stmt = $conn->prepare("INSERT INTO assessment_answers (user_id, assessment_id, selected_option) VALUES (?, ?, ?)");

    foreach ($_POST['answers'] as $assessment_id => $selected_option) {
        // Hifadhi jibu
        $insert_stmt->bind_param("iis", $user_id, $assessment_id, $selected_option);
        $insert_stmt->execute();

        // Angalia kama jibu ni sahihi
        $check_correct = $conn->prepare("SELECT correct_option FROM assessment WHERE id = ?");
        $check_correct->bind_param("i", $assessment_id);
        $check_correct->execute();
        $check_correct->bind_result($correct_option);
        $check_correct->fetch();
        $check_correct->close();

        if ($selected_option === $correct_option) {
            $correct_count++;
        }
    }

    // Step 3: Hesabu asilimia
    $score_percentage = round(($correct_count / $total_questions) * 100, 2);

    // Step 4: Toa feedback kwenye alert
    echo "<script>
        alert('Asante! Majibu yako yamehifadhiwa. Umefaulu maswali $correct_count kati ya $total_questions. Alama yako ni $score_percentage%.');
        window.location.href = 'student_dashboard.php';
    </script>";
    exit();
}

// Fetch questions
$sql = "SELECT * FROM assessment ORDER BY id ASC";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("<p style='color:red; text-align:center; font-size:1.2rem;'> Hakuna maswali yaliyowekwa kwa sasa.</p>");
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8" />
    <title>Mtihani wa Kozi - Smart Ndoa</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
    
            color: #333;
        }
        h1 {
            color: black;
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
        }
        form {
            max-width: 1000px;
            background: #fff;
            margin: 0 auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        .question {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e1e7ef;
        }
        .question:last-child {
            border-bottom: none;
        }
        .question p {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }
        label {
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            border: 1px solid transparent;
            cursor: pointer;
            margin-bottom: 10px;
            transition: all 0.25s ease;
            user-select: none;
            font-size: 1rem;
        }
        label:hover {
            background-color: #e6f0ff;
            border-color: #007bff;
        }
        input[type="radio"] {
            margin-right: 12px;
            transform: scale(1.2);
            vertical-align: middle;
            cursor: pointer;
        }
        input[type="radio"]:checked + span {
            font-weight: 700;
            color: #007bff;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 14px 35px;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            display: block;
            margin: 30px auto 0;
            box-shadow: 0 5px 12px rgba(0, 123, 255, 0.4);
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
            box-shadow: 0 8px 20px rgba(0, 86, 179, 0.5);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include "../sidebar.php"; ?>
        <div class="main-content">
            <header>
                <h1 >Mtihani wa Kujipima</h1>
            </header>
            <form method="post">
                <?php
                $q_no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='question'>";
                    echo "<p><b>{$q_no}. " . htmlspecialchars($row['question']) . "</b></p>";
                    echo "<label><input type='radio' name='answers[{$row['id']}]' value='A' required> <span>" . htmlspecialchars($row['option_a']) . "</span></label>";
                    echo "<label><input type='radio' name='answers[{$row['id']}]' value='B'> <span>" . htmlspecialchars($row['option_b']) . "</span></label>";
                    echo "<label><input type='radio' name='answers[{$row['id']}]' value='C'> <span>" . htmlspecialchars($row['option_c']) . "</span></label>";
                    echo "<label><input type='radio' name='answers[{$row['id']}]' value='D'> <span>" . htmlspecialchars($row['option_d']) . "</span></label>";
                    echo "</div>";
                    $q_no++;
                }
                ?>
                <input type="submit" value=" Tuma Majibu">
            </form>
        </div>
    </div>
</body>
</html>
