-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 02 Jun 2026 pada 06.14
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_ppl`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_katalog`
--

CREATE TABLE `master_katalog` (
  `id_katalog` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `merek` varchar(50) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `master_katalog`
--

INSERT INTO `master_katalog` (`id_katalog`, `nama_produk`, `merek`, `jenis`, `tanggal`, `stok`) VALUES
(4, 'RAM 8GB TESS1', 'TESS1', 'Sparepart', '2026-06-01 15:07:54', 19),
(5, 'RAM 16GB TESS2', 'TESS2', 'Sparepart', '2026-06-01 15:08:21', 19);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_barang`
--

CREATE TABLE `tabel_barang` (
  `id_barang` int(11) NOT NULL,
  `id_katalog` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok_masuk` int(11) NOT NULL,
  `tanggal_masuk` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tabel_barang`
--

INSERT INTO `tabel_barang` (`id_barang`, `id_katalog`, `harga`, `stok_masuk`, `tanggal_masuk`) VALUES
(7, 5, 250000, 9, '2026-06-01 15:08:45'),
(8, 4, 100000, 19, '2026-06-01 15:09:02'),
(9, 5, 200000, 10, '2026-06-02 03:18:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_keluar`
--

CREATE TABLE `tabel_keluar` (
  `id_keluar` int(11) NOT NULL,
  `tanggal_keluar` timestamp NOT NULL DEFAULT current_timestamp(),
  `nama_pembeli` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT '-',
  `id_barang` int(11) NOT NULL,
  `total_bayar` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tabel_keluar`
--

INSERT INTO `tabel_keluar` (`id_keluar`, `tanggal_keluar`, `nama_pembeli`, `no_hp`, `id_barang`, `total_bayar`) VALUES
(3, '2026-06-01 15:09:27', 'DUDUNG', '-', 7, 250000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_menu`
--

CREATE TABLE `tabel_menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `parent_id` int(11) DEFAULT 0,
  `urutan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tabel_menu`
--

INSERT INTO `tabel_menu` (`id_menu`, `nama_menu`, `link`, `icon`, `parent_id`, `urutan`) VALUES
(1, 'Dashboard', 'index.php', 'fas fa-fw fa-tachometer-alt', 0, 1),
(2, 'Manajemen Gudang', '#', 'fas fa-fw fa-boxes', 0, 2),
(3, 'Ruang Teknisi', '#', 'fas fa-fw fa-tools', 0, 3),
(4, 'Barang Masuk', 'barang_tampil.php', '', 2, 1),
(5, 'Antrean Servis', 'servis_tampil.php', '', 3, 1),
(6, 'Riwayat Servis Selesai', 'riwayat_servis.php', '', 3, 2),
(7, 'Master Katalog Produk', 'katalog_tampil.php', '', 0, 1),
(8, 'Penjualan', 'penjualan_tampil.php', NULL, 2, 2),
(9, 'Riwayat', 'riwayat_stok.php', NULL, 0, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_riwayat_stok`
--

CREATE TABLE `tabel_riwayat_stok` (
  `id_riwayat` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_katalog` int(11) NOT NULL,
  `jenis_arus` enum('MASUK','KELUAR') NOT NULL,
  `jumlah` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Struktur dari tabel `tabel_kategori`
--
CREATE TABLE tabel_kategori (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL
);

--
-- Tambah Data di Tabel Kategori
--
INSERT INTO tabel_kategori (nama_kategori) VALUES
('Sparepart'),
('Unit Laptop');




--
-- Dumping data untuk tabel `tabel_riwayat_stok`
--

INSERT INTO `tabel_riwayat_stok` (`id_riwayat`, `tanggal`, `id_katalog`, `jenis_arus`, `jumlah`, `keterangan`) VALUES
(9, '2026-06-01 15:08:45', 5, 'MASUK', 10, 'STOK BARU'),
(10, '2026-06-01 15:09:02', 4, 'MASUK', 20, 'STOK BARU'),
(11, '2026-06-01 15:09:28', 5, 'KELUAR', 1, 'TERJUAL KEPADA DUDUNG'),
(12, '2026-06-01 15:13:43', 4, 'KELUAR', 1, 'DIGUNAKAN UNTUK SERVIS LAPTOP [LAPTOP ABAL ABAL] AN. DUDUNG'),
(13, '2026-06-02 03:18:03', 5, 'MASUK', 10, 'STOK BARU');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_servis`
--

CREATE TABLE `tabel_servis` (
  `id_servis` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipe_laptop` varchar(100) NOT NULL,
  `keluhan` text NOT NULL,
  `status_servis` varchar(20) NOT NULL,
  `id_barang` int(11) DEFAULT NULL,
  `biaya_jasa` int(11) DEFAULT 0,
  `total_biaya` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tabel_servis`
--

INSERT INTO `tabel_servis` (`id_servis`, `nama_pelanggan`, `no_hp`, `tanggal`, `tipe_laptop`, `keluhan`, `status_servis`, `id_barang`, `biaya_jasa`, `total_biaya`) VALUES
(2, 'dudung', '08456789123', '2026-06-01 15:10:08', 'laptop abal abal', 'ram rusakkkkk', 'Selesai', 8, 100000, 200000),
(3, 'dudung', '08456789123', '2026-06-01 15:14:28', 'laptop abal abal', 'upgrade ram', 'Antrean', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_user`
--

CREATE TABLE `tabel_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tabel_user`
--

INSERT INTO `tabel_user` (`id_user`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'admin', 'admin123', 'Admin Toko');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `master_katalog`
--
ALTER TABLE `master_katalog`
  ADD PRIMARY KEY (`id_katalog`);

--
-- Indeks untuk tabel `tabel_barang`
--
ALTER TABLE `tabel_barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `id_katalog` (`id_katalog`);

--
-- Indeks untuk tabel `tabel_keluar`
--
ALTER TABLE `tabel_keluar`
  ADD PRIMARY KEY (`id_keluar`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `tabel_menu`
--
ALTER TABLE `tabel_menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `tabel_riwayat_stok`
--
ALTER TABLE `tabel_riwayat_stok`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_katalog` (`id_katalog`);

--
-- Indeks untuk tabel `tabel_servis`
--
ALTER TABLE `tabel_servis`
  ADD PRIMARY KEY (`id_servis`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `tabel_user`
--
ALTER TABLE `tabel_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `master_katalog`
--
ALTER TABLE `master_katalog`
  MODIFY `id_katalog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tabel_barang`
--
ALTER TABLE `tabel_barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `tabel_keluar`
--
ALTER TABLE `tabel_keluar`
  MODIFY `id_keluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tabel_menu`
--
ALTER TABLE `tabel_menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `tabel_riwayat_stok`
--
ALTER TABLE `tabel_riwayat_stok`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `tabel_servis`
--
ALTER TABLE `tabel_servis`
  MODIFY `id_servis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tabel_user`
--
ALTER TABLE `tabel_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tabel_barang`
--
ALTER TABLE `tabel_barang`
  ADD CONSTRAINT `tabel_barang_ibfk_1` FOREIGN KEY (`id_katalog`) REFERENCES `master_katalog` (`id_katalog`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tabel_keluar`
--
ALTER TABLE `tabel_keluar`
  ADD CONSTRAINT `tabel_keluar_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `tabel_barang` (`id_barang`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tabel_riwayat_stok`
--
ALTER TABLE `tabel_riwayat_stok`
  ADD CONSTRAINT `tabel_riwayat_stok_ibfk_1` FOREIGN KEY (`id_katalog`) REFERENCES `master_katalog` (`id_katalog`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tabel_servis`
--
ALTER TABLE `tabel_servis`
  ADD CONSTRAINT `tabel_servis_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `tabel_barang` (`id_barang`) ON DELETE SET NULL;
COMMIT;


ALTER TABLE `master_katalog` ADD FOREIGN KEY (`id_kategori`) REFERENCES `tabel_kategori`(`id_kategori`) ON DELETE RESTRICT ON UPDATE RESTRICT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
