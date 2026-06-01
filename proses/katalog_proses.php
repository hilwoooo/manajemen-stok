<?php
include('../koneksi.php');

//tambah
if (isset($_POST['tambah_katalog'])) {
    $merek       = mysqli_real_escape_string($koneksi, strtoupper($_POST['merek']));
    $nama_produk = mysqli_real_escape_string($koneksi, strtoupper($_POST['nama_produk']));
    $jenis       = $_POST['jenis'];

    // Kita masukkan kolom 'stok' dengan nilai default 0 saat pertama kali didaftarkan
    $query_tambah = "INSERT INTO master_katalog (merek, nama_produk, jenis, stok) VALUES ('$merek', '$nama_produk', '$jenis', 0)";
    
    if (mysqli_query($koneksi, $query_tambah)) {
        echo "<script>alert('Produk Berhasil Ditambahkan ke Katalog!'); window.location='../katalog_tampil.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

//edit
if (isset($_POST['edit_katalog'])) {
    $id_katalog  = $_POST['id_katalog'];
    $merek       = mysqli_real_escape_string($koneksi, strtoupper($_POST['merek']));
    $nama_produk = mysqli_real_escape_string($koneksi, strtoupper($_POST['nama_produk']));
    $jenis       = $_POST['jenis'];

    // Update profil produk tanpa mengganggu jumlah stok yang sedang berjalan
    $query_edit = "UPDATE master_katalog SET merek='$merek', nama_produk='$nama_produk', jenis='$jenis' WHERE id_katalog='$id_katalog'";
    
    if (mysqli_query($koneksi, $query_edit)) {
        echo "<script>alert('Data Katalog Berhasil Diubah!'); window.location='../katalog_tampil.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

//hapus
if (isset($_POST['hapus_katalog'])) {
    $id_katalog = $_POST['id_katalog'];
    
    // LANGKAH AMAN: Hapus dulu semua log barang masuk yang berkaitan di tabel_barang
    // Supaya database tidak mengalami error Foreign Key Constraint
    mysqli_query($koneksi, "DELETE FROM tabel_barang WHERE id_katalog='$id_katalog'");
    
    // Baru setelah itu hapus data masternya
    $query_hapus = "DELETE FROM master_katalog WHERE id_katalog='$id_katalog'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        echo "<script>alert('Produk dan Seluruh History Barang Berhasil Dihapus!'); window.location='../katalog_tampil.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>