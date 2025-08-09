<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';

function sendNotification($to, $name, $status, $conn) {
    // Fetch the latest start_date from training_schedule table
    $startDate = "TBD"; // default if none found

    $sql = "SELECT start_date FROM training_schedule ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $startDate = $row['start_date'];
        // Format date nicely, e.g. 5/5/2025
        $startDate = date("j/n/Y", strtotime($startDate));
    }

    try {
        $mail = new PHPMailer(true);

        // SMTP settings (Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'othmanhamad130@gmail.com'; // ✅ Correct Gmail
        $mail->Password   = 'bczw ggnp dcmt wpjh';      // 🔑 App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Email settings
        $mail->setFrom('othmanhamad130@gmail.com', 'SMART NDOA SYSTEM');
        $mail->addAddress($to, $name);
        $mail->addReplyTo('othmanhamad130@gmail.com', 'SMART NDOA SYSTEM');

        $mail->isHTML(true);
        $mail->Subject = 'Status ya Maombi Yako';

        // Email body
        if ($status == 'approved') {
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; color: #333;'>
                    <h2 style='color: #006400;'>Hongera $name!</h2>
                    <p>Tunapenda kukufahamisha kuwa <b>maombi yako yamekubaliwa rasmi</b> kwa ajili ya kushiriki mafunzo ya maadili ya ndoa yanayoendeshwa na <b>SMART NDOA SYSTEM</b>.</p>
                    
                    <p>Yatakayo anza Tarehe<b> $startDate <b/>siku ya <b>Jumamosi </b>hapo <b>Jamii Zinjibar</b></p>

                    <p>Karibu kwenye safari ya kujifunza kuhusu ndoa kwa ukamilifu wake. Mafunzo haya ni muhimu sana kwa maandalizi ya maisha ya ndoa yenye mafanikio, hekima na maadili mema.</p>

                    <hr style='margin: 30px 0; border: 1px dashed #ccc;'>

                    <p><b>Masomo:</b> Ndoa kwa ukamilifu wake</p>
                    <p><b>Ada:</b> Bure (wiki 10)</p>
                    <p><b>Mafunzo:</b> Jumamosi & Jumapili, saa 2:00 - 6:00</p>
                    <p><b>Mahali:</b> MASJID JAMII ZINJIBR</p>

                    <hr style='margin: 30px 0; border: 1px dashed #ccc;'>

                    <p>Tafadhali hakikisha unaendelea kuangalia <b>barua pepe yako</b> kwa taarifa zaidi .</p>

                    <p style='margin-top: 25px;'>Tunatarajia kukuona hivi karibuni katika mafunzo yetu. Asante kwa kuchagua SMART NDOA SYSTEM.</p>
                    
                    <p>Wako katika huduma, <br><b>Dhunnurayyn Hamad Faki</b></p>
                </div>
            ";
        } else {
            $mail->Body = "
                <h3>Habari $name,</h3>
                <p>Maombi yako yamekataliwa .<br>Jaribu tena au wasiliana nasi kwa msaada zaidi.</p>
            ";
        }

        $mail->send();

    } catch (Exception $e) {
        echo " Email not sent. Error: {$mail->ErrorInfo}";
    }
}
