<?php
include('../Config/koneksi.php');

global $koneksi;

//tambah
if (isset($_POST['tambah_katalog'])) {
    // input data tambah katalog
    $merek       = mysqli_real_escape_string($koneksi, strtoupper($_POST['merek']));
    $nama_produk = mysqli_real_escape_string($koneksi, strtoupper($_POST['nama_produk']));
    $id_kategori = $_POST['id_kategori'];

    // proses penentuan jenis berdasarkan id kategori
    if ($id_kategori == 1) {
        $jenis = "SPAREPART";
    } elseif ($id_kategori == 2) {
        $jenis = "LAPTOP";
    } elseif ($id_kategori == 3) {
       $jenis = "AKSESORIS";
    } else {
        $jenis = "LAINNYA";
    }

    // Query untuk menyimpan data ke master katalog
    $query_tambah = "INSERT INTO master_katalog 
                    (merek, nama_produk, jenis, stok, id_kategori) 
                    VALUES 
                    ('$merek', '$nama_produk', '$jenis', 0, '$id_kategori')";

    if (mysqli_query($koneksi, $query_tambah)) {
        echo "<script>alert('Produk Berhasil Ditambahkan ke Katalog!'); window.location='../views/katalog/katalog_tampil.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

//hapus
if (isset($_POST['hapus_katalog'])) {
    // input id katalog yang mau dihapus
    $id_katalog = $_POST['id_katalog'];

    // Query hapus data di tabel barang yang berelasi dengan id katalog
    mysqli_query($koneksi, "DELETE FROM tabel_barang WHERE id_katalog='$id_katalog'");

    // Query hapus data dari master katalog
    $query_hapus = "DELETE FROM master_katalog WHERE id_katalog='$id_katalog'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        echo "<script>alert('Produk dan Seluruh History Barang Berhasil Dihapus!'); window.location='../views/katalog/katalog_tampil.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>