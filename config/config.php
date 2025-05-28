<?php

date_default_timezone_set('Asia/Jakarta');

$host   = 'localhost';
$user   = 'root';
$pass   = '';
$db     = 'ud_mutiara';

$koneksi    = mysqli_connect($host, $user, $pass, $db);

// CEK KONEKSI
// if(mysqli_connect_errno()){
//     echo "Koneksi gagal : " . mysqli_connect_error();
//     exit();
// } else {
//     echo "Koneksi Berhasil";
// }

$main_url = 'http://localhost/projek_kp/';