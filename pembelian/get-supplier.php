<?php
require "../config/config.php";

$supplierId = $_GET['id_supplier'] ?? '';

$query = "SELECT b.* FROM produk_supplier ps 
          JOIN barang b ON ps.id_barang = b.id_barang 
          WHERE ps.id_supplier = ? 
          ORDER BY b.nama_barang";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $supplierId);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

header('Content-Type: application/json');
echo json_encode($products);
?>