<?php
require "../config/config.php";

$noBeli = $_GET['no_beli'] ?? '';

// Dapatkan semua barang yang akan dihapus untuk mengembalikan stok
$query = "SELECT kode_brg, qty FROM beli_detail WHERE no_beli = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $noBeli);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    // Kembalikan stok barang
    $update = "UPDATE barang SET stock = stock - ? WHERE id_barang = ?";
    $stmtUpdate = $koneksi->prepare($update);
    $stmtUpdate->bind_param("is", $row['qty'], $row['kode_brg']);
    $stmtUpdate->execute();
}

// Hapus detail pembelian
$delete = "DELETE FROM beli_detail WHERE no_beli = ?";
$stmtDelete = $koneksi->prepare($delete);
$stmtDelete->bind_param("s", $noBeli);
$stmtDelete->execute();

echo json_encode(['success' => true]);
?>