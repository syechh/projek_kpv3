<?php

session_start();

if(!isset($_SESSION["ssLoginPOS"])){
  header("location: ../auth/login.php");
  exit();
}

require "../config/config.php";
require "../config/functions.php";

$tgl1 = $_GET['tgl1'];
$tgl2 = $_GET['tgl2'];
$dataJual = getData("SELECT * FROM jual_head WHERE tgl_jual BETWEEN '$tgl1' AND '$tgl2'");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
</head>
<body>
    

    <div style="text-align: center;">
        <h2 style="margin-bottom: -15px">Rekap Laporan Penjualan</h2>
        <h2 style="margin-bottom: 15px">Toko Bangunan Mutiara</h2>
    </div>

    <table>
        <thead>
            <tr>
                <td colspan="5" style="height: 5px;">
                    <hr style="margin-bottom: 2px; margin-left: -5px;", size="3", color="grey">
                </td>
            </tr>
            <tr>
                <th>No</th>
                <th style="width: 120px;">Tgl Penjualan</th>
                <th style="width: 120px;">Id Penjualan</th>
                <th style="width: 300px;">Customer</th>
                <th>Total Penjualan</th>
            </tr>
            <tr>
                <td colspan="5" style="height: 5px;">
                    <hr style="margin-bottom: 2px; margin-left: -5px; margin-top: 1px;", size="3", color="grey">
                </td>
            </tr>
        </thead>
        <tbody>
            <?php
                $no = 1;
                $totalPendapatan = 0;
                foreach ($dataJual as $data) {
                $totalPendapatan += $data['total'];
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td align="center"><?= in_date($data['tgl_jual']) ?></td>
                <td align="center"><?= $data['no_jual'] ?></td>
                <td align="center"><?= $data['customer'] ?></td>
                <td align="right"><?= number_format($data['total'],0,',','.') ?></td>
            </tr>
            <?php
                }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="height: 5px;">
                    <hr style="margin-bottom: 2px; margin-left: -5px; margin-top: 1px;" size="3" color="grey">
                </td>
            </tr>
            <tr>
                <th colspan="4" align="right">Total Penjualan:</th>
                <th align="right"><?= number_format($totalPendapatan, 0, ',', '.') ?></th>
            </tr>
        </tfoot>

        <tfoot>
            <tr>
                <td colspan="5" style="height: 5px;">
                    <hr style="margin-bottom: 2px; margin-left: -5px; margin-top: 1px;", size="3", color="grey">
                </td>
            </tr>
        </tfoot>
    </table>

    <script>
        window.print();
    </script>


</body>
</html>


<?php


?>