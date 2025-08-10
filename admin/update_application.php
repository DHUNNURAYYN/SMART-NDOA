<?php
include '../connection.php';
include '../session_check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_id = $_POST['form_id'];
    $full_name = $_POST['full_name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $nationality = $_POST['nationality'];
    $shehia = $_POST['shehia'];
    $district = $_POST['district'];
    $phone = $_POST['phone'];
    $employed = $_POST['employed'];

    // Ensure workplace is a string and truncate to 255 chars (adjust length if needed)
    $workplace = isset($_POST['workplace']) ? substr(trim($_POST['workplace']), 0, 255) : '';

    $marital_status = $_POST['marital_status'];
    $disability = $_POST['disability'];
    $education_level = $_POST['education_level'];
    $status = $_POST['status'];

    $sql = "UPDATE application_form SET 
        full_name=?, gender=?, dob=?, nationality=?, shehia=?, district=?,
        phone=?, employed=?, workplace=?, marital_status=?, disability=?,
        education_level=?, status=?
        WHERE form_id=?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

    $stmt->bind_param("sssssssssssssi", 
        $full_name, $gender, $dob, $nationality, $shehia, $district,
        $phone, $employed, $workplace, $marital_status, $disability,
        $education_level, $status, $form_id
    );

    if ($stmt->execute()) {
        header("Location: view_application.php?id=$form_id&updated=1");
        exit;
    } else {
        echo "❌ Hitilafu wakati wa kuhifadhi mabadiliko: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
