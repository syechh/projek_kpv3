<?php

function uploadimg($url = null){
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

    $namaFileBaru = rand(10, 1000) . '-' . $namafile;
    
    move_uploaded_file($tmp, '../assets/images/' . $namaFileBaru);
    return $namaFileBaru;
}

function getData($sql){
    global $koneksi;

    $result = mysqli_query($koneksi, $sql);
    $rows   = [];
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

?>