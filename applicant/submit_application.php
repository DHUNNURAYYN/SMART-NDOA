<?php
include '../connection.php'; // Connect to the DB
include '../session_check.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Target directory for uploads
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // File upload
    $zanid = $_FILES["zanid"]["name"];
    $zanid_tmp = $_FILES["zanid"]["tmp_name"];
    $zanid_path = $target_dir . time() . '_' . basename($zanid); // unique filename
    move_uploaded_file($zanid_tmp, $zanid_path);

    // Collect form data
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $nationality = $_POST['uraia'];
    $shehia = $_POST['shehia'];
    $district = $_POST['wilaya'];
    $phone = $_POST['phone'];
    $employed = $_POST['umeajiriwa'];
    $workplace = isset($_POST['sehemu']) ? $_POST['sehemu'] : null; // nullable
    $marital_status = $_POST['ndoa'];
    $disability = $_POST['disability'];
    $education_level = $_POST['elimu'];

    // Prepare and execute insert query

$sql = "INSERT INTO application_form (
    user_id, full_name, gender, dob, nationality, shehia, district, phone,
    employed, workplace, marital_status, disability, id_picture, education_level
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "isssssssssssss",
    $user_id, $name, $gender, $dob, $nationality, $shehia, $district, $phone,
    $employed, $workplace, $marital_status, $disability, $zanid_path, $education_level
);


    if ($stmt->execute()) {
        echo "<script>alert('Maombi yako yametumwa kikamilifu! '); window.location.href='applicant_dashboard.php';</script>";
    } else {
        echo "<script>alert('Kuna tatizo wakati wa kutuma maombi. '); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../student/index.php");
    exit;
}
?>
