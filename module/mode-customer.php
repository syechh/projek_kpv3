<?php
require "../config/config.php";

// Check session
if (isset($_SESSION["ssLoginPOS"])) {
    $level = userLogin()['level'];
    if ($level != 1 && $level != 3) {
        header("Location: " . $main_url . "error-page.php");
        exit();
    }
}


function insertCustomer($data){
    global $koneksi;
    $nama = mysqli_real_escape_string($koneksi, $data['nama']);
    
    $sql = "INSERT INTO customer (nama) VALUES ('$nama')";
    mysqli_query($koneksi, $sql);
    
    return mysqli_affected_rows($koneksi);
}

// Delete customer function
function deleteCustomer($id){
    global $koneksi;
    $sqlDelete = "DELETE FROM customer WHERE id_customer = $id";
    mysqli_query($koneksi, $sqlDelete);
    return mysqli_affected_rows($koneksi);
}

// Update customer function (name only)
function updateCustomer($data){
    global $koneksi;
    $id = mysqli_real_escape_string($koneksi, $data['id']);
    $nama = mysqli_real_escape_string($koneksi, $data['nama']);

    $sqlCustomer = "UPDATE customer SET nama = '$nama' WHERE id_customer = $id";
    mysqli_query($koneksi, $sqlCustomer);
    return mysqli_affected_rows($koneksi);
}

// Get customers function
function getCustomers($search = '') {
    global $koneksi;
    $query = "SELECT * FROM customer";
    if(!empty($search)) {
        $query .= " WHERE nama LIKE '%$search%'";
    }
    return mysqli_query($koneksi, $query);
}
?>