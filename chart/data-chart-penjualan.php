<?php
$koneksi = new mysqli("localhost", "root", "", "nama_database");

$query = "SELECT tgl_jual, SUM(total) AS total_harian 
          FROM jual_head 
          GROUP BY tgl_jual 
          ORDER BY tgl_jual ASC";
$result = $koneksi->query($query);

$tanggal = [];
$total = [];

while($row = $result->fetch_assoc()) {
    $tanggal[] = $row['tgl_jual'];
    $total[] = $row['total_harian'];
}

$data = [
    "labels" => $tanggal,
    "datasets" => $total
];

header('Content-Type: application/json');
echo json_encode($data);
?>
