<?php
require_once __DIR__ . '/../Config/koneksi.php';

// 1. Hitung Total Jenis Barang
$q_barang = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tabel_barang");
$d_barang = mysqli_fetch_array($q_barang);
$total_barang = $d_barang['total'];

// 2. Hitung Jumlah Antrean Servis Masuk
$q_antrean = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tabel_servis WHERE status_servis='Antrean'");
$d_antrean = mysqli_fetch_array($q_antrean);
$total_antrean = $d_antrean['total'];

// 3. Hitung Jumlah Servis yang Sedang Diproses
$q_proses = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tabel_servis WHERE status_servis='Proses'");
$d_proses = mysqli_fetch_array($q_proses);
$total_proses = $d_proses['total'];

// 4. Hitung Total Pendapatan dari Servis Selesai
$q_pendapatan = mysqli_query($koneksi, "SELECT SUM(total_biaya) AS total FROM tabel_servis WHERE status_servis='Selesai'");
$d_pendapatan = mysqli_fetch_array($q_pendapatan);
$total_pendapatan = $d_pendapatan['total'] ?? 0; // Jika masih kosong, di-set ke angka 0
?>