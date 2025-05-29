<?php
session_start();

if(!isset($_SESSION["ssLoginPOS"])){
  header("location: auth/login.php");
  exit();
}

require "config/config.php";
require "config/functions.php";

$title = "Dashboard - UD MUTIARA";
require "templatess/header.php";
require "templatess/navbar.php";
require "templatess/sidebar.php";

$level = userLogin()['level'];

// Data yang akan ditampilkan berdasarkan level
$users = $level == 1 ? getData("SELECT * FROM users") : []; // Hanya owner
$userNum = count($users);

$suppliers = ($level == 1 || $level == 2) ? getData("SELECT * FROM supplier") : []; // Owner dan admin
$supplierNum = count($suppliers);

$customers = ($level == 1 || $level == 2) ? getData("SELECT * FROM customer") : []; // Owner dan admin
$customerNum = count($customers);

$barang = ($level == 1 || $level == 2) ? getData("SELECT * FROM barang") : []; // Owner dan admin
$barangNum = count($barang);

$stockBrg = getData("SELECT * FROM barang");

// Get sales data for the chart
$periode = $_GET['periode'] ?? 'harian';
$penjualanData = getPenjualan($periode);
$labels = array_column($penjualanData, 'label');
$totals = array_map('intval', array_column($penjualanData, 'total_harian'));
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <?php if($level == 1): // Owner bisa melihat semua card ?>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= $userNum ?></h3>
                <p>Users</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="<?= $main_url ?>users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        <?php endif; ?>
        
        <?php if($level == 1 || $level == 2): // Owner dan admin bisa melihat supplier, customer, barang ?>
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= $supplierNum ?></h3>
                <p>Supplier</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-bus"></i>
              </div>
              <?php if($level == 1): ?>
              <a href="<?= $main_url ?>supplier" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              <?php endif; ?>
            </div>
          </div>
          
          <?php if($level == 1 || $level == 2 || $level == 3 || $level == 4): ?>
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?= $customerNum ?></h3>
                  <p>Customers</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-stalker"></i>
                </div>
                <?php if($level == 1): ?>
                <a href="<?= $main_url ?>customer" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                <?php endif; ?>
              </div>
            </div>
          <?php endif; ?>
          
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?= $barangNum ?></h3>
                <p>Item Barang</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-cart"></i>
              </div>
              <?php if($level == 1): ?>
              <a href="<?= $main_url ?>barang" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              <?php endif; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <!-- Info Stock Barang - Ditampilkan untuk semua level kecuali kasir -->
      
      <section class="content">
        <div class="container-fluid">
          <div class="card">
            <div class="card card-outline card-danger">
              <div class="card-header">
                <h5 class="card-title">Info Stock Barang</h5>
                  <h5><a href="stock" class="float-right" title="laporan stock"><i class="fas fa-arrow-right"></i></a></h5>
              </div>
              <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap" id="tblData">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Barang</th>
                      <th>Stock</th>
                      <th>Status</th>
                      <th>Supplier</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $stockMin = getData("
                      SELECT b.*, s.nama as supplier_nama, s.deskripsi as supplier_deskripsi
                      FROM barang b
                      LEFT JOIN supplier s ON s.deskripsi LIKE CONCAT('%', b.nama_barang, '%')
                      WHERE b.stock < b.stock_minimal
                    ");

                    $no = 1; 
                    foreach($stockMin as $min): 
                    ?>
                      <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $min['nama_barang'] ?></td>
                        <td class="text-center"><?= $min['stock'] ?></td>
                        <td>
                          <?php
                          if($min['stock'] == 0){
                                        echo '<span class="text-danger">Stock Habis</span>';
                                    } else if($min['stock'] < $min['stock_minimal']){
                                        echo '<span class="text-warning">Stock Kurang</span>';
                                    }
                          ?>
                        </td>
                        <td>
                          <?php 
                          if (!empty($min['supplier_nama'])) {
                            echo $min['supplier_nama'];
                          } else {
                            echo 'Tidak ada supplier yang terkait';
                          }
                          ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </section>
      <?php
          if(userLogin()['level'] != 3 && userLogin()['level'] != 4){
        ?>
      <!-- chart -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <!-- Line Chart -->
              <div class="col-md-6">
                <div class="card card-outline card-primary">
                  <div class="card-header">
                    <!-- Dropdown periode -->
                    <form method="GET" style="margin-bottom: 15px;">
                      <label for="periode">Pilih Periode:</label>
                      <select name="periode" id="periode" onchange="this.form.submit()" class="form-control col-sm-6">
                        <option value="harian" <?= $periode == 'harian' ? 'selected' : '' ?>>Harian</option>
                        <option value="mingguan" <?= $periode == 'mingguan' ? 'selected' : '' ?>>Mingguan</option>
                        <option value="bulanan" <?= $periode == 'bulanan' ? 'selected' : '' ?>>Bulanan</option>
                      </select>
                    </form>
                    <h5 class="card-title">Grafik Total Penjualan per <?= ucfirst($periode) ?></h5>
                  </div>
                  <div class="card-body">
                    <canvas id="lineChartPenjualan" height="200"></canvas>
                  </div>
                </div>
              </div>

              <!-- Pie Chart -->
              <div class="col-md-6">
                <div class="card card-outline card-success">
                  <div class="card-header">
                    <h5 class="card-title">Produk Terlaris</h5>
                  </div>
                  <div class="card-body">
                    <canvas id="pieChartProduk" height="200"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-md-4"></div>
            </div>
          </div>
        </section>

        <?php
        }
        ?>
    </div>
  </div>
</div>

<script>
  const ctx = document.getElementById('lineChartPenjualan').getContext('2d');
  const chartData = {
    labels: <?= json_encode($labels) ?>,
    datasets: [{
      label: 'Total Penjualan per <?= ucfirst($periode) ?>',
      data: <?= json_encode($totals) ?>,
      borderColor: 'rgba(75, 192, 192, 1)',
      tension: 0.4,
      fill: false,
    }]
  };

  new Chart(ctx, {
    type: 'line',
    data: chartData,
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  //script chart produk
  const pieCtx = document.getElementById('pieChartProduk').getContext('2d');
  const pieChart = new Chart(pieCtx, {
    type: 'doughnut',
    data: {
      labels: <?= json_encode($produkTerlarisLabels) ?>,
      datasets: [{
        label: 'Produk Terlaris',
        data: <?= json_encode($produkTerlarisData) ?>,
        backgroundColor: [
          '#FF6384', '#36A2EB', '#FFCE56',
          '#4BC0C0', '#9966FF', '#FF9F40',
          '#C9CBCF', '#46BFBD', '#FDB45C', '#949FB1'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              let total = context.dataset.data.reduce((a, b) => a + b, 0);
              let value = context.raw;
              let percent = ((value / total) * 100).toFixed(1);
              return `${context.label}: ${value} (${percent}%)`;
            }
          }
        }
      }
    }
  });
</script>


<?php 
require "templatess/footer.php";
?>

