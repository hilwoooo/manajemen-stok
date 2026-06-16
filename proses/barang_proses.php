<?php
include('../config/koneksi.php');

global $koneksi;
//tambah barang 
if (isset($_POST['tambah_barang'])) {
    // input
    $id_katalog = mysqli_real_escape_string($koneksi, $_POST['id_katalog']);
    $harga      = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $stok_masuk = (int)$_POST['stok_masuk']; 

    // Validasi produk tidak kosong
    if (empty($id_katalog)) {
        echo "<script>alert('Pilih produk katalog terlebih dahulu!'); window.history.back();</script>";
        exit();
    }

    // Query untuk menyimpan barang masuk
    $query_ins = "INSERT INTO tabel_barang (id_barang, id_katalog, harga, stok_masuk) 
                  VALUES (NULL, '$id_katalog', '$harga', '$stok_masuk')";
    
    if (mysqli_query($koneksi, $query_ins)) {
        
        // upadate penambahan stok ke master
        $query_upd_master = "UPDATE master_katalog SET stok = stok + $stok_masuk WHERE id_katalog = '$id_katalog'";
        mysqli_query($koneksi, $query_upd_master);

        // Mencatat ke riwayat stok
        $keterangan_masuk = "STOK BARU";
        mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, qty, keterangan) 
                                VALUES ('$id_katalog', 'MASUK', '$stok_masuk', '$keterangan_masuk')");

        echo "<script>alert('Barang Masuk Berhasil Dicatat, Stok Master Bertambah!'); window.location='../views/barang/barang_tampil.php';</script>";
        exit();
    } else {
        echo "Error pada Tambah Barang: " . mysqli_error($koneksi);
    }
}
//edit
if (isset($_POST['edit_barang'])) {
    // input data edit
    $id_barang  = mysqli_real_escape_string($koneksi, $_POST['id_barang']);
    $id_katalog = mysqli_real_escape_string($koneksi, $_POST['id_katalog']);
    $harga      = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $stok_baru  = mysqli_real_escape_string($koneksi, $_POST['stok_masuk']); 

    // mengambil id katalog lama dan stok masuk yang lama
    $S_lama = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_katalog, stok_masuk FROM tabel_barang WHERE id_barang='$id_barang'"));
    $stok_lama       = $S_lama['stok_masuk'];
    $id_katalog_lama = $S_lama['id_katalog'];

    // Query update data barang masuk
    $query_edit = "UPDATE tabel_barang SET id_katalog='$id_katalog', harga='$harga', stok_masuk='$stok_baru' WHERE id_barang='$id_barang'";
    
    if (mysqli_query($koneksi, $query_edit)) {
        
        // update pengurangan stok katalog lama di master
        mysqli_query($koneksi, "UPDATE master_katalog SET stok = stok - $stok_lama WHERE id_katalog = '$id_katalog_lama'");
        // update penambahan stok katalog baru di master
        mysqli_query($koneksi, "UPDATE master_katalog SET stok = stok + $stok_baru WHERE id_katalog = '$id_katalog'");

        // Mencatat pembatalan stok lama ke riwayat stok
        $keterangan_batal = "EDIT DATA: PEMBATALAN STOK SEBELUMNYA";
        mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, qty, keterangan) 
                                VALUES ('$id_katalog_lama', 'KELUAR', '$stok_lama', '$keterangan_batal')");
        
        // Mencatat penyesuaian stok baru ke riwayat stok
        $keterangan_koreksi = "EDIT DATA, PENYESUAIAN STOK BARU";
        mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, qty, keterangan) 
                                VALUES ('$id_katalog', 'MASUK', '$stok_baru', '$keterangan_koreksi')");

        echo "<script>alert('Catatan Barang Masuk Berhasil Diubah!'); window.location='../views/barang/barang_tampil.php';</script>";
        exit();
    } else {
        echo "Error pada Edit Barang: " . mysqli_error($koneksi);
    }
}
//hapus
if (isset($_POST['hapus_barang'])) {
    // input id barang yang mau dihapus
    $id_barang = mysqli_real_escape_string($koneksi, $_POST['id_barang']);
    
    // mengambil id katalog dan stok masuk sebelum dihapus
    $S_lama = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_katalog, stok_masuk FROM tabel_barang WHERE id_barang='$id_barang'"));
    $stok_lama  = $S_lama['stok_masuk'];
    $id_katalog = $S_lama['id_katalog'];

    // Query hapus data barang
    $query_hapus = "DELETE FROM tabel_barang WHERE id_barang='$id_barang'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        
        // update pengurangan stok karena data dihapus
        mysqli_query($koneksi, "UPDATE master_katalog SET stok = stok - $stok_lama WHERE id_katalog = '$id_katalog'");

        // Mencatat ke riwayat stok pengurangan barang
        $keterangan_hapus = "STOK di hapus";
        mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, qty, keterangan) 
                                VALUES ('$id_katalog', 'KELUAR', '$stok_lama', ' $keterangan_hapus')");

        echo "<script>alert('Catatan Berhasil Dihapus'); window.location='../views/barang/barang_tampil.php';</script>";
        exit();
    } else {
        echo "Error pada Hapus Barang: " . mysqli_error($koneksi);
    }
}
?>