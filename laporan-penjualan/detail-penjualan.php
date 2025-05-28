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

$id = $_GET['id'];
$tgl = $_GET['tgl'];

// Ambil data detail penjualan
$penjualan = getData("SELECT * FROM jual_detail WHERE no_jual = '$id'");

// Ambil data header penjualan (untuk total, bayar, dan kembalian)
$header_penjualan = getData("SELECT total, jml_bayar, kembalian FROM jual_head WHERE no_jual = '$id'")[0];
$total = $header_penjualan['total'];
$jml_bayar = $header_penjualan['jml_bayar'];
$kembalian = $header_penjualan['kembalian'];
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Detail Penjualan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= $main_url ?>laporan-penjualan">Penjualan</a></li>
            <li class="breadcrumb-item active">Penjualan</li>
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
                <button type="button" class="btn btn-sm btn-outline-primary float-right ml-1" data-toggle="modal" data-target="#mdlPeriodejual" onclick="printDoc()">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <button type="button" class="btn btn-sm btn-success float-right"><?= $tgl ?></button>
                <button type="button" class="btn btn-sm btn-warning float-right mr-1"><?= $id ?></button>
            </div>
            <div class="card-body table-responsive p-3">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Harga jual</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Jumlah Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $no = 1;
                        foreach($penjualan as $jual){?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $jual['barcode'] ?></td>
                            <td><?= $jual['nama_brg'] ?></td>
                            <td><?= number_format($jual['harga_jual'],0,",",".") ?></td>
                            <td class="text-center"><?= $jual['qty'] ?></td>
                            <td class="text-center"><?= number_format($jual['jml_harga'],0,",",".") ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total Harga:</th>
                        <th class="text-center"><?= number_format($total,0,",",".") ?></th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right">Jumlah Bayar:</th>
                        <th class="text-center"><?= number_format($jml_bayar,0,",",".") ?></th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-right">Kembalian:</th>
                        <th class="text-center"><?= number_format($kembalian,0,",",".") ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        </div>
    </div>
  </section>
</div>

<script>
  function printDoc() {
    window.open("../report/r-struk.php?id=<?= $id ?>", "_blank", "width=900,height=600,left=100");
  }
</script>

<?php
require "../templatess/footer.php";
?>