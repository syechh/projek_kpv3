<?php
session_start();

if (!isset($_SESSION["ssLoginPOS"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";

// Validasi parameter
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Parameter ID transaksi tidak valid");
}

$id = $_GET['id'];

// Ambil data header pembelian
$dataBeli = getData("SELECT * FROM beli_head WHERE no_beli = '$id'");
if (empty($dataBeli)) {
    die("Error: Data pembelian tidak ditemukan");
}
$dataBeli = $dataBeli[0];

// Ambil nama supplier berdasarkan nama supplier di beli_head
$supplierName = 'Tidak Diketahui';
if (!empty($dataBeli['suplier'])) {
    $supplierData = getData("SELECT nama FROM supplier WHERE id_supplier = '{$dataBeli['suplier']}'");
    if (!empty($supplierData)) {
        $supplierName = $supplierData[0]['nama'];
    }
}

// Ambil data detail pembelian
$itemBeli = getData("SELECT * FROM beli_detail WHERE no_beli = '$id'");
if (empty($itemBeli)) {
    die("Error: Item pembelian tidak ditemukan");
}

// Format tanggal
$tglBeli = date('d-m-Y H:i:s', strtotime($dataBeli['tgl_beli']));
$username = isset(userLogin()['username']) ? userLogin()['username'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Struk Pembelian - <?= htmlspecialchars($id) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 240px;
            margin: 0 auto;
            padding: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        .item-row {
            border-bottom: 1px dotted #000;
            padding: 3px 0;
        }
        .item-name {
            font-weight: bold;
        }
        .total-section {
            border-top: 1px dotted #000;
            border-bottom: 1px solid #000;
            padding: 5px 0;
            margin-top: 5px;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 11px;
        }
        .supplier-info {
            margin: 5px 0;
            font-size: 11px;
        }
    </style>
</head>
<body>

<div class="header">
    <h3 style="margin: 5px 0; font-size: 14px;">TOKO BANGUNAN MUTIARA</h3>
    <p style="margin: 3px 0; font-size: 10px;">
        Jl. Serua Raya, Serua, Kec. Bojongsari<br />
        Kota Depok, Jawa Barat 16517
    </p>
</div>

<div class="supplier-info">
    <p style="margin: 2px 0;">
        <strong>No. Pembelian:</strong> <?= htmlspecialchars($id) ?><br />
        <strong>Tanggal:</strong> <?= $tglBeli ?><br />
        <strong>Operator:</strong> <?= htmlspecialchars($username) ?><br />
        <strong>Supplier:</strong> <?= htmlspecialchars($supplierName) ?>
    </p>
</div>

<div class="items">
    <?php foreach ($itemBeli as $item) { ?>
    <div class="item-row">
        <div class="item-name"><?= htmlspecialchars($item['nama_brg']) ?></div>
        <div style="display: flex; justify-content: space-between;">
            <span>x <?= number_format($item['harga_beli'],0,',','.') ?></span>
            <span><?= $item['qty'] ?> x</span>
            <span><?= number_format($item['jml_harga'],0,',','.') ?></span>
        </div>
    </div>
    <?php } ?>
</div>

<div class="total-section">
    <div style="display: flex; justify-content: space-between;">
        <span>Total Pembelian:</span>
        <span><strong><?= number_format($dataBeli['total'],0,',','.') ?></strong></span>
    </div>
</div>

<div class="footer">
    Catatan Pembelian Barang<br />
    Barang yang sudah diterima menjadi tanggung jawab toko
</div>

<script>
    window.print();
    setTimeout(function () {
        window.close();
    }, 1000);
</script>

</body>
</html>
