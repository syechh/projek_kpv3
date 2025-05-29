<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?></title>

  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Tambahkan font awesome (kalau belum) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo $main_url ?>assets/adminLTE-3.2.0/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="<?php echo $main_url ?>assets/adminLTE-3.2.0/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- SweetAlert2 -->
  <script src="<?php echo $main_url ?>assets/adminLTE-3.2.0/plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!--JQuery -->
  <script src="<?php echo $main_url ?>assets/adminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $main_url ?>assets/adminLTE-3.2.0/dist/css/adminlte.min.css">
  <link rel="shortcut icon" href="<?php echo $main_url ?>assets/images/cart.png">
</head> 
<style>
  /* body {
  transition: background-color 0.3s, color 0.3s;
  } */

  body.dark-mode {
    background-color: #121212;
    color: #eee;
  }

  body.dark-mode .main-header.navbar {
    background-color: #343a40 !important;
    color: #fff !important;
  }

  body.dark-mode .main-header.navbar .btn {
    color: #fff !important;
    border-color: #fff !important;
  }

</style>
<body class="hold-transition sidebar-mini dark-mode">
<div class="wrapper"></div>