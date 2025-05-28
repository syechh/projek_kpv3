<?php
require "../config/config.php";

header('Content-Type: application/json');

if(isset($_GET['id_supplier'])) {
    // Jika ada parameter id_supplier, ambil produk dari supplier tersebut
    $id_supplier = $_GET['id_supplier'];
    
    $query = "SELECT b.* FROM produk_supplier ps 
              JOIN barang b ON ps.id_barang = b.id_barang 
              WHERE ps.id_supplier = $id_supplier
              ORDER BY b.nama_barang";
    $result = mysqli_query($koneksi, $query);
    
    $products = [];
    while($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    
    echo json_encode($products);
} else {
    // Jika tidak ada parameter, ambil semua supplier
    $query = "SELECT * FROM supplier ORDER BY nama";
    $result = mysqli_query($koneksi, $query);
    
    $suppliers = [];
    while($row = mysqli_fetch_assoc($result)) {
        $suppliers[] = $row;
    }
    
    echo json_encode($suppliers);
}

mysqli_close($koneksi);
?>