<?php
require_once __DIR__ . '/../Config/koneksi.php';

//fungsi membuat filter kategori bedarsarkan jenis barang pada halaman barang masuk
function filterBarang($id_kategori) {
    $get_id = $id_kategori;

    global $koneksi;
    $query = "SELECT b.*, k.nama_produk, k.merek, tk.nama_kategori
                                  FROM tabel_barang AS b
                                  INNER JOIN master_katalog AS k ON b.id_katalog = k.id_katalog
                                  INNER JOIN tabel_kategori AS tk ON k.id_kategori = tk.id_kategori
                                  WHERE tk.id_kategori = $get_id
                                  ORDER BY b.id_barang DESC";
    $tampil = mysqli_query($koneksi, $query);

    return $tampil;
}

function filterPenjualan($id_kategori) {

    global $koneksi;

    $query = "SELECT 
                    tk.*,
                    b.harga,
                    b.tanggal_masuk,
                    k.nama_produk,
                    k.merek,
                    kt.nama_kategori
              FROM tabel_keluar AS tk
              INNER JOIN tabel_barang AS b 
              ON tk.id_barang = b.id_barang
              INNER JOIN master_katalog AS k 
              ON b.id_katalog = k.id_katalog
              INNER JOIN tabel_kategori AS kt 
              ON k.id_kategori = kt.id_kategori
              WHERE kt.id_kategori = '$id_kategori'
              ORDER BY tk.id_keluar DESC";

    $tampil = mysqli_query($koneksi, $query);
    return $tampil;

}
?>