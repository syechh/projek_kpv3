<?php
session_start();

if (!isset($_SESSION["ssLoginPOS"])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";

// Validasi parameter
if (!isset($_GET['nota']) || empty($_GET['nota'])) {
    die("Error: Parameter nota transaksi tidak valid");
}

$nota = $_GET['nota'];

// Ambil data header penjualan
$dataJual = getData("SELECT * FROM jual_head WHERE no_jual = '$nota'");
if (empty($dataJual)) {
    die("Error: Data penjualan tidak ditemukan");
}
$dataJual = $dataJual[0];

// Ambil data detail penjualan
$itemJual = getData("SELECT * FROM jual_detail WHERE no_jual = '$nota'");
if (empty($itemJual)) {
    die("Error: Item penjualan tidak ditemukan");
}

// Format tanggal
$tglJual = date('d-m-Y H:i:s', strtotime($dataJual['tgl_jual']));
$username = isset(userLogin()['username']) ? userLogin()['username'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Penjualan - <?= htmlspecialchars($nota) ?></title>
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
        .customer-info {
            margin: 5px 0;
            font-size: 11px;
        }
        .item-detail {
            display: flex;
            justify-content: space-between;
        }
        .keterangan {
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px dashed #000;
            font-size: 11px;
        }
    </style>
</head>
<body>

<div class="header">
    <h3 style="margin: 5px 0; font-size: 14px;">TOKO BANGUNAN MUTIARA</h3>
    <p style="margin: 3px 0; font-size: 10px;">
        Jl. Serua Raya, Serua, Kec. Bojongsari<br>
        Kota Depok, Jawa Barat 16517
    </p>
</div>

<div class="customer-info">
    <p style="margin: 2px 0;">
        <strong>No. Nota:</strong> <?= htmlspecialchars($nota) ?><br>
        <strong>Tanggal:</strong> <?= $tglJual ?><br>
        <strong>Kasir:</strong> <?= htmlspecialchars($username) ?><br>
        <?php if(!empty($dataJual['customer'])): ?>
        <strong>Customer:</strong> <?= htmlspecialchars($dataJual['customer']) ?>
        <?php endif; ?>
    </p>
</div>

<div class="items">
    <?php foreach ($itemJual as $item) { ?>
    <div class="item-row">
        <div class="item-name"><?= htmlspecialchars($item['nama_brg']) ?></div>
        <div class="item-detail">
            <span><?= $item['qty'] ?> x <?= number_format($item['harga_jual'],0,',','.') ?></span>
            <span><?= number_format($item['jml_harga'],0,',','.') ?></span>
        </div>
    </div>
    <?php } ?>
</div>

<div class="total-section">
    <div style="display: flex; justify-content: space-between;">
        <span>Total:</span>
        <span><strong><?= number_format($dataJual['total'],0,',','.') ?></strong></span>
    </div>
    <div style="display: flex; justify-content: space-between;">
        <span>Bayar:</span>
        <span><strong><?= number_format($dataJual['jml_bayar'],0,',','.') ?></strong></span>
    </div>
    <div style="display: flex; justify-content: space-between;">
        <span>Kembali:</span>
        <span><strong><?= number_format($dataJual['kembalian'],0,',','.') ?></strong></span>
    </div>
</div>

<?php if(!empty($dataJual['keterangan'])): ?>
<div class="keterangan">
    <strong>Keterangan:</strong><br>
    <?= nl2br(htmlspecialchars($dataJual['keterangan'])) ?>
</div>
<?php endif; ?>

<div class="footer">
    Terima kasih sudah berbelanja<br>
    Barang yang sudah dibeli tidak dapat dikembalikan
</div>

<script>
    window.print();
    setTimeout(function(){
        window.close();
    }, 1000);
</script>

</body>
</html>