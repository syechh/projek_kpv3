<?php
session_start();

if(!isset($_SESSION["ssLoginPOS"])){
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-barang.php";

$title = "Laporan - Toko Bangunan Mutiara";
require "../templatess/header.php";
require "../templatess/navbar.php";
require "../templatess/sidebar.php";

// Validasi parameter GET
if(!isset($_GET['id']) || !isset($_GET['tgl'])) {
    header("location: ../laporan-pembelian");
    exit();
}

$id = $_GET['id'];
$tgl = $_GET['tgl'];

// Ambil data header pembelian
$header_pembelian = getData("SELECT bh.*, s.nama as nama_supplier 
                           FROM beli_head bh 
                           JOIN supplier s ON bh.suplier = s.id_supplier 
                           WHERE bh.no_beli = '$id'");

if(empty($header_pembelian)) {
    echo "<script>
            alert('Data pembelian tidak ditemukan');
            window.location.href = '../laporan-pembelian';
          </script>";
    exit();
}

$header = $header_pembelian[0];
$total = $header['total'];
$supplier = $header['nama_supplier'];

// Ambil detail pembelian dengan join ke tabel barang untuk mendapatkan satuan
$pembelian = getData("SELECT bd.*, b.satuan 
                     FROM beli_detail bd 
                     JOIN barang b ON bd.kode_brg = b.id_barang 
                     WHERE bd.no_beli = '$id'");
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detail Pembelian</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= $main_url ?>laporan-pembelian">Pembelian</a></li>
            <li class="breadcrumb-item active">Detail Pembelian</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

  <section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list fa-sm"></i> Rincian Barang
                </h3>
                <div class="float-right">
                    <button type="button" class="btn btn-sm btn-outline-primary ml-1" onclick="printDoc()">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                    <button type="button" class="btn btn-sm btn-success"><?= $tgl ?></button>
                    <button type="button" class="btn btn-sm btn-warning mr-1"><?= $id ?></button>
                    <button type="button" class="btn btn-sm btn-info">Supplier: <?= $supplier ?></button>
                </div>
            </div>
            <div class="card-body table-responsive p-3">
                <?php if(empty($pembelian)): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Tidak ada barang dalam pembelian ini
                    </div>
                <?php else: ?>
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Harga Beli</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Satuan</th>
                            <th class="text-center">Jumlah Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach($pembelian as $beli): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $beli['kode_brg'] ?></td>
                            <td><?= $beli['nama_brg'] ?></td>
                            <td><?= number_format($beli['harga_beli'],0,",",".") ?></td>
                            <td class="text-center"><?= $beli['qty'] ?></td>
                            <td class="text-center"><?= $beli['satuan'] ?></td>
                            <td class="text-center"><?= number_format($beli['jml_harga'],0,",",".") ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-right">Total Harga:</th>
                            <th class="text-center"><?= number_format($total,0,",",".") ?></th>
                        </tr>
                    </tfoot>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
  </section>

</div>

<script>
   function printDoc() {
        window.open("../report/r-struk-sup.php?id=<?= $id ?>", "_blank", "width=900,height=600,left=100");
   }
</script>

<?php
require "../templatess/footer.php";
?>