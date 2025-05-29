<?php

require "../config/config.php";
require "../config/functions.php";
require('../assets/fpdf/vendor/autoload.php');

$stockBrg = getData("SELECT * FROM barang");

$pdf = new FPDF();


$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(190,10,'Laporan Stock Barang',0,1,'C');

$pdf->SetFont('Arial','B',12);
$pdf->Cell(190,10,'','B',1);

// Header kolom
$pdf->Cell(10,10,'No',0,0,'C');
$pdf->Cell(30,10,'Kode',0,0);
$pdf->Cell(45,10,'Nama Barang',0,0);
$pdf->Cell(20,10,'Stock',0,0,'C');
$pdf->Cell(20,10,'Satuan',0,0,'C');
$pdf->Cell(30,10,'Harga',0,0,'R');
$pdf->Cell(35,10,'Total',0,1,'R');

$pdf->Cell(190,1,'','T',1);

$pdf->SetFont('Arial','',11);
$no = 1;
$grandTotal = 0;

foreach($stockBrg as $stock) {
    $harga = $stock['harga_jual'];
    $total = $stock['stock'] * $harga;
    $grandTotal += $total;

    $pdf->Cell(10,8,$no++,0,0,'C');
    $pdf->Cell(30,8,$stock['id_barang'],0,0);
    $pdf->Cell(45,8,$stock['nama_barang'],0,0);
    $pdf->Cell(20,8,$stock['stock'],0,0,'C');
    $pdf->Cell(20,8,$stock['satuan'],0,0,'C');
    $pdf->Cell(30,8,'Rp '.number_format($harga, 0, ',', '.'),0,0,'R');
    $pdf->Cell(35,8,'Rp '.number_format($total, 0, ',', '.'),0,1,'R');
}

$pdf->Cell(190,1,'','T',1);

// Grand total
$pdf->SetFont('Arial','B',12);
$pdf->Cell(155,10,'Total Keseluruhan',0,0,'R');
$pdf->Cell(35,10,'Rp '.number_format($grandTotal, 0, ',', '.'),0,1,'R');

$pdf->Output();
?>
