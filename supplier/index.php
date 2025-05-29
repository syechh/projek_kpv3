<?php
session_start();

if(!isset($_SESSION["ssLoginPOS"])){
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-supplier.php";

$title = "Data Supplier - Toko Bangunan Mutiara";
require "../templatess/header.php";
require "../templatess/navbar.php";
require "../templatess/sidebar.php";

if(isset($_GET['msg'])){
    $msg = $_GET['msg'];
} else {
    $msg = '';
}

$alert = '';

if($msg == 'deleted'){
    $alert = '<div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-check"></i> Alert!</h5>
                  Supplier berhasil dihapus
                </div>';
}

if($msg == 'updated'){
  $alert = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check-circle "></i> Alert!</h5>
                Supplier berhasil diperbarui
              </div>';
}

if($msg == 'aborted'){
    $alert = '<div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                  Supplier gagal dihapus
                </div>';
}
?>

<style>
  /* File: assets/css/custom.css */
  .table td {
    vertical-align: middle;
  }

  .badge-info {
    display: inline-block;
    padding: 5px 10px;
    margin-right: 5px;
    margin-bottom: 5px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: normal;
    white-space: normal;
    text-align: left;
  }

  #tblData {
    table-layout: fixed;
  }

  #tblData td:nth-child(5), /* Deskripsi */
  #tblData td:nth-child(6) { /* Produk */
    word-wrap: break-word;
  }

  /* opsional data akan ke kanan bukan menurun ke bawah */
  /* .badge-info {
    white-space: nowrap;
    display: inline-block;
    margin: 2px;
    padding: 5px 8px;
    font-size: 12px;
    font-weight: normal;
} */
</style>

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
            <li class="breadcrumb-item active">Data Supplier</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section>
        <div class="container-fluid">
            <div class="card">
                <?php if($alert != ''){
                    echo $alert;
                } ?>
                <div class="card-header">
                    <h3 class="card-title">
                            <i class="fas fa-list fa-sm"></i> Data Supplier
                        </h3>
                        <a href="<?= $main_url ?>supplier/add-supplier.php" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus fa-sm"></i> Tambah Supplier</a>
                </div>
                <div class="card-body table-responsive p-3">
                    <table class="table table-hover text-nowrap" id="tblData">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Telpon</th>
                                <th>Alamat</th>
                                <th>Deskripsi</th>
                                <th style="width: 20%">Produk yang Dijual</th>
                                <th style="width: 10%">Operasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                $suppliers  =   getData("SELECT * FROM supplier");
                                foreach($suppliers as $supplier):
                                    $produkSupplier = getProdukBySupplier($supplier['id_supplier']);
                                    $produkList = '';
                                    if(mysqli_num_rows($produkSupplier) > 0) {
                                        $produkList = '<div style="display: flex; flex-wrap: wrap; gap: 5px; max-height: 100px; overflow-y: auto;">';
                                        while($produk = mysqli_fetch_assoc($produkSupplier)) {
                                            $produkList .= '<span class="badge badge-info">' . $produk['nama_barang'] . '</span>';
                                        }
                                        $produkList .= '</div>';
                                    } else {
                                        $produkList = '<span class="text-muted">Belum ada produk</span>';
                                    }
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $supplier['nama'] ?></td>
                                    <td><?= $supplier['telp'] ?></td>
                                    <td><?= $supplier['alamat'] ?></td>
                                    <td><?= $supplier['deskripsi'] ?></td>
                                    <td><?= $produkList ?></td>
                                    <td>
                                        <a href="edit-supplier.php?id=<?= $supplier['id_supplier'] ?>" class="btn btn-sm btn-warning" title="Edit Supplier"><i class="fas fa-pen"></i></a>
                                        <!-- Tombol hapus trigger modal -->
                                        <a href="#" 
                                          class="btn btn-sm btn-danger btn-delete-supplier" 
                                          data-id="<?= $supplier['id_supplier'] ?>" 
                                          data-nama="<?= htmlspecialchars($supplier['nama']) ?>" 
                                          data-toggle="modal" 
                                          data-target="#modalDeleteSupplier" 
                                          title="Hapus Supplier">
                                          <i class="fas fa-trash"></i>
                                        </a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Konfirmasi Hapus Supplier -->
<div class="modal fade" id="modalDeleteSupplier" tabindex="-1" role="dialog" aria-labelledby="modalDeleteSupplierLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content bg-danger">
      <div class="modal-header">
        <h5 class="modal-title text-white">Konfirmasi Hapus</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-white">
        <p>Yakin mau hapus supplier <strong id="namaSupplier"></strong>?</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Batal</button>
        <a href="#" class="btn btn-light" id="btnConfirmDelete">Hapus</a>
      </div>
    </div>
  </div>
</div>

<script>
  //script untuk modal
  $(document).ready(function () {
    $('.btn-delete-supplier').on('click', function () {
      const id = $(this).data('id');
      const nama = $(this).data('nama');
      
      $('#namaSupplier').text(nama);
      $('#btnConfirmDelete').attr('href', 'del-supplier.php?id=' + id);
    });
  });
</script>


<?php
require "../templatess/footer.php";
?>