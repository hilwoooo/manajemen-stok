<?php
include('../Config/koneksi.php');

global $koneksi;

//tambah
if (isset($_POST['tambah_katalog'])) {
    $merek       = mysqli_real_escape_string($koneksi, strtoupper($_POST['merek']));
    $nama_produk = mysqli_real_escape_string($koneksi, strtoupper($_POST['nama_produk']));
    $id_kategori = $_POST['id_kategori'];

    if ($id_kategori == 1) {
        $jenis = "SPAREPART";
    } elseif ($id_kategori == 2) {
        $jenis = "LAPTOP";
    } elseif ($id_kategori == 3) {
       $jenis = "AKSESORIS";
    } else {
        $jenis = "LAINNYA";
    }

    // insert
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

//edit
// if (isset($_POST['edit_katalog'])) {
//     $id_katalog  = $_POST['id_katalog'];
//     $merek       = mysqli_real_escape_string($koneksi, strtoupper($_POST['merek']));
//     $nama_produk = mysqli_real_escape_string($koneksi, strtoupper($_POST['nama_produk']));
//     $jenis       = $_POST['jenis'];

//     // Update profil produk tanpa mengganggu jumlah stok yang sedang berjalan
//     $query_edit = "UPDATE master_katalog SET merek='$merek', nama_produk='$nama_produk', jenis='$jenis' WHERE id_katalog='$id_katalog'";
    
//     if (mysqli_query($koneksi, $query_edit)) {
//         echo "<script>alert('Data Katalog Berhasil Diubah!'); window.location='../katalog_tampil.php';</script>";
//     } else {
//         echo "Error: " . mysqli_error($koneksi);
//     }
// }

//hapus
if (isset($_POST['hapus_katalog'])) {
    $id_katalog = $_POST['id_katalog'];

    mysqli_query($koneksi, "DELETE FROM tabel_barang WHERE id_katalog='$id_katalog'");

    $query_hapus = "DELETE FROM master_katalog WHERE id_katalog='$id_katalog'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        echo "<script>alert('Produk dan Seluruh History Barang Berhasil Dihapus!'); window.location='../views/katalog/katalog_tampil.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>