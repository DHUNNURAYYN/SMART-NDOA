<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/PHPMailer.php';
require 'src/SMTP.php';
require 'src/Exception.php';

function sendNotification($to, $name, $status) {
    $mail = new PHPMailer(true);
    try {
        // Server Settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'othmanhamd130@gmail.com'; // 🔁 Gmail yako
        $mail->Password = 'jncc head mupm xqyh';     // 🔁 App Password yako
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipient
        $mail->setFrom('othmanhamd130@gmail.com', 'SMART SYSTEM');
        $mail->addAddress($to, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Status ya Maombi Yako';

        if ($status == 'approved') {
            $mail->Body = "Habari <b>$name</b>,<br><br>Maombi yako yamekubaliwa ✅.<br>Karibu kwenye mafunzo!";
        } else {
            $mail->Body = "Habari <b>$name</b>,<br><br>Maombi yako yamekataliwa ❌.<br>Jaribu tena au wasiliana nasi.";
        }

        $mail->send();
        // echo "✅ Email sent";
    } catch (Exception $e) {
        echo "❌ Email not sent. Error: {$mail->ErrorInfo}";
    }
}
?>
