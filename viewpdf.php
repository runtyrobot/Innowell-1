<?php
require_once('fpdf/fpdf.php');

if ($_GET['pay_id'] && $_GET['pay_fname'] && $_GET['pay_lname']) {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->setFont('Arial', 'B', 16);

    $pdf->Cell(10, 10, 'Faktura ' . $_GET['pay_id']);
    $pdf->Cell(-10, 40, $_GET['pay_fname'] . " " . $_GET['pay_lname']);

    $pdf->output();
} else http_response_code(400); // Missing Params - Throw Error