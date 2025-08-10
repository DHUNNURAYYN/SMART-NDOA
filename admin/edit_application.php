<?php
include '../connection.php';
include '../session_check.php';

if (!isset($_GET['id'])) {
    echo "⚠️ Hakuna fomu iliyochaguliwa kwa ajili ya kuhariri.";
    exit;
}

$form_id = $_GET['id'];

$query = "SELECT * FROM application_form WHERE form_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $form_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows < 1) {
    echo "⚠️ Fomu haijapatikana.";
    exit;
}

$form = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <title>Hariri Fomu ya Maombi</title>
    <link rel="stylesheet" href="../dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    .edit-form-container {
        width: 100%;
        max-width: 1000px;
        margin: 30px auto;
        padding: 25px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        overflow-y: auto;
        font-family: Arial, sans-serif;
    }

    .edit-form-container h2 {
        margin-bottom: 20px;
        font-size: 22px;
        color: #333;
        text-align: center;
    }

    .edit-form-container label {
        display: block;
        margin-bottom: 6px;
        font-weight: 600;
        color: #444;
    }

    .edit-form-container input,
    .edit-form-container select,
    .edit-form-container textarea {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        font-family: Arial, sans-serif;
        box-sizing: border-box;
    }

    .edit-form-container textarea {
        resize: vertical;
        min-height: 80px;
    }

    .submit-btn {
        display: block;
        margin: 20px auto 0;
        background-color: #007bff;
        color: #fff;
        padding: 12px 30px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 15px;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }

    .kitambulisho-img {
        width: 150px;
        border-radius: 5px;
        margin-bottom: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        transition: transform 0.2s ease;
    }
    .kitambulisho-img:hover {
        transform: scale(1.05);
    }
    </style>
</head>
<body>
<div class="dashboard-container">
    <?php include '../sidebar.php'; ?>

    <div class="main-content">
        <header>
            <h1>Rekebisha Fomu Ya Maombi</h1>
        </header>

        <form method="POST" action="update_application.php" class="edit-form-container" enctype="multipart/form-data">
            <input type="hidden" name="form_id" value="<?= htmlspecialchars($form['form_id']) ?>">

            <label>Jina Kamili</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($form['full_name']) ?>" required>

            <label>Jinsia</label>
            <select name="gender" required>
                <option value="male" <?= $form['gender'] == 'male' ? 'selected' : '' ?>>Mwanaume</option>
                <option value="female" <?= $form['gender'] == 'female' ? 'selected' : '' ?>>Mwanamke</option>
            </select>

            <label>Tarehe ya Kuzaliwa</label>
            <input type="date" name="dob" value="<?= htmlspecialchars($form['dob']) ?>" required>

            <label>Uraia</label>
            <input type="text" name="nationality" value="<?= htmlspecialchars($form['nationality']) ?>" required>

            <label>Shehia</label>
            <input type="text" name="shehia" value="<?= htmlspecialchars($form['shehia']) ?>" required>

            <label>Wilaya</label>
            <input type="text" name="district" value="<?= htmlspecialchars($form['district']) ?>" required>

            <label>Namba ya Simu</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($form['phone']) ?>" required>

            <label>Umeajiriwa?</label>
            <select name="employed" required>
                <option value="ndio" <?= $form['employed'] == 'ndio' ? 'selected' : '' ?>>Ndio</option>
                <option value="hapana" <?= $form['employed'] == 'hapana' ? 'selected' : '' ?>>Hapana</option>
            </select>

            <label>Sehemu ya Kazi</label>
            <select name="workplace">
                <option value="">--Chagua--</option>
                <option value="serikali" <?= $form['workplace'] == 'serikali' ? 'selected' : '' ?>>Serikali</option>
                <option value="binafsi" <?= $form['workplace'] == 'binafsi' ? 'selected' : '' ?>>Binafsi</option>
            </select>

            <label>Hali ya Ndoa</label>
            <select name="marital_status" required>
                <option value="umeoa" <?= $form['marital_status'] == 'umeoa' ? 'selected' : '' ?>>Umeoa/Nimeolewa</option>
                <option value="sijaoa" <?= $form['marital_status'] == 'sijaoa' ? 'selected' : '' ?>>Sijaoa/Sijaolewa</option>
                <option value="mjane" <?= $form['marital_status'] == 'mjane' ? 'selected' : '' ?>>Mjane/Mgane</option>
                <option value="ninamchumba" <?= $form['marital_status'] == 'ninamchumba' ? 'selected' : '' ?>>Ninamchumba</option>
                <option value="sinamchumba" <?= $form['marital_status'] == 'sinamchumba' ? 'selected' : '' ?>>Sina mchumba</option>
            </select>

            <label>Ulemavu</label>
            <textarea name="disability"><?= htmlspecialchars($form['disability']) ?></textarea>

            <label>Kiwango cha Elimu</label>
            <input type="text" name="education_level" value="<?= htmlspecialchars($form['education_level']) ?>" required>

            <label>Picha ya Kitambulisho</label><br>
            <?php if (!empty($form['id_picture'])): ?>
                <img src="../applicant/<?= htmlspecialchars($form['id_picture']) ?>" alt="Picha ya Kitambulisho" class="kitambulisho-img">
            <?php else: ?>
                <p>Hakuna picha ya kitambulisho iliyopakiwa.</p>
            <?php endif; ?>
            <input type="file" name="id_picture" accept="image/*">

            <label>Status ya Maombi</label>
            <select name="status">
                <option value="pending" <?= $form['status'] == 'pending' ? 'selected' : '' ?>>Inasubiri</option>
                <option value="approved" <?= $form['status'] == 'approved' ? 'selected' : '' ?>>Imekubaliwa</option>
                <option value="rejected" <?= $form['status'] == 'rejected' ? 'selected' : '' ?>>Imekataliwa</option>
            </select>

            <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Hifadhi</button>
        </form>
    </div>
</div>
</body>
</html>
