<?php
include('../koneksi.php');

// ====================================================================
// 1. PROSES TAMBAH BARANG MASUK (KULAKAN)
// ====================================================================
if (isset($_POST['tambah_barang'])) {
    $id_katalog = $_POST['id_katalog'];
    $harga      = $_POST['harga'];
    $stok_masuk = $_POST['stok_masuk']; // Mengambil input jumlah barang masuk

    // AKSI 1: Selalu INSERT ke tabel_barang sebagai log riwayat baru (menyimpan harga & stok_masuk)
    $query_ins = "INSERT INTO tabel_barang (id_katalog, harga, stok_masuk) VALUES ('$id_katalog', '$harga', '$stok_masuk')";
    
    if (mysqli_query($koneksi, $query_ins)) {
        
        // AKSI 2: Tambahkan jumlah stok_masuk tadi ke kolom stok milik master_katalog
        $query_upd_master = "UPDATE master_katalog SET stok = stok + $stok_masuk WHERE id_katalog = '$id_katalog'";
        mysqli_query($koneksi, $query_upd_master);

        $keterangan_masuk = "STOK BARU";
        mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, jumlah, keterangan) 
                                VALUES ('$id_katalog', 'MASUK', '$stok_masuk', '$keterangan_masuk')");

        echo "<script>alert('Barang Masuk Berhasil Dicatat, Stok Master Bertambah & Jurnal Tercatat!'); window.location='../barang_tampil.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
} 

// edit
if (isset($_POST['edit_barang'])) {
    $id_barang  = $_POST['id_barang'];
    $id_katalog = $_POST['id_katalog'];
    $harga      = $_POST['harga'];
    $stok_baru  = $_POST['stok_masuk']; 

    $S_lama = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_katalog, stok_masuk FROM tabel_barang WHERE id_barang='$id_barang'"));
    $stok_lama       = $S_lama['stok_masuk'];
    $id_katalog_lama = $S_lama['id_katalog'];

    $query_edit = "UPDATE tabel_barang SET id_katalog='$id_katalog', harga='$harga', stok_masuk='$stok_baru' WHERE id_barang='$id_barang'";
    
    if (mysqli_query($koneksi, $query_edit)) {
        
        mysqli_query($koneksi, "UPDATE master_katalog SET stok = stok - $stok_lama WHERE id_katalog = '$id_katalog_lama'");
        mysqli_query($koneksi, "UPDATE master_katalog SET stok = stok + $stok_baru WHERE id_katalog = '$id_katalog'");

        $keterangan_batal = "EDIT DATA: PEMBATALAN STOK SEBELUMNYA";
        mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, jumlah, keterangan) 
                                VALUES ('$id_katalog_lama', 'KELUAR', '$stok_lama', '$keterangan_batal')");
        
        $keterangan_koreksi = "EDIT DATA, PENYESUAIAN STOK BARU";
        mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, jumlah, keterangan) 
                                VALUES ('$id_katalog', 'MASUK', '$stok_baru', '$keterangan_koreksi')");

        echo "<script>alert('Catatan Barang Masuk Berhasil Diubah & Jurnal Riwayat Disinkronkan!'); window.location='../barang_tampil.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

//hapus
if (isset($_POST['hapus_barang'])) {
    $id_barang = $_POST['id_barang'];
    
    // Ambil info data sebelum dihapus agar stok master bisa dikurangi kembali secara otomatis
    $S_lama = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_katalog, stok_masuk FROM tabel_barang WHERE id_barang='$id_barang'"));
    $stok_lama  = $S_lama['stok_masuk'];
    $id_katalog = $S_lama['id_katalog'];

    $query_hapus = "DELETE FROM tabel_barang WHERE id_barang='$id_barang'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        
        mysqli_query($koneksi, "UPDATE master_katalog SET stok = stok - $stok_lama WHERE id_katalog = '$id_katalog'");

        $keterangan_hapus = "STOK di hapus";
        mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, jumlah, keterangan) 
                                VALUES ('$id_katalog', 'KELUAR', '$stok_lama', '$keterangan_hapus')");

        echo "<script>alert('Catatan Berhasil Dihapus & Stok Logika Keluar Berhasil Dicatat!'); window.location='../barang_tampil.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>