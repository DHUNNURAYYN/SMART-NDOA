<?php
ob_start();

require_once('../TCPDF-main/tcpdf.php');
include '../connection.php';
include '../session_check.php';

if (!isset($_GET['id'])) {
    die("Hakuna ID iliyotolewa.");  // No ID provided
}

$form_id = $_GET['id'];

// Fetch applicant data
$sql = "SELECT * FROM application_form WHERE form_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $form_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Maombi hayajapatikana.");  // Application not found
}

$form = $result->fetch_assoc();

// Initialize PDF
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator('MFUMO SMART NDOA');
$pdf->SetAuthor('Ofisi ya Mufti Mkuu Zanzibar');
$pdf->SetTitle('Maombi Yaliyokubaliwa - ' . $form['full_name']);
$pdf->SetMargins(20, 25, 20);
$pdf->AddPage();

// Logo centered horizontally
$logoPath = '../logo/logo.jpg';
if (file_exists($logoPath)) {
    $pageWidth = $pdf->getPageWidth();
    $logoWidth = 35;  // desired logo width in mm
    $x = ($pageWidth - $logoWidth) / 2;
    $pdf->Image($logoPath, $x, 10, $logoWidth);
}

$pdf->Ln(25);

// Header text with color
$pdf->SetFont('dejavusans', 'B', 18);
$pdf->SetTextColor(0, 51, 102);
$pdf->Cell(0, 10, 'OFISI YA MUFTI MKUU - ZANZIBAR', 0, 1, 'C');

$pdf->SetFont('dejavusans', '', 14);
$pdf->SetTextColor(0, 102, 153);
$pdf->Cell(0, 8, 'MFUMO WA USIMAMIZI WA NDOA', 0, 1, 'C');

$pdf->Ln(10);

// Title with stronger color
$pdf->SetFont('dejavusans', 'B', 20);
$pdf->SetTextColor(0, 70, 127);
$pdf->Cell(0, 12, 'FOMU YA KUBALIKIWA KWA MAOMBI', 0, 1, 'C');
$pdf->Ln(8);

$pdf->SetTextColor(0, 0, 0);
$pdf->SetFont('dejavusans', '', 12);

// Applicant info table with borders and color stripes
$html = '
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        font-family: dejavusans;
        font-size: 12pt;
    }
    th, td {
        border: 1px solid #B0C4DE;
        padding: 6px 8px;
    }
    tr:nth-child(even) {
        background-color: #F0F8FF;
    }
    b {
        color: #003366;
    }
    .status {
        color: green;
        font-weight: bold;
    }
</style>

<table>
    <tr><td><b>Jina Kamili:</b></td><td>' . htmlspecialchars($form['full_name']) . '</td></tr>
    <tr><td><b>Jinsia:</b></td><td>' . htmlspecialchars($form['gender']) . '</td></tr>
    <tr><td><b>Tarehe ya Kuzaliwa:</b></td><td>' . htmlspecialchars($form['dob']) . '</td></tr>
    <tr><td><b>Namba ya Simu:</b></td><td>' . htmlspecialchars($form['phone']) . '</td></tr>
    <tr><td><b>Hali ya Ndoa:</b></td><td>' . htmlspecialchars($form['marital_status']) . '</td></tr>
    <tr><td><b>Je, umeajiriwa?:</b></td><td>' . htmlspecialchars($form['employed']) . '</td></tr>
    <tr><td><b>Elimu:</b></td><td>' . htmlspecialchars($form['education_level']) . '</td></tr>
    <tr><td><b>Hali ya Maombi:</b></td><td class="status">Yamekubaliwa</td></tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

// ID Picture (optional)
$id_image_path = '../applicant/' . $form['id_picture'];
if (file_exists($id_image_path)) {
    $pdf->Ln(12);
    $pdf->SetFont('dejavusans', 'B', 14);
    $pdf->SetTextColor(0, 51, 102);
    $pdf->Cell(0, 10, 'Nakili ya Kitambulisho Kilichowekwa', 0, 1, 'L');
    $pdf->Image($id_image_path, 20, $pdf->GetY(), 50, 40, '', '', 'T', false, 300);
}

// Signature Section
$pdf->Ln(50);
$pdf->SetFont('dejavusans', '', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 8, 'Imekubaliwa na:', 0, 1, 'L');
$pdf->Ln(10);
$pdf->Cell(0, 8, '___________________________', 0, 1, 'L');
$pdf->Cell(0, 8, 'Afisa Mkuu, Ofisi ya Mufti', 0, 1, 'L');

ob_end_clean();
$pdf->Output("Maombi_Yaliyokubaliwa_{$form['full_name']}.pdf", 'D');
exit;
?>
