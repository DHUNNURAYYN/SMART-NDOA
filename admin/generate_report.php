<?php
include '../connection.php';
include '../session_check.php';

// Hakikisha ni admin tu anaweza kuingia hapa
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Andika query kupata data zote muhimu
$sql = "
SELECT
  u.user_id,
  u.full_name,
  u.email,
  u.phone,
  total_q.total_questions,
  IFNULL(correct_answers.correct_count, 0) AS correct_answers,
  ROUND(IFNULL(correct_answers.correct_count, 0) / total_q.total_questions * 100, 2) AS score_percentage,
  CASE
    WHEN ROUND(IFNULL(correct_answers.correct_count, 0) / total_q.total_questions * 100, 2) >= 75 THEN 'Imepitishwa'
    ELSE 'Haijapitishwa'
  END AS assessment_status,
  IFNULL(attendance_summary.total_days, 0) AS total_days,
  IFNULL(attendance_summary.days_present, 0) AS days_present,
  ROUND(IFNULL(attendance_summary.days_present, 0) / IFNULL(attendance_summary.total_days, 1) * 100, 2) AS attendance_percentage
FROM users u
CROSS JOIN (SELECT COUNT(*) AS total_questions FROM assessment) AS total_q
LEFT JOIN (
    SELECT aa.user_id, COUNT(*) AS correct_count
    FROM assessment_answers aa
    JOIN assessment a ON aa.assessment_id = a.id
    WHERE aa.selected_option = a.correct_option
    GROUP BY aa.user_id
) AS correct_answers ON u.user_id = correct_answers.user_id
LEFT JOIN (
    SELECT user_id,
           COUNT(*) AS total_days,
           SUM(CASE WHEN status = 'Present' THEN 1 ELSE 0 END) AS days_present
    FROM attendances
    GROUP BY user_id
) AS attendance_summary ON u.user_id = attendance_summary.user_id
WHERE u.role = 'student'
ORDER BY u.full_name ASC
";

$result = $conn->query($sql);
if (!$result) {
    die("Tatizo katika kupata ripoti: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8" />
    <title>Ripoti za Wanafunzi - Smart Ndoa</title>
    <link rel="stylesheet" href="../dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        .report-container {
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
            font-size: 14px;
        }
        th {
            color: white;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .status-passed {
            font-weight: bold;
            color: green;
            text-transform: uppercase;
        }
        .status-failed {
            font-weight: bold;
            color: red;
            text-transform: uppercase;
        }
        .back-btn {
            display: inline-block;
            margin-bottom: 10px;
            background: #228B22;
            color: white;
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        .back-btn:hover {
            background: #1a6d1a;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <?php include '../sidebar.php'; ?>

    <div class="main-content">
        <header>
            <h1>Ripoti za Wanafunzi Kiujumla</h1>
        </header>

        <div class="report-container">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Jina Kamili</th>
                        <th>Barua Pepe</th>
                        <th>Namba ya Simu</th>
                        <th>Jumla Maswali</th>
                        <th>Maswali Aliyopata</th>
                        <th>Asilimia (%)</th>
                        <th>Hali ya Tathmini</th>
                        <th>Jumala ya siku</th>
                        <th>Alizohudhuria</th>
                        <th>Asilimia Mahudhurio (%)</th>
                    </tr>
                </thead>
               <tbody>
                <?php if($result->num_rows > 0): ?>
                    <?php 
                    $count = 1; // kuhesabu namba za rows
                    while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $count++ ?></td> <!-- Namba ya mfuatano -->
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= $row['total_questions'] ?></td>
                        <td><?= $row['correct_answers'] ?></td>
                        <td><?= $row['score_percentage'] ?>%</td>
                        <td class="<?= $row['assessment_status'] == 'Imepitishwa' ? 'status-passed' : 'status-failed' ?>">
                            <?= $row['assessment_status'] ?>
                        </td>
                        <td><?= $row['total_days'] ?></td>
                        <td><?= $row['days_present'] ?></td>
                        <td><?= $row['attendance_percentage'] ?>%</td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="11">Hakuna taarifa za wanafunzi waliopatikana.</td></tr>
                <?php endif; ?>
                </tbody>

            </table>
            <button id="downloadReportBtn" style="margin-top: 20px; padding: 10px 20px; background-color: #228B22; color: white; border: none; border-radius: 6px; cursor: pointer;">
                    ⬇ Pakua Ripoti
                </button>

        </div>
    </div>
</div>



<script>
document.getElementById('downloadReportBtn').addEventListener('click', function () {
    const table = document.querySelector('table');
    let csvContent = '';
    
    for (let row of table.rows) {
        let rowData = [];
        for (let cell of row.cells) {
            let text = cell.innerText.replace(/,/g, ''); 
            rowData.push('"' + text + '"'); 
        }
        csvContent += rowData.join(',') + '\n';
    }

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.href = url;
    a.download = 'ripoti_ya_wanafunzi.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
});
</script>

</body>
</html>

<?php $conn->close(); ?>
