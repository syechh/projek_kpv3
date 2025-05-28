<?php
session_start();
require "../config/config.php";

if(isset($_GET['no_beli'])) {
    $no_beli = $_GET['no_beli'];
    
    // Cek supplier di head
    $query = mysqli_query($koneksi, "SELECT h.suplier, s.nama as supplierName 
                                   FROM beli_head h 
                                   JOIN supplier s ON h.suplier = s.id_supplier 
                                   WHERE h.no_beli = '$no_beli'");
    
    if(mysqli_num_rows($query)) {
        $data = mysqli_fetch_assoc($query);
        echo json_encode([
            'supplier' => $data['suplier'],
            'supplierName' => $data['supplierName']
        ]);
    } else {
        // Jika belum ada head, cek dari detail
        $query = mysqli_query($koneksi, "SELECT d.suplier, s.nama as supplierName 
                                       FROM beli_detail d
                                       JOIN supplier s ON d.suplier = s.id_supplier 
                                       WHERE d.no_beli = '$no_beli' LIMIT 1");
        if(mysqli_num_rows($query)) {
            $data = mysqli_fetch_assoc($query);
            echo json_encode([
                'supplier' => $data['suplier'],
                'supplierName' => $data['supplierName']
            ]);
        } else {
            echo json_encode(['supplier' => null]);
        }
    }
} else {
    echo json_encode(['error' => 'No purchase number provided']);
}
?>