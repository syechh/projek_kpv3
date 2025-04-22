<?php

function insert($data){
    global $koneksi;

    $username   = strtolower(mysqli_real_escape_string($koneksi, $data['username']));
    $fullname   = mysqli_real_escape_string($koneksi, $data['fullname']);
    $password   = mysqli_real_escape_string($koneksi, $data['password']);
    $password2  = mysqli_real_escape_string($koneksi, $data['password2']);
    $level      = mysqli_real_escape_string($koneksi, $data['level']);
    $alamat     = mysqli_real_escape_string($koneksi, $data['alamat']);
    $gambar     = mysqli_real_escape_string($koneksi, $_FILES['foto']['name']);

    if($password !== $password2){
        echo "<script>
               alert('Password anda tidak sesuai, user gagal registrasi');
            </script>";
        return false;
    }

    $pass   = password_hash($password, PASSWORD_DEFAULT);

    $cekUsername    = mysqli_query($koneksi, "SELECT username FROM users WHERE username = '$username'");
    if(mysqli_num_rows($cekUsername) > 0 ){
        echo "<script>
                alert('Username sudah tersedia, user gagal registrasi');
            </script>";
        return false;
    }

    if ($gambar != null) {
        $gambar = uploadimg();
    } else {
        $gambar = 'default-icon-user.jpg';
    }

    //gambar tidak sesuai validasi
    if($gambar == ''){
        return false;
    }

    $sqlUser    = "INSERT INTO users VALUE (null, '$username', '$fullname', '$pass', '$alamat', '$level', '$gambar')";
    mysqli_query($koneksi, $sqlUser);

    return mysqli_affected_rows($koneksi);
}

function delete($id, $foto){
    global $koneksi;

    $sqlDel = "DELETE FROM users WHERE user_id = $id";
    mysqli_query($koneksi, $sqlDel);
    if($foto != 'default-icon-user.jpg'){
        unlink('../assets/images/' . $foto);
    }
    
    return mysqli_affected_rows($koneksi);
}

//fungsi untuk user terpilih 
// admin
function selectUser1($level){
    $result = null;
    if($level == 1){
        $result = "selected";
    }
    return $result;
}

//supervisor
function selectUser2($level){
    $result = null;
    if($level == 2){
        $result = "selected";
    }
    return $result;
}

//owner
function selectUser3($level){
    $result = null;
    if($level == 3){
        $result = "selected";
    }
    return $result;
}

//fungsi edit data
function update($data){
    global $koneksi;

    $iduser     = mysqli_real_escape_string($koneksi, $data['id']);
    $username   = strtolower(mysqli_real_escape_string($koneksi, $data['username']));
    $fullname   = mysqli_real_escape_string($koneksi, $data['fullname']);
    $level      = mysqli_real_escape_string($koneksi, $data['level']);
    $alamat     = mysqli_real_escape_string($koneksi, $data['alamat']);
    $gambar     = mysqli_real_escape_string($koneksi, $_FILES['foto']['name']);
    $fotolama   = mysqli_real_escape_string($koneksi, $data['oldImg']);

    //cek username sekarang
    $queryUsername  = mysqli_query($koneksi, "SELECT * FROM users WHERE user_id = $iduser");
    $dataUsername   = mysqli_fetch_assoc($queryUsername);
    $curUsername    = $dataUsername['username'];

    //cek username baru
    $newUsername    = mysqli_query($koneksi, "SELECT username FROM users WHERE username = '$username'");
    
    //cek username ganti nama user atau tidak
    if($username !== $curUsername){
        if(mysqli_num_rows($newUsername)){
            echo "<script>
                alert('Username sudah tersedia, Update data user gagal');
                document.location.href= 'data_user.php';
            </script>";
        return false;
        }
    }

    //cek gambar
    if($gambar != null){
        $url    = "data-user.php";
        $imgUser = uploadimg($url);
        if($fotolama != 'default-icon-user.jpg'){
            @unlink('../assets/images' . $fotolama);
        }
    } else {
        $imgUser = $fotolama;
    }

    mysqli_query($koneksi, "UPDATE users SET username = '$username', 
                            fullname = '$fullname', 
                            alamat = '$alamat', 
                            level = '$level', 
                            foto = '$imgUser' 
                            WHERE user_id = $iduser"
                );

    return mysqli_affected_rows($koneksi);
}