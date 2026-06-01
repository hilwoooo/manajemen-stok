<?php
include('../koneksi.php');

//tambah
if (isset($_POST['tambah_penjualan'])) {
    $id_barang    = $_POST['id_barang']; 
    $nama_pembeli = mysqli_real_escape_string($koneksi, strtoupper($_POST['nama_pembeli']));
    $no_hp        = mysqli_real_escape_string($koneksi, $_POST['no_hp']);

    $cari_barang = mysqli_query($koneksi, "SELECT id_katalog, harga, stok_masuk FROM tabel_barang WHERE id_barang = '$id_barang'");
    
    if (mysqli_num_rows($cari_barang) > 0) {
        $data_barang = mysqli_fetch_array($cari_barang);
        $id_katalog   = $data_barang['id_katalog'];
        $harga_jual   = $data_barang['harga'];
        $stok_masuk   = $data_barang['stok_masuk'];

        if ($stok_masuk <= 0) {
            echo "<script>alert('Gagal! Stok untuk batch terlama ini sudah habis. Silakan refresh halaman.'); window.location='../penjualan_tampil.php';</script>";
            exit();
        }

        $query_insert = "INSERT INTO tabel_keluar (nama_pembeli, no_hp, id_barang, total_bayar) 
                         VALUES ('$nama_pembeli', '$no_hp', '$id_barang', '$harga_jual')";
        
        if (mysqli_query($koneksi, $query_insert)) {
 
            mysqli_query($koneksi, "UPDATE tabel_barang SET stok_masuk = stok_masuk - 1 WHERE id_barang = '$id_barang'");

            mysqli_query($koneksi, "UPDATE master_katalog SET stok = stok - 1 WHERE id_katalog = '$id_katalog'");

            //barang keluar
            $keterangan_keluar = "TERJUAL KEPADA " . $nama_pembeli;
            mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, jumlah, keterangan) 
                                    VALUES ('$id_katalog', 'KELUAR', 1, '$keterangan_keluar')");

            echo "<script>alert('Penjualan Berhasil! Stok terpotong (FIFO) & Jurnal Riwayat Gabungan Tercatat.'); window.location='../penjualan_tampil.php';</script>";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    } else {
        echo "<script>alert('Gagal! Data batch barang tidak ditemukan.'); window.location='../penjualan_tampil.php';</script>";
    }
}
?>