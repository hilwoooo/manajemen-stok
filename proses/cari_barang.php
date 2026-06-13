<?php
require_once __DIR__ . '/../Config/koneksi.php';

//fungsi untuk membuat pencarian barang pada halaman barang maauk
function cariItems($keyword)
{

    global $koneksi;


    $query = "SELECT b.*, k.nama_produk, k.merek, tk.nama_kategori
                                  FROM tabel_barang AS b
                                  INNER JOIN master_katalog AS k ON b.id_katalog = k.id_katalog
                                  INNER JOIN tabel_kategori AS tk ON k.id_kategori = tk.id_kategori
                                  WHERE k.nama_produk LIKE '%$keyword%' OR 
                                  k.merek LIKE '%$keyword%' OR
                                  b.harga LIKE '%$keyword%' OR
                                  tk.nama_kategori LIKE '%$keyword%' OR
                                  k.merek LIKE '%$keyword%' 
                                  ORDER BY b.id_barang DESC";
    $query_search = mysqli_query($koneksi, $query);
    return $query_search;
}

?>