<?php
require_once __DIR__ . '/../Config/koneksi.php';


//fungsi menampilkan data tabel katalog
function tampilKatalog()
{
    global $koneksi;
    $query = "SELECT * FROM master_katalog AS mk JOIN tabel_kategori AS k ON mk.id_kategori = k.id_kategori  ORDER BY id_katalog DESC";
    $tampil = mysqli_query($koneksi, $query);

    return $tampil;
}

//fungsi menampilkan data tabel barang masuk
function tampilBarangMasuk()
{
    global $koneksi;
    $query = "SELECT b.*, k.nama_produk, k.merek, tk.nama_kategori
                                  FROM tabel_barang AS b
                                  INNER JOIN master_katalog AS k ON b.id_katalog = k.id_katalog
                                  INNER JOIN tabel_kategori AS tk ON k.id_kategori = tk.id_kategori
                                  ORDER BY b.id_barang DESC";
    $tampil = mysqli_query($koneksi, $query);

    return $tampil;
}

//fungsi menampilkan kategori barang
function getKategoriData()
{
    global $koneksi;

    $query = "SELECT * FROM tabel_kategori";
    $tampil = mysqli_query($koneksi, $query);

    return $tampil;
}

//fungsi menampilkan tabel penjualan barang
function getPenjualan()
{
    global $koneksi;
    $query = "SELECT tk.*, b.harga AS harga_modal, b.tanggal_masuk, k.nama_produk, k.merek,  kt.nama_kategori
                                  FROM tabel_keluar tk
                                  INNER JOIN tabel_barang AS b ON tk.id_barang = b.id_barang
                                  INNER JOIN master_katalog AS k ON b.id_katalog = k.id_katalog
                                  INNER JOIN tabel_kategori AS kt ON b.id_katalog = k.id_katalog
                                  ORDER BY tk.id_keluar DESC";

    $tampil = mysqli_query($koneksi, $query);

    return $tampil;
}
