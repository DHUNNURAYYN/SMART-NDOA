<?php
include '../connection.php'; // Connect to the DB
include '../session_check.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user already submitted the form
    $check_sql = "SELECT * FROM application_form WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert(' Tayari umeshatuma maombi. Hakuruhusiwi kutuma tena.'); window.location.href='applicant_dashboard.php';</script>";
        exit;
    }

    // Upload directory
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
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $nationality = $_POST['uraia'];
    $shehia = $_POST['shehia'];
    $district = $_POST['wilaya'];
    $phone = $_POST['phone'];
    $employed = $_POST['umeajiriwa'];
    $workplace = isset($_POST['sehemu']) ? $_POST['sehemu'] : null;
    $marital_status = $_POST['ndoa'];
    $disability = $_POST['disability'];
    $education_level = $_POST['elimu'];

    // Insert data
    $sql = "INSERT INTO application_form (
    user_id, full_name, email, gender, dob, nationality, shehia, district, phone,
    employed, workplace, marital_status, disability, id_picture, education_level
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "issssssssssssss",///this asign how many value neede
        $user_id, $name,$email, $gender, $dob, $nationality, $shehia, $district, $phone,
        $employed, $workplace, $marital_status, $disability, $zanid_path, $education_level
    );

    if ($stmt->execute()) {
        echo "<script>alert(' Maombi yako yametumwa kikamilifu!'); window.location.href='applicant_dashboard.php';</script>";
    } else {
        echo "<script>alert(' Kuna tatizo wakati wa kutuma maombi.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../student/index.php");
    exit;
}
?>
