<?php
if(userLogin()['level'] == 3){
    header("location:" . $main_url . "error-page.php");
    exit();
}

function insert($data){
    global $koneksi;

    $nama = mysqli_real_escape_string($koneksi, $data['nama']);
    $telpon = mysqli_real_escape_string($koneksi, $data['telpon']);

    // Cek apakah deskripsi dan alamat kosong/null
    $deskripsi = !empty($data['ketr']) ? mysqli_real_escape_string($koneksi, $data['ketr']) : '-';
    $alamat = !empty($data['alamat']) ? mysqli_real_escape_string($koneksi, $data['alamat']) : '-';

    $query = "INSERT INTO supplier VALUES (null, '$nama', '$telpon', '$deskripsi', '$alamat')";
    mysqli_query($koneksi, $query);

    return mysqli_insert_id($koneksi);
}


// Fungsi untuk menambahkan produk supplier (tanpa harga beli)
function tambahProdukSupplier($idSupplier, $idBarang) {
    global $koneksi;
    $query = "INSERT INTO produk_supplier (id_supplier, id_barang) 
              VALUES ($idSupplier, '$idBarang')";
    return mysqli_query($koneksi, $query);
}

// Fungsi untuk mendapatkan produk yang dijual oleh supplier
function getProdukBySupplier($idSupplier) {
    global $koneksi;
    $query = "SELECT ps.id_barang, b.* 
              FROM produk_supplier ps
              JOIN barang b ON ps.id_barang = b.id_barang
              WHERE ps.id_supplier = $idSupplier
              ORDER BY b.nama_barang";
    return mysqli_query($koneksi, $query);
}

//fungsi hapus data supplier
function delete($id){
    global $koneksi;
    $sqlDelete  = "DELETE FROM supplier WHERE id_supplier = $id";
    mysqli_query($koneksi, $sqlDelete);

    return mysqli_affected_rows($koneksi);
}

//fungsi edit / update supplier
function update($data){
    global $koneksi;

    $id       = mysqli_real_escape_string($koneksi, $data['id']);
    $nama     = mysqli_real_escape_string($koneksi, $data['nama']);
    $telpon   = mysqli_real_escape_string($koneksi, $data['telpon']);
    $alamat   = mysqli_real_escape_string($koneksi, $data['alamat']);
    $ketr     = mysqli_real_escape_string($koneksi, $data['ketr']);

    $sqlSupplier = "UPDATE supplier SET 
                    nama = '$nama',
                    telp = '$telpon',
                    deskripsi = '$ketr',
                    alamat = '$alamat'
                    WHERE id_supplier = $id";
    mysqli_query($koneksi, $sqlSupplier);

    return mysqli_affected_rows($koneksi);
}
?>