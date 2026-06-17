-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 17 Jun 2026 pada 04.56
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
-- Database: `store_computer`
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
  `stok` int(11) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `master_katalog`
--

INSERT INTO `master_katalog` (`id_katalog`, `nama_produk`, `merek`, `jenis`, `tanggal`, `stok`, `id_kategori`) VALUES
(1, 'MOUSE ROBOT M102', 'ROBOT', 'AKSESORIS', '2026-06-16 07:03:05', 19, 3),
(2, 'M90 MOUSE', 'LOGITECH', 'AKSESORIS', '2026-06-16 07:04:09', 30, 3),
(3, 'SSD SATA 128GB', 'OVATION', 'SPAREPART', '2026-06-16 07:04:52', 16, 1),
(4, 'SSD SATA 1TB', 'OVATION', 'SPAREPART', '2026-06-16 07:05:24', 5, 1),
(5, 'SSD M.2 SATA 128GB', 'VENOMRX', 'SPAREPART', '2026-06-16 07:06:58', 9, 1),
(6, 'MOTHERBOARD H610M K DDR4', 'GIGABYTE ', 'SPAREPART', '2026-06-16 07:11:22', 12, 1),
(7, 'THINKPAD T480', 'LENOVO', 'LAPTOP', '2026-06-16 07:13:14', 12, 2),
(8, 'PENDINGIN PROCESSOR', 'THERMAL PASTA', 'SPAREPART', '2026-06-16 13:45:11', 79, 1);

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
(15, 7, 3500000, 12, '2026-06-16 07:14:20'),
(16, 2, 66000, 30, '2026-06-16 07:15:28'),
(17, 6, 1200000, 7, '2026-06-16 07:28:54'),
(18, 1, 33500, 19, '2026-06-16 07:30:10'),
(19, 5, 840000, 9, '2026-06-16 07:30:48'),
(20, 3, 467000, 16, '2026-06-16 07:31:19'),
(21, 4, 2200000, 5, '2026-06-16 10:15:26'),
(22, 6, 1200000, 5, '2026-06-16 10:16:01'),
(23, 8, 2400, 49, '2026-06-16 13:45:36'),
(24, 8, 2400, 30, '2026-06-16 13:45:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_detail_servis`
--

CREATE TABLE `tabel_detail_servis` (
  `id_detail` int(11) NOT NULL,
  `id_servis` int(11) DEFAULT NULL,
  `id_barang` varchar(50) DEFAULT NULL,
  `harga_satuan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tabel_detail_servis`
--

INSERT INTO `tabel_detail_servis` (`id_detail`, `id_servis`, `id_barang`, `harga_satuan`) VALUES
(12, 23, '21', 2200000),
(13, 26, '23', 2400),
(14, 26, '21', 2200000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_kategori`
--

CREATE TABLE `tabel_kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tabel_kategori`
--

INSERT INTO `tabel_kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Sparepart'),
(2, 'Unit Laptop'),
(3, 'Aksesoris');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_keluar`
--

CREATE TABLE `tabel_keluar` (
  `id_keluar` int(11) NOT NULL,
  `tanggal_keluar` timestamp NOT NULL DEFAULT current_timestamp(),
  `nama_pembeli` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT '-',
  `total_bayar` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `id_barang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tabel_keluar`
--

INSERT INTO `tabel_keluar` (`id_keluar`, `tanggal_keluar`, `nama_pembeli`, `no_hp`, `total_bayar`, `qty`, `id_barang`) VALUES
(6, '2026-06-16 10:16:52', 'DUDUNG', '08423456787', 1200000, 1, 17),
(7, '2026-06-16 10:16:52', 'DUDUNG', '08423456787', 840000, 1, 19),
(8, '2026-06-16 10:16:52', 'DUDUNG', '08423456787', 33500, 1, 18);

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
(4, 'Barang Masuk', 'views/barang/barang_tampil.php', '', 2, 1),
(5, 'Antrean Servis', 'views/service/service_tampil.php', '', 3, 1),
(6, 'Riwayat Servis Selesai', 'views/service/riwayat_service.php', '', 3, 2),
(7, 'Master Katalog Produk', 'views/katalog/katalog_tampil.php', 'fas fa-solid fa-key', 0, 1),
(8, 'Penjualan', 'views/penjualan/penjualan_tampil.php', NULL, 2, 2),
(9, 'Riwayat', 'views/riwayat/riwayat_stok.php', 'fas fa-file', 0, 3),
(10, 'Laporan', 'views/laporan/laporan_iventaris.php', 'fas fa-solid fa-copy', 0, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_riwayat_stok`
--

CREATE TABLE `tabel_riwayat_stok` (
  `id_riwayat` int(11) NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_katalog` int(11) NOT NULL,
  `jenis_arus` enum('MASUK','KELUAR') NOT NULL,
  `qty` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tabel_riwayat_stok`
--

INSERT INTO `tabel_riwayat_stok` (`id_riwayat`, `tanggal`, `id_katalog`, `jenis_arus`, `qty`, `keterangan`) VALUES
(42, '2026-06-16 07:14:20', 7, 'MASUK', 12, 'STOK BARU'),
(43, '2026-06-16 07:15:29', 2, 'MASUK', 30, 'STOK BARU'),
(44, '2026-06-16 07:28:54', 6, 'MASUK', 8, 'STOK BARU'),
(45, '2026-06-16 07:30:11', 1, 'MASUK', 20, 'STOK BARU'),
(46, '2026-06-16 07:30:48', 5, 'MASUK', 10, 'STOK BARU'),
(47, '2026-06-16 07:31:19', 3, 'MASUK', 16, 'STOK BARU'),
(48, '2026-06-16 10:15:26', 4, 'MASUK', 7, 'STOK BARU'),
(49, '2026-06-16 10:16:01', 6, 'MASUK', 5, 'STOK BARU'),
(50, '2026-06-16 10:16:52', 6, 'KELUAR', 1, 'TERJUAL KEPADA DUDUNG (QTY: 1)'),
(51, '2026-06-16 10:16:52', 5, 'KELUAR', 1, 'TERJUAL KEPADA DUDUNG (QTY: 1)'),
(52, '2026-06-16 10:16:52', 1, 'KELUAR', 1, 'TERJUAL KEPADA DUDUNG (QTY: 1)'),
(53, '2026-06-16 13:40:30', 4, 'KELUAR', 1, 'DIGUNAKAN UNTUK SERVIS LAPTOP [LENOVO] AN. ABUY (QTY: 1)'),
(54, '2026-06-16 13:45:36', 8, 'MASUK', 50, 'STOK BARU'),
(55, '2026-06-16 13:45:53', 8, 'MASUK', 30, 'STOK BARU'),
(56, '2026-06-16 13:56:04', 8, 'KELUAR', 1, 'DIGUNAKAN UNTUK SERVIS LAPTOP [LENOVO THINKPAD] AN. SAMSUL (QTY: 1)'),
(57, '2026-06-16 13:56:04', 4, 'KELUAR', 1, 'DIGUNAKAN UNTUK SERVIS LAPTOP [LENOVO THINKPAD] AN. SAMSUL (QTY: 1)');

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
(23, 'abuy', '0823456786', '2026-06-16 10:17:51', 'lenovo', 'laptop ganti ssd', 'Selesai', 21, 20000, 2220000),
(24, 'cimot', '0865433456', '2026-06-16 10:18:32', 'lenovo thinkpad', 'ganti ssd dan ram', 'Antrean', NULL, 0, 0),
(25, 'ciki', '08345677654', '2026-06-16 10:19:21', 'chromebook', 'benerin yg lepas', 'Selesai', NULL, 20000, 20000),
(26, 'samsul', '08987654323', '2026-06-16 13:46:47', 'lenovo thinkpad', 'ganti thermal pasta dan ganti ssd', 'Selesai', 23, 20000, 2222400);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tabel_user`
--

CREATE TABLE `tabel_user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tabel_user`
--

INSERT INTO `tabel_user` (`id_user`, `nama`, `password`, `email`) VALUES
(3, 'dudung', '$2y$10$6NF1NjzeXqkMVtfyFHJis./6naOFpaV0H/ovAzSaNHoD6AMa43lG6', 'dudung@gmail.com');

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
-- Indeks untuk tabel `tabel_detail_servis`
--
ALTER TABLE `tabel_detail_servis`
  ADD PRIMARY KEY (`id_detail`);

--
-- Indeks untuk tabel `tabel_kategori`
--
ALTER TABLE `tabel_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `tabel_keluar`
--
ALTER TABLE `tabel_keluar`
  ADD PRIMARY KEY (`id_keluar`),
  ADD KEY `tabel_keluar_ibfk_1` (`id_barang`);

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
  MODIFY `id_katalog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tabel_barang`
--
ALTER TABLE `tabel_barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `tabel_detail_servis`
--
ALTER TABLE `tabel_detail_servis`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `tabel_kategori`
--
ALTER TABLE `tabel_kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `tabel_keluar`
--
ALTER TABLE `tabel_keluar`
  MODIFY `id_keluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tabel_menu`
--
ALTER TABLE `tabel_menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `tabel_riwayat_stok`
--
ALTER TABLE `tabel_riwayat_stok`
  MODIFY `id_riwayat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT untuk tabel `tabel_servis`
--
ALTER TABLE `tabel_servis`
  MODIFY `id_servis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `tabel_user`
--
ALTER TABLE `tabel_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
