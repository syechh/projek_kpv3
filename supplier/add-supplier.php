<?php
session_start();

if(!isset($_SESSION["ssLoginPOS"])){
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-supplier.php";

$title = "Tambah Supplier - Toko Bangunan Mutiara";
require "../templatess/header.php";
require "../templatess/navbar.php";
require "../templatess/sidebar.php";

// Ambil data produk untuk dropdown
$produk = getData("SELECT * FROM barang ORDER BY nama_barang");

$alert = '';
$error = '';

if(isset($_POST['simpan'])){
    // Validasi apakah ada produk yang dipilih
    if(!isset($_POST['produk']) || empty($_POST['produk']) || $_POST['produk'][0] == ""){
        $error = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="icon fas fa-exclamation-triangle"></i> Harap pilih minimal satu produk!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
    } else {
        // Simpan data supplier
        $idSupplier = insert($_POST);
        
        if($idSupplier > 0){
            // Simpan produk yang dipilih
            foreach($_POST['produk'] as $idBarang){
                if(!empty($idBarang)){
                    tambahProdukSupplier($idSupplier, $idBarang);
                }
            }
            
            header("Location: ../supplier?msg=added");
            exit();
        }
    }
}
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Supplier</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= $main_url ?>supplier">Supplier</a></li>
            <li class="breadcrumb-item active">Tambah Supplier</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <form action="" method="POST" id="formSupplier">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-plus fa-sm"></i> Tambah Supplier
            </h3>
            <button type="submit" name="simpan" class="btn btn-primary btn-sm float-right" id="btnSimpan">
              <i class="fas fa-save"></i> Simpan
            </button>
            <button type="reset" class="btn btn-danger btn-sm float-right mr-1">
              <i class="fas fa-times"></i> Reset
            </button>
          </div>
          <div class="card-body">
            <div class="row">
              <!-- Kolom Kiri - Form Supplier -->
              <div class="col-lg-6">
                <?= $error ?>
                <?= $alert ?>
                <div class="form-group">
                  <label for="name">Nama Supplier *</label>
                  <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama Supplier" autofocus autocomplete="off" required>
                </div>
                <div class="form-group">
                  <label for="telpon">Telpon *</label>
                  <input type="text" name="telpon" class="form-control" id="telpon" pattern="[0-9]{5,}" title="minimal 5 angka" placeholder="Nomer Telpon Supplier" required>
                </div>
                <div class="form-group">
                  <label for="ketr">Deskripsi *</label>
                  <textarea name="ketr" id="ketr" rows="1" class="form-control" placeholder="Keterangan Supplier" required></textarea>
                </div>
                <div class="form-group">
                  <label for="alamat">Alamat *</label>
                  <textarea name="alamat" id="alamat" rows="3" class="form-control" placeholder="Alamat Supplier" required></textarea>
                </div>
              </div>
              
              <!-- Kolom Kanan - Produk yang Dijual -->
              <div class="col-lg-6">
                <div class="form-group">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="mb-0">Produk yang Dijual *</label>
                    <button type="button" class="btn btn-sm btn-info" id="tambahProduk">
                      <i class="fas fa-plus"></i> Tambah Produk
                    </button>
                  </div>
                  <div id="daftarProduk" style="max-height: 300px; overflow-y: auto;">
                    <!-- Produk akan ditambahkan di sini -->
                  </div>
                  <small class="text-danger" id="produkError" style="display:none">Harap pilih minimal satu produk</small>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<!-- Template untuk produk yang bisa di-clone -->
<template id="templateProduk">
  <div class="input-group mb-2 produk-item">
    <select name="produk[]" class="form-control select-produk" required>
      <option value="">-- Pilih Produk --</option>
      <?php foreach($produk as $p) : ?>
        <option value="<?= $p['id_barang'] ?>"><?= $p['nama_barang'] ?></option>
      <?php endforeach; ?>
    </select>
    <div class="input-group-append">
      <button class="btn btn-danger btn-hapus-produk ml-2" type="button">
        <i class="fas fa-trash"></i>
      </button>
    </div>
  </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const tambahProdukBtn = document.getElementById('tambahProduk');
  const daftarProduk = document.getElementById('daftarProduk');
  const template = document.getElementById('templateProduk');
  const formSupplier = document.getElementById('formSupplier');
  const produkError = document.getElementById('produkError');
  
  // Tambah produk baru
  tambahProdukBtn.addEventListener('click', function() {
    const clone = template.content.cloneNode(true);
    const newItem = clone.querySelector('.produk-item');
    
    // Tombol hapus produk
    newItem.querySelector('.btn-hapus-produk').addEventListener('click', function() {
      newItem.remove();
      validateProduk();
    });
    
    // Validasi saat produk dipilih
    newItem.querySelector('select').addEventListener('change', validateProduk);
    
    daftarProduk.appendChild(clone);
    validateProduk();
  });
  
  // Validasi form sebelum submit
  formSupplier.addEventListener('submit', function(e) {
    if(!validateProduk()) {
      e.preventDefault();
      produkError.style.display = 'block';
      // Scroll ke error message
      document.getElementById('produkError').scrollIntoView({ behavior: 'smooth' });
    } else {
      produkError.style.display = 'none';
    }
  });
  
  // Fungsi validasi produk
  function validateProduk() {
    const selects = document.querySelectorAll('.select-produk');
    let isValid = false;
    
    selects.forEach(select => {
      if(select.value !== "") {
        isValid = true;
      }
    });
    
    produkError.style.display = isValid ? 'none' : 'block';
    return isValid;
  }
  
  // Tambahkan satu produk secara otomatis saat halaman dimuat
  tambahProdukBtn.click();
});
</script>

<?php
require "../templatess/footer.php";
?>