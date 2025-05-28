<?php
session_start();
require "../config/config.php";

if(isset($_GET['no_jual'])) {
    $no_jual = $_GET['no_jual'];
    
    // Hapus detail penjualan dan kembalikan stok
    $query = mysqli_query($koneksi, "SELECT * FROM jual_detail WHERE no_jual = '$no_jual'");
    while($row = mysqli_fetch_assoc($query)) {
        $barcode = $row['barcode'];
        $qty = $row['qty'];
        mysqli_query($koneksi, "UPDATE barang SET stock = stock + $qty WHERE barcode = '$barcode'");
    }
    
    // Hapus semua record di jual_detail
    $delete = mysqli_query($koneksi, "DELETE FROM jual_detail WHERE no_jual = '$no_jual'");
    
    if($delete) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Nomor penjualan tidak valid']);
}
?>