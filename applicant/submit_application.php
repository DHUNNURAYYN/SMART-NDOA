<?php
include '../connection.php';
include '../session_check.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';

// Function to send email notification
function sendNotification($to, $name, $status) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'othmanhamad130@gmail.com';
        $mail->Password   = 'bczw ggnp dcmt wpjh'; // Your app password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('othmanhamad130@gmail.com', 'SMART NDOA SYSTEM');
        $mail->addAddress($to, $name);
        $mail->addReplyTo('othmanhamad130@gmail.com', 'SMART NDOA SYSTEM');

        $mail->isHTML(true);
        $mail->Subject = 'Status ya Maombi Yako';

       $mail->Body = "
     <div style='font-family: Arial, sans-serif; color: #333;'>
        <h2 style='color: #006400;'>Maombi Yamepokelewa</h2>
        <p>Hongera ndugu <b>$name</b>, umefanikiwa kujaza fomu ya maombi ya kujiunga na mafunzo ya maadili ya ndoa.</p>
        <p>Subiri ujumbe kupitia <b>barua pepe yako (Gmail)</b> mara tu utakapokubaliwa rasmi na kuanza masomo yetu.</p>

        <hr style='margin: 30px 0; border: 1px dashed #ccc;'>

        <p><b>Masomo:</b> Ndoa kwa ukamilifu wake</p>
        <p><b>Ada:</b> Bure (wiki 10)</p>
        <p><b>Mafunzo:</b> Jumamosi & Jumapili, saa 2:00 - 6:00</p>

        <p style='margin-top: 30px;'>Tafadhali usisite kuwasiliana nasi kwa msaada wowote.</p>
        <p>Wako kwa huduma, <br><b>SMART NDOA SYSTEM</b></p>
    </div>
    ";


        $mail->send();
    } catch (Exception $e) {
        error_log("Email error: " . $mail->ErrorInfo);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user'];

    // Check if user already submitted
    $check_sql = "SELECT * FROM application_form WHERE user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Tayari umeshatuma maombi.'); window.location.href='applicant_dashboard.php';</script>";
        exit;
    }

    // Upload
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

    $zanid = $_FILES["zanid"]["name"];
    $zanid_tmp = $_FILES["zanid"]["tmp_name"];
    $zanid_path = $target_dir . time() . '_' . basename($zanid);
    move_uploaded_file($zanid_tmp, $zanid_path);

    // Get form data
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

    // Insert into database
    $sql = "INSERT INTO application_form (
        user_id, full_name, email, gender, dob, nationality, shehia, district, phone,
        employed, workplace, marital_status, disability, id_picture, education_level
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "issssssssssssss",
        $user_id, $name, $email, $gender, $dob, $nationality, $shehia, $district,
        $phone, $employed, $workplace, $marital_status, $disability, $zanid_path, $education_level
    );

    if ($stmt->execute()) {
        sendNotification($email, $name, "pending");
        
        echo "<script>alert(' Maombi yako yametumwa kikamilifu!'); window.location.href='application_success.php';</script>";

    } else {
        echo "<script>alert('Tatizo limetokea.'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../student/index.php");
    exit;
}
?>
