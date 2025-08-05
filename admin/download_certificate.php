<?php
ob_start(); // Start output buffering to prevent TCPDF errors

require_once('../TCPDF-main/tcpdf.php');
include '../connection.php';

if (!isset($_GET['user_id'])) {
    die("User ID missing.");
}

$user_id = (int)$_GET['user_id'];

// Fetch user info
$sql = "SELECT full_name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) die("User not found.");

$full_name = strtoupper($user['full_name']);
$completion_date = date("F j, Y");

// === TCPDF SETUP ===
$pdf = new TCPDF('L', 'mm', 'A3', true, 'UTF-8', false); // A3 Landscape
$pdf->SetCreator('SMART NDOA SYSTEM');
$pdf->SetAuthor('Mufti Office Zanzibar');
$pdf->SetTitle('Marriage Ethics Certificate');
$pdf->SetMargins(20, 20, 20);
$pdf->AddPage();

// Draw Dark Green Border
$pdf->SetDrawColor(0, 128, 0);
$pdf->SetLineWidth(1);
$pdf->Rect(10, 10, 400, 277); // Fits A3 page

// Centered Logo
$pdf->Image('../logo/logo.jpg', 185, 20, 50); // X=185 centers logo

// Title
$pdf->Ln(60);
$pdf->SetFont('times', 'B', 28);
$pdf->Cell(0, 15, "THE GRAND MUFTI'S OFFICE OF ZANZIBAR", 0, 1, 'C');

$pdf->SetFont('times', 'B', 26);
$pdf->Cell(0, 15, 'CERTIFICATE OF COMPLETION', 0, 1, 'C');

// Green underline
$pdf->SetDrawColor(0, 128, 0);
$pdf->SetLineWidth(0.6);
$pdf->Line(70, $pdf->GetY(), 370, $pdf->GetY());
$pdf->Ln(12);

// Certificate Body
$pdf->SetFont('times', '', 20);
$html = "
<div style=\"text-align: center;\">
    This is to certify that
    <span style=\"font-size: 28px; color:rgb(0,128,0);\"><b>$full_name</b></span><br><br>
    has successfully completed the<br><br>
    <span style=\"font-size: 24px; color:rgb(0,128,0);\"><b>Marriage Ethics Training</b></span><br><br>
    conducted by the Office of the Mufti
    as part of the official marriage preparation program.<br><br>
    Completion Date: <span style=\"color:rgb(0,128,0);\"><b>$completion_date</b></span>
</div>";
$pdf->writeHTML($html, true, false, true, false, '');

// Signature
$pdf->SetY(245); // Bottom of A3
$pdf->Image('../images/signature.png', 300, 245, 50); // Positioned bottom-right

// Signature text
$pdf->SetFont('times', '', 16);
$pdf->SetY(265);
$pdf->Cell(0, 10, 'Signature - Office of the Mufti', 0, 0, 'R');

// Output PDF
ob_end_clean();
$pdf->Output("Certificate_$full_name.pdf", 'D'); // Force download
exit;
?>
