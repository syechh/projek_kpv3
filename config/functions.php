<?php

function uploadimg($url = null, $name = null){
    $namafile   = $_FILES['foto']['name'];
    $ukuran     = $_FILES['foto']['size'];
    $tmp        = $_FILES['foto']['tmp_name'];

    //validasi file gambar yang boleh diupload
    $ekstensiGambarValid    = ['jpg', 'jpeg', 'png', 'gif'];
    $ekstensiGambar         = explode('.', $namafile);
    $ekstensiGambar          = strtolower(end($ekstensiGambar));
    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        if($url != null){
            echo '<script>
                alert("file yang anda upload bukan gambar, Data gagal diupdate");
                document.location.href= "' . $url . '";
            </script>';
            die();
        } else {

            echo '<script>
                alert("file yang anda upload bukan gambar, Data gagal ditambahkan");
                </script>';
            return false;
        }
    }

    //validasi ukuran gambar max 1mb
    if ($ukuran > 1000000) {
        if($url != null){
            echo '<script>
                alert("Ukuran gambar melebihi 1 MB, Data gagal diupdate");
                document.location.href= "' . $url . '";
            </script>';
            die();
        } else {
            echo '<script>
                alert("file yang anda upload melebihi kapasitas (1 MB), Data gagal ditambahkan"); 
                </script>';
            return false;
        }
    }

    if($name != null){
        $namaFileBaru = $name . '.' . $ekstensiGambar;
    } else {
        $namaFileBaru = rand(10, 1000) . '-' . $namafile;
    }

    move_uploaded_file($tmp, '../assets/images/' . $namaFileBaru);
    return $namaFileBaru;
}

function getData($sql){
    global $koneksi;

    // Gunakan prepared statement untuk mencegah SQL injection
    $stmt = mysqli_prepare($koneksi, $sql);
    if (!$stmt) {
        die("Error in prepared statement: " . mysqli_error($koneksi));
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $rows = [];
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    
    mysqli_stmt_close($stmt);
    return $rows;
}

function userLogin(){
    $userActive = $_SESSION["ssUserPOS"];
    $dataUser   = getData("SELECT * FROM users WHERE username = '$userActive'")[0];
    return $dataUser;
}

//fungsi beriwarna pada sidebar menu jika terpilih (berwarna biru)
function userMenu(){
    $uri_path   = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri_segments   = explode('/', $uri_path);
    $menu           = $uri_segments['2'];
    return $menu;
}

function menuHome(){
    if(userMenu() == 'dashboard.php'){
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

//menu buka pengaturan
function menuSetting(){
    if(userMenu() == 'users'){
        $result = 'menu-is-opening menu-open';
    } else {
        $result = null;
    }
    return $result;
}

//membuat menu supplier aktif ketika dipilih
function menuMaster(){
    if(userMenu() == 'supplier' or userMenu() == 'customer' or userMenu() == 'barang'){
        $result = 'menu-is-opening menu-open';
    } else {
        $result = null;
    }
    return $result;
}

function menuUser(){
    if(userMenu() == 'users'){
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function menuSupplier(){
    if(userMenu() == 'supplier'){
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function menuCustomer(){
    if(userMenu() == 'customer'){
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function menuBarang(){
    if(userMenu() == 'barang'){
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function MenuPembelian(){
    if(userMenu() == 'pembelian'){
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function MenuPenjualan(){
    if(userMenu() == 'penjualan'){
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}
function MenuStockBarang(){
    if(userMenu() == 'stockBarang'){
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function laporanPembelian(){
    if(userMenu() == 'laporan-pembelian'){
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function laporanPenjualan(){
    if(userMenu() == 'laporan-penjualan'){
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}

function laporanStock(){
    if(userMenu() == 'stock'){
        $result = 'active';
    } else {
        $result = null;
    }
    return $result;
}


function in_date($tgl){
    $tg = substr($tgl, 8, 2);
    $bln = substr($tgl, 5, 2);
    $thn = substr($tgl, 0, 4);
    return $tg . "-" . $bln . "-" . $thn;
}
//chart otomatis
function getPenjualan($periode) {
    global $koneksi;

    if ($periode == 'mingguan') {
        // Ambil data total penjualan per minggu (ISO week)
        $sql = "SELECT YEAR(tgl_jual) AS tahun, WEEK(tgl_jual, 1) AS minggu, SUM(total) AS total_harian 
                FROM jual_head 
                GROUP BY tahun, minggu 
                ORDER BY tahun, minggu ASC";
        $result = $koneksi->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            // Gabungkan tahun dan minggu jadi label, contoh "2025-W22"
            $row['label'] = $row['tahun'] . '-W' . str_pad($row['minggu'], 2, '0', STR_PAD_LEFT);
            $data[] = $row;
        }
        return $data;
    } else if ($periode == 'bulanan') {
        // Ambil data total penjualan per bulan
        $sql = "SELECT YEAR(tgl_jual) AS tahun, MONTH(tgl_jual) AS bulan, SUM(total) AS total_harian 
                FROM jual_head 
                GROUP BY tahun, bulan 
                ORDER BY tahun, bulan ASC";
        $result = $koneksi->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            // Label: "2025-05"
            $row['label'] = $row['tahun'] . '-' . str_pad($row['bulan'], 2, '0', STR_PAD_LEFT);
            $data[] = $row;
        }
        return $data;
    } else {
        // Default: harian
        $sql = "SELECT tgl_jual AS label, SUM(total) AS total_harian 
                FROM jual_head 
                GROUP BY tgl_jual 
                ORDER BY tgl_jual ASC";
        $result = $koneksi->query($sql);
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}

//chart produk terlaris
function getProdukTerlaris() {
  global $koneksi; // pastikan koneksi db tersedia

  $sql = "SELECT nama_brg, SUM(qty) as total_terjual
          FROM jual_detail
          GROUP BY nama_brg
          ORDER BY total_terjual DESC
          LIMIT 10"; // ambil 10 produk terlaris

  $result = mysqli_query($koneksi, $sql);
  $produk = [];
  $jumlah = [];

  while ($row = mysqli_fetch_assoc($result)) {
    $produk[] = $row['nama_brg'];
    $jumlah[] = (int)$row['total_terjual'];
  }

  return [$produk, $jumlah];
}

list($produkTerlarisLabels, $produkTerlarisData) = getProdukTerlaris();


?>