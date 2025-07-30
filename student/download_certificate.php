<?php
// Define missing constants if not already defined
if (!defined('CURLOPT_CONNECTTIMEOUT')) {
    define('CURLOPT_CONNECTTIMEOUT', 0);
}
if (!defined('CURLOPT_TIMEOUT')) {
    define('CURLOPT_TIMEOUT', 0);
}
if (!defined('CURLOPT_RETURNTRANSFER')) {
    define('CURLOPT_RETURNTRANSFER', 1);
}
if (!defined('CURLOPT_URL')) {
    define('CURLOPT_URL', 10002);
}
if (!defined('CURLOPT_SSL_VERIFYPEER')) {
    define('CURLOPT_SSL_VERIFYPEER', 64);
}

require_once('../TCPDF-main/tcpdf.php');
include '../connection.php';
include '../session_check.php';

// Get logged-in user ID
$user_id = $_SESSION['user'];

// Check attendance percentage
$total_sessions = 1;
$sql = "SELECT COUNT(*) as present_days FROM attendances 
        WHERE user_id = ? AND status = 'Present'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$present_days = $row['present_days'];
$attendance_percentage = ($present_days / $total_sessions) * 100;

// Stop if not eligible
if ($attendance_percentage < 75) {
    die("⚠️ Sorry, you are not eligible for a certificate. Please wait for another semester.");
}

// Get student info
$sql2 = "SELECT full_name FROM users WHERE user_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$result2 = $stmt2->get_result();
$user = $result2->fetch_assoc();

$full_name = strtoupper($user['full_name']);
$completion_date = date("F j, Y"); // e.g. July 29, 2025

// === TCPDF START ===
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('SMART NDOA SYSTEM');
$pdf->SetAuthor('Mufti Office Zanzibar');
$pdf->SetTitle('Certificate of Completion');
$pdf->SetMargins(20, 20, 20);
$pdf->AddPage();

// Add border
$pdf->SetLineWidth(1);
$pdf->Rect(10, 10, 277, 190); // Full border on A4 landscape

// Logo placeholder
$pdf->Image('../images/logo.png', 20, 15, 30); // <-- Add your logo here

// Certificate title
$pdf->SetFont('times', 'B', 24);
$pdf->Cell(0, 20, 'OFISI YA MUFTI MKUU WA ZANZIBAR', 0, 1, 'C');
$pdf->SetFont('times', '', 20);
$pdf->Cell(0, 10, 'CERTIFICATE OF COMPLETION', 0, 1, 'C');
$pdf->Ln(10);

// Body content
$pdf->SetFont('times', '', 16);
$pdf->MultiCell(0, 10, 
    "This is to certify that\n\n" . 
    "🎓 $full_name\n\n" . 
    "has successfully completed the **Marriage Ethics Training** conducted by the Office of the Mufti during the official program. " .
    "This training covered important aspects of Islamic marital life and responsibilities.\n\n" .
    "Completion Date: $completion_date", 
    0, 'C');

// Signature placeholder
$pdf->Image('../images/signature.png', 210, 140, 50); // <-- Add your signature
$pdf->SetY(185);
$pdf->SetFont('times', '', 12);
$pdf->Cell(0, 10, 'Signature - Office of the Mufti', 0, 0, 'R');

// Download the PDF
$pdf->Output("Certificate_$full_name.pdf", 'D'); // Force download
?>
