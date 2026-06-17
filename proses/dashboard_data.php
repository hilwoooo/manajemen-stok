<?php
require_once __DIR__ . '/../Config/koneksi.php';

// Total Jenis Barang
$q_barang = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tabel_barang");
$d_barang = mysqli_fetch_array($q_barang);
$total_barang = $d_barang['total'];

// Jumlah Antrean Servis Masuk
$q_antrean = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tabel_servis WHERE status_servis='Antrean'");
$d_antrean = mysqli_fetch_array($q_antrean);
$total_antrean = $d_antrean['total'];

// Jumlah Servis yang Sedang Diproses
$q_proses = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tabel_servis WHERE status_servis='Proses'");
$d_proses = mysqli_fetch_array($q_proses);
$total_proses = $d_proses['total'];

// --- BERIKUT ADALAH PENAMBAHAN UNTUK SERVIS YANG SUDAH DIKERJAKAN (SELESAI) ---
$q_selesai = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tabel_servis WHERE status_servis='Selesai'");
$d_selesai = mysqli_fetch_array($q_selesai);
$total_selesai = $d_selesai['total'];
// -----------------------------------------------------------------------------

// Total Pendapatan dari Servis Selesai
$q_pendapatan = mysqli_query($koneksi, "SELECT SUM(total_biaya) AS total FROM tabel_servis WHERE status_servis='Selesai'");
$d_pendapatan = mysqli_fetch_array($q_pendapatan);
$total_pendapatan = $d_pendapatan['total'] ?? 0; 

// Jumlah Transaksi Penjualan Produk/Sparepart
$q_penjualan = mysqli_query($koneksi, "SELECT COUNT(*) AS penjualan
    FROM tabel_keluar AS penjualan
    INNER JOIN tabel_barang AS b 
        ON penjualan.id_barang = b.id_barang
    INNER JOIN master_katalog AS k 
        ON b.id_katalog = k.id_katalog
    INNER JOIN tabel_kategori AS kt 
        ON k.id_kategori = kt.id_kategori");
$r_penjualan = mysqli_fetch_array($q_penjualan);
$total_penjualan = ($r_penjualan['penjualan'] > 0) ? $r_penjualan['penjualan'] : 0;

// Total Seluruh Riwayat Servis (Semua Status)
$q_servis_count = mysqli_query($koneksi, "SELECT COUNT(*) AS jumlah_service FROM tabel_servis");
$d_serv = mysqli_fetch_array($q_servis_count);
$jumlah_service = ($d_serv['jumlah_service'] > 0 ) ? $d_serv['jumlah_service'] : 0;

// Total Pendapatan dari Penjualan
$q_pendapatan_penjualan = mysqli_query($koneksi, "SELECT SUM(total_bayar) AS total FROM tabel_keluar");
$d_pendapatan_penjualan = mysqli_fetch_array($q_pendapatan_penjualan);
$total_pendapatan_penjualan = $d_pendapatan_penjualan['total'] ?? 0; 

// --- BERIKUT ADALAH GABUNGAN TOTAL PENDAPATAN (SERVIS + PENJUALAN) ---
$total_hasil_seluruhnya = $total_pendapatan + $total_pendapatan_penjualan;
?>