<?php
session_start();

if(!isset($_SESSION["ssLoginPOS"])){
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-customer.php";

$title = "Edit Customer - Toko Bangunan Mutiara";
require "../templatess/header.php";
require "../templatess/navbar.php";
require "../templatess/sidebar.php";

if(isset($_POST['update'])){
    if(updateCustomer($_POST)){
        echo "<script>
                document.location.href = 'data-customer.php?msg=updated';
            </script>";
    }
}

$id = $_GET['id'];
if($id == null){
  echo "<script>
            alert('data tidak ada..');
            document.location = 'index.php';
          </script>";
}


$sqlEdit = "SELECT * FROM customer WHERE id_customer = $id";
$customer = getData($sqlEdit)['0'];
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Customer</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= $main_url ?>dashboard.php">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= $main_url ?>customer/data-customer.php">Customer</a></li>
            <li class="breadcrumb-item active">Edit Customer</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card">
        <form action="" method="POST">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-pen fa-sm"></i> Edit Customer
            </h3>
            <button type="submit" name="update" class="btn btn-primary btn-sm float-right">
              <i class="fas fa-save"></i> Perbarui
            </button>
            <button type="reset" class="btn btn-danger btn-sm float-right mr-1">
              <i class="fas fa-times"></i> Reset
            </button>
          </div>
          <div class="card-body">
            <div class="row">
              <input type="hidden" name="id" value="<?= $customer['id_customer'] ?>">
              <div class="col-lg-8 mb-3">
                <div class="form-group">
                  <label for="name">Nama Customer</label>
                  <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama Customer" autofocus value="<?= $customer['nama'] ?>" required>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<?php
require "../templatess/footer.php";
?>