<?php
include '../connection.php';
include '../session_check.php';

if (!isset($_GET['id'])) {
    echo " Hakuna fomu iliyochaguliwa.";
    exit;
}

$form_id = $_GET['id'];

// Fetch application data
$sql = "SELECT * FROM application_form WHERE form_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $form_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows < 1) {
    echo " Fomu haipo.";
    exit;
}

$form = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>View Application</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
.info-box {
    background: #fff;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    padding: 20px;
    border-radius: 8px;
    width: 90%;       /* optional */
    margin: 20px auto;
    
    max-height: auto;      /* ukomo wa urefu wa box */
    overflow-y: auto;       /* onyesha scroll bar wima wakati maelezo yamezidi */
}


.info-box table {
    width: 100%;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
}

.info-box th, .info-box td {
    text-align: left;
    padding: 8px 12px;
    border-bottom: 1px solid #eee;
    font-size: 15px;
}

.buttons {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}


.buttons a {
    display: inline-block;
    text-decoration: none;
    padding: 10px 18px;
    margin-right: 10px;
    border-radius: 5px;
    color: white;
    font-weight: 600;
    font-family: Arial, sans-serif;
    background-color: #007bff; /* kuongeza rangi ya button */
}


.buttons {
    margin-top: 15px;
}
.buttons a {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 6px;
    color: white;
    text-decoration: none;
    font-weight: 600;
    margin-right: 10px;
    transition: background-color 0.3s ease;
}

.edit-btn {
    background-color: #007bff; /* Blue */
}
.edit-btn:hover {
    background-color: #0056b3;
}

.approve-btn {
    background-color: #45584aff; /* Green */
}
.approve-btn:hover {
    background-color: #1e7e34;
}

img {
    max-height: 120px;
    border: 1px solid #ccc;
    margin-top: 10px;
    border-radius: 4px;
}

.kitambulisho-img {
    max-width: 120px;
    border-radius: 5px;
    margin-top: 5px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    transition: transform 0.2s ease;
}

.kitambulisho-img:hover {
    transform: scale(1.05);
}


.download-id-card {
  display: inline-flex;
  align-items: center;
  text-decoration: none;
  color: #007bff;
  font-weight: 600;
}

.download-id-card i {
  margin-right: 8px;
  font-size: 18px;
}
.download-id-card:hover {
  color: #0056b3;
}

    </style>
   
        
</head>
<body>

<div class="dashboard-container">
    <?php include '../sidebar.php'; ?>

    <div class="main-content">
        <header>
            <h1>Tazama Maombi</h1>
        </header>

        <div class="info-box">
            <h3> Taarifa Binafsi</h3>
            <table>
                <tr><th>Jina Kamili</th><td><?= $form['full_name'] ?></td></tr>
                <tr><th>Jinsia</th><td><?= $form['gender'] ?></td></tr>
                <tr><th>Tarehe ya Kuzaliwa</th><td><?= $form['dob'] ?></td></tr>
                <tr><th>Uraia</th><td><?= $form['nationality'] ?></td></tr>
                <tr><th>Wilaya</th><td><?= $form['district'] ?></td></tr>
                <tr><th>Shehia</th><td><?= $form['shehia'] ?></td></tr>
                <tr><th>Namba ya Simu</th><td><?= $form['phone'] ?></td></tr>
                <tr><th>Umeajiriwa</th><td><?= $form['employed'] ?></td></tr>
                <tr><th>Sehemu ya Kazi</th><td><?= $form['workplace'] ?></td></tr>
                <tr><th>Hali ya Ndoa</th><td><?= $form['marital_status'] ?></td></tr>
                <tr><th>Ulemavu</th><td><?= $form['disability'] ?: 'Hakuna' ?></td></tr>
                <tr><th>Kiwango cha Elimu</th><td><?= $form['education_level'] ?></td></tr>
                <tr><th>Status ya Maombi</th><td><strong><?= ucfirst($form['status']) ?></strong></td></tr>
                <tr>
                   <th>Kitambulisho</th>
                <td>
                    <!-- Clickable image for download -->
                    <a href="<?= htmlspecialchars('/applicant/' . $form['id_picture']) ?>" download>
                        <img src="<?= htmlspecialchars('/applicant/' . $form['id_picture']) ?>" alt="Kitambulisho" class="kitambulisho-img">
                    </a>
                    
                    <!-- Download button -->
                    <br>
                    <a href="path_to_id_card.pdf" download class="download-id-card">
                    
                    </a>

                </td>


                </tr>
            </table>
            <div class="buttons">
                <a class="edit-btn" href="edit_application.php?id=<?= $form['form_id'] ?>">Maboresho</a>
                <a class="approve-btn" href="generate_application_pdf.php?id=<?= $form['form_id'] ?>" target="_blank">⬇ Pakua Ripoti</a>
            </div>
          
        </div>
    </div>
</div>

</body>
</html>
