<?php

function generateNo() {
    global $koneksi;
    
    $queryNo = mysqli_query($koneksi, "SELECT max(no_beli) as maxno FROM beli_head");
    $row = mysqli_fetch_assoc($queryNo);
    $maxno = $row["maxno"];

    if (empty($maxno)) {
        return 'PB0001';
    }

    // Ekstrak nomor urut
    $noUrut = (int) substr($maxno, 2);
    $noUrut++;
    
    return 'PB' . sprintf("%04d", $noUrut);
}

function totalBeli($nobeli) {
    global $koneksi;
    $totalBeli = mysqli_query($koneksi, "SELECT sum(jml_harga) AS total FROM beli_detail WHERE no_beli = '$nobeli'");
    $data = mysqli_fetch_assoc($totalBeli);
    return $data["total"] ?? 0;
}

function insert($data) {
    global $koneksi;

    $no = mysqli_real_escape_string($koneksi, $data['nobeli']);
    $tgl = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $kode = mysqli_real_escape_string($koneksi, $data['kodeBrg']);
    $nama = mysqli_real_escape_string($koneksi, $data['namaBrg']);
    $qty = (int)$data['qty'];
    $harga = (int)$data['harga'];
    $jmlharga = $qty * $harga;
    $supplierId = mysqli_real_escape_string($koneksi, $data['supplier']);
    
    // Dapatkan nama supplier
    $supplierQuery = mysqli_query($koneksi, "SELECT nama FROM supplier WHERE id_supplier = '$supplierId'");
    $supplierData = mysqli_fetch_assoc($supplierQuery);
    $supplierName = $supplierData['nama'];

    $cekbrg = mysqli_query($koneksi, "SELECT * FROM beli_detail WHERE no_beli = '$no' AND kode_brg = '$kode' AND suplier = '$supplierId'");
    
    if(mysqli_num_rows($cekbrg)) {
        // Jika barang dari supplier yang sama sudah ada, update jumlahnya
        $existing = mysqli_fetch_assoc($cekbrg);
        $newQty = $existing['qty'] + $qty;
        $newJmlHarga = $newQty * $harga;
        
        $sqlUpdate = "UPDATE beli_detail SET 
                     qty = $newQty, 
                     jml_harga = $newJmlHarga 
                     WHERE no_beli = '$no' AND kode_brg = '$kode' AND suplier = '$supplierId'";
        mysqli_query($koneksi, $sqlUpdate);
        
        // Update stok barang
        mysqli_query($koneksi, "UPDATE barang SET stock = stock + $qty WHERE id_barang = '$kode'");
    } else if(empty($qty) || $qty <= 0) {
        echo "<script>alert('Qty barang harus lebih dari 0');</script>";
        return false;
    } else {
        // Jika barang belum ada, insert baru
        $sqlbeli = "INSERT INTO beli_detail (no_beli, tgl_beli, kode_brg, nama_brg, qty, harga_beli, jml_harga, suplier, supplier_name) 
                    VALUES ('$no', '$tgl', '$kode', '$nama', $qty, $harga, $jmlharga, '$supplierId', '$supplierName')";
        mysqli_query($koneksi, $sqlbeli);
        
        // Update stok barang
        mysqli_query($koneksi, "UPDATE barang SET stock = stock + $qty WHERE id_barang = '$kode'");
    }

    return mysqli_affected_rows($koneksi);
}

function delete($idbrg, $idbeli, $qty) {
    global $koneksi;

    $query = mysqli_query($koneksi, "SELECT qty, suplier FROM beli_detail WHERE kode_brg = '$idbrg' AND no_beli = '$idbeli'");
    if(mysqli_num_rows($query)) {
        $data = mysqli_fetch_assoc($query);
        $qtyToRestore = $data['qty'];
        $supplierId = $data['suplier'];
        
        $sqlDel = "DELETE FROM beli_detail WHERE kode_brg = '$idbrg' AND no_beli = '$idbeli' AND suplier = '$supplierId'";
        mysqli_query($koneksi, $sqlDel);

        mysqli_query($koneksi, "UPDATE barang SET stock = stock - $qtyToRestore WHERE id_barang = '$idbrg'");
    }

    return mysqli_affected_rows($koneksi);
}

function simpan($data) {
    global $koneksi;

    $nobeli = mysqli_real_escape_string($koneksi, $data['nobeli']);
    $tgl = mysqli_real_escape_string($koneksi, $data['tglNota']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['ketr']);

    // Validasi apakah ada barang yang dibeli
    $cekDetail = mysqli_query($koneksi, "SELECT * FROM beli_detail WHERE no_beli = '$nobeli'");
    if(mysqli_num_rows($cekDetail) == 0) {
        echo "<script>alert('Tidak ada barang yang dibeli. Transaksi tidak dapat diproses!');</script>";
        return false;
    }

    // Dapatkan semua supplier yang terlibat
    $suppliers = getData("SELECT DISTINCT suplier, supplier_name FROM beli_detail WHERE no_beli = '$nobeli'");
    
    // Simpan transaksi untuk setiap supplier secara terpisah
    foreach($suppliers as $supplier) {
        $supplierId = $supplier['suplier'];
        $supplierName = $supplier['supplier_name'];
        
        // Hitung total untuk supplier ini
        $supplierTotal = getData("SELECT SUM(jml_harga) as total FROM beli_detail 
                               WHERE no_beli = '$nobeli' AND suplier = '$supplierId'")[0]['total'];
        
        // Generate nomor nota baru yang sequential
        $supplierNoBeli = generateNo();
        
        // Simpan head pembelian untuk supplier ini
        $sqlbeli = "INSERT INTO beli_head (no_beli, tgl_beli, suplier, total, keterangan) 
                    VALUES ('$supplierNoBeli', '$tgl', '$supplierId', $supplierTotal, '$keterangan')";
        mysqli_query($koneksi, $sqlbeli);
        
        // Update detail pembelian dengan nomor nota yang baru
        mysqli_query($koneksi, "UPDATE beli_detail SET no_beli = '$supplierNoBeli' 
                               WHERE no_beli = '$nobeli' AND suplier = '$supplierId'");
    }

    return true;
}

// Fungsi FIFO untuk penjualan
function applyFIFO($id_barang, $qty) {
    global $koneksi;
    
    $purchases = getData("SELECT * FROM beli_detail 
                         WHERE kode_brg = '$id_barang' 
                         ORDER BY tgl_beli ASC, no_beli ASC");
    
    $remainingQty = $qty;
    $totalCost = 0;
    
    foreach($purchases as $purchase) {
        if($remainingQty <= 0) break;
        
        $availableQty = $purchase['qty'];
        $usedQty = min($availableQty, $remainingQty);
        
        $totalCost += $usedQty * $purchase['harga_beli'];
        $remainingQty -= $usedQty;
        
        // Update stok yang tersisa di pembelian ini
        mysqli_query($koneksi, "UPDATE beli_detail SET qty = qty - $usedQty 
                               WHERE id = {$purchase['id']}");
    }
    
    return $totalCost;
}
?>