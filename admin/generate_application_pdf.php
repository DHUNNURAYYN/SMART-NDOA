<?php
ob_start();

require_once('../TCPDF-main/tcpdf.php');
include '../connection.php';
include '../session_check.php';

if (!isset($_GET['id'])) {
    die("No ID provided.");
}

$form_id = $_GET['id'];

// Fetch applicant data
$sql = "SELECT * FROM application_form WHERE form_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $form_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Application not found.");
}

$form = $result->fetch_assoc();

// === Setup PDF ===
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('SMART NDOA SYSTEM');
$pdf->SetAuthor('Mufti Office Zanzibar');
$pdf->SetTitle('Approved Application - ' . $form['full_name']);
$pdf->SetMargins(20, 30, 20);
$pdf->AddPage();

// === Add Logo and Header ===
$logoPath = '../assets/logo.png'; // make sure this path exists
if (file_exists($logoPath)) {
    $pdf->Image($logoPath, 15, 10, 25); // (x, y, width)
}
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'OFFICE OF THE GRAND MUFTI - ZANZIBAR', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 8, 'SMART NDOA MANAGEMENT SYSTEM', 0, 1, 'C');
$pdf->Ln(10); // space

// === Title ===
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 12, 'APPLICATION APPROVAL FORM', 0, 1, 'C');
$pdf->Ln(5);

// === Applicant Info ===
$pdf->SetFont('helvetica', '', 12);
$html = '
<table cellpadding="6">
    <tr><td><b>Full Name:</b></td><td>' . $form['full_name'] . '</td></tr>
    <tr><td><b>Gender:</b></td><td>' . $form['gender'] . '</td></tr>
    <tr><td><b>Date of Birth:</b></td><td>' . $form['dob'] . '</td></tr>
    <tr><td><b>Nationality:</b></td><td>' . $form['nationality'] . '</td></tr>
    <tr><td><b>District:</b></td><td>' . $form['district'] . '</td></tr>
    <tr><td><b>Shehia:</b></td><td>' . $form['shehia'] . '</td></tr>
    <tr><td><b>Phone:</b></td><td>' . $form['phone'] . '</td></tr>
    <tr><td><b>Employed:</b></td><td>' . $form['employed'] . '</td></tr>
    <tr><td><b>Workplace:</b></td><td>' . $form['workplace'] . '</td></tr>
    <tr><td><b>Marital Status:</b></td><td>' . $form['marital_status'] . '</td></tr>
    <tr><td><b>Disability:</b></td><td>' . ($form['disability'] ?: 'None') . '</td></tr>
    <tr><td><b>Education Level:</b></td><td>' . $form['education_level'] . '</td></tr>
    <tr><td><b>Status:</b></td><td><b style="color:green;">Approved</b></td></tr>
</table>
';
$pdf->writeHTML($html, true, false, true, false, '');

// === Optional ID Picture ===
$id_image_path = '../applicant/' . $form['id_picture'];
if (file_exists($id_image_path)) {
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Attached ID Copy', 0, 1, 'L');
    $pdf->Image($id_image_path, '', '', 45, 35, '', '', 'T', false, 300);
}

// === Signature Section ===
$pdf->Ln(15);
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 8, 'Approved by:', 0, 1, 'L');
$pdf->Cell(0, 8, '___________________________', 0, 1, 'L');
$pdf->Cell(0, 8, 'Officer-in-Charge, Mufti Office', 0, 1, 'L');

ob_end_clean(); // ✅ Clean buffer
$pdf->Output("Approved_Application_{$form['full_name']}.pdf", 'D');
exit;
