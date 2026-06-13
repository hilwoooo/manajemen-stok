<?php
require_once __DIR__ . '/../Config/koneksi.php';

global $koneksi;

// ====================================================================
// 1. PROSES TAMBAH ANTREAN SERVIS
// ====================================================================
if (isset($_POST['tambah_servis'])) {
    $nama_pelanggan = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $no_hp          = $_POST['no_hp'];
    $tipe_laptop    = mysqli_real_escape_string($koneksi, $_POST['tipe_laptop']);
    $keluhan        = mysqli_real_escape_string($koneksi, $_POST['keluhan']);

    $query_tambah = "INSERT INTO tabel_servis (nama_pelanggan, no_hp, tipe_laptop, keluhan, status_servis) 
                     VALUES ('$nama_pelanggan', '$no_hp', '$tipe_laptop', '$keluhan', 'Antrean')";
    
    if (mysqli_query($koneksi, $query_tambah)) {
        echo "<script>alert('Pendaftaran Antrean Servis Berhasil!'); window.location='../servis_tampil.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

// ====================================================================
// 2. PROSES SERVIS SELESAI (POTONG STOK & CATAT JURNAL GABUNGAN)
// ====================================================================
if (isset($_POST['servis_selesai'])) {
    $id_servis  = $_POST['id_servis'];
    $id_barang  = $_POST['id_barang']; 
    $biaya_jasa = $_POST['biaya_jasa'];
    
    $harga_sparepart = 0;
    $id_katalog      = "";

    // Jika teknisi memilih ganti sparepart, cari harga beserta id_katalog-nya
    if (!empty($id_barang)) {
        $cari_barang = mysqli_query($koneksi, "SELECT id_katalog, harga FROM tabel_barang WHERE id_barang = '$id_barang'");
        $data_barang = mysqli_fetch_array($cari_barang);
        
        $harga_sparepart = $data_barang['harga'];
        $id_katalog      = $data_barang['id_katalog']; // Diperlukan untuk memotong stok di master
    }

    // Hitung Total Biaya otomatis
    $total_biaya = $biaya_jasa + $harga_sparepart;

    // Ambil info nama pelanggan dan tipe laptop terlebih dahulu untuk keperluan teks keterangan di riwayat_stok
    $cari_info_servis = mysqli_query($koneksi, "SELECT nama_pelanggan, tipe_laptop FROM tabel_servis WHERE id_servis = '$id_servis'");
    $data_info_servis = mysqli_fetch_array($cari_info_servis);
    $nama_pelanggan   = strtoupper($data_info_servis['nama_pelanggan']);
    $tipe_laptop      = strtoupper($data_info_servis['tipe_laptop']);

    // A. Update data servis menjadi Selesai beserta rincian biayanya
    $kolom_barang = !empty($id_barang) ? "'$id_barang'" : "NULL";
    
    $query_update_servis = "UPDATE tabel_servis SET 
                            status_servis = 'Selesai', 
                            id_barang = $kolom_barang, 
                            biaya_jasa = '$biaya_jasa', 
                            total_biaya = '$total_biaya' 
                            WHERE id_servis = '$id_servis'";
    
    $eksekusi_servis = mysqli_query($koneksi, $query_update_servis);

    if ($eksekusi_servis) {
        
        // B. JIKA GANTI SPAREPART, JALANKAN LOGIKA FIFO & CATAT JURNAL GABUNGAN
        if (!empty($id_barang) && !empty($id_katalog)) {
            
            // 1. Potong stok_masuk pada batch terlama di tabel_barang berkurang 1 (Sama seperti Kasir)
            mysqli_query($koneksi, "UPDATE tabel_barang SET stok_masuk = stok_masuk - 1 WHERE id_barang = '$id_barang'");

            // 2. Potong stok global di master_katalog berkurang 1
            $query_potong_stok = "UPDATE master_katalog SET stok = stok - 1 WHERE id_katalog = '$id_katalog'";
            mysqli_query($koneksi, $query_potong_stok);

            // 3. YANG BARU: Kirim catatan log KELUAR ke tabel_riwayat_stok
            $keterangan_servis = "DIGUNAKAN UNTUK SERVIS LAPTOP [" . $tipe_laptop . "] AN. " . $nama_pelanggan;
            mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, jumlah, keterangan) 
                                    VALUES ('$id_katalog', 'KELUAR', 1, '$keterangan_servis')");
        }

        echo "<script>alert('Servis Telah Selesai! Stok Batch (FIFO) & Master Katalog Berhasil Dipotong Serta Jurnal Tercatat.'); window.location='/manajemen-stok/Views/Service/service_tampil.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>