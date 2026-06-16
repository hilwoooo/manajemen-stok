<?php
include('../config/koneksi.php');

global $koneksi;

// tambah penjualan (Menyesuaikan tabel_keluar lama + Kolom Qty)
if (isset($_POST['tambah_penjualan'])) {

    // Ambil data pembeli
    $nama_pembeli = mysqli_real_escape_string($koneksi, strtoupper($_POST['nama_pembeli']));
    $no_hp        = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    
    // Ambil data barang & qty berbentuk ARRAY dari form input dinamis
    $id_barang_array = $_POST['id_barang']; // Dari name="id_barang[]"
    $qty_array       = $_POST['qty'];    // Dari name="jumlah[]"

    // Validasi awal: Pastikan ada barang yang dipilih
    if (empty($id_barang_array) || count($id_barang_array) == 0) {
        echo "<script>alert('Pilih minimal satu barang!'); window.history.back();</script>";
        exit();
    }

    // TAHAP 1: Gabungkan Qty jika ada item ID Barang yang sama dipilih di baris berbeda
    $barang_tergabung = [];
    foreach ($id_barang_array as $index => $id_barang) {
        if (empty($id_barang)) continue; // Skip jika ada dropdown yang kosong

        $id_barang = mysqli_real_escape_string($koneksi, $id_barang);
        $qty_beli  = (int)$qty_array[$index];

        if (isset($barang_tergabung[$id_barang])) {
            $barang_tergabung[$id_barang] += $qty_beli;
        } else {
            $barang_tergabung[$id_barang] = $qty_beli;
        }
    }

    // TAHAP 2: Validasi Stok terlebih dahulu sebelum melakukan transaksi
    $data_siap_simpan = [];
    foreach ($barang_tergabung as $id_barang => $total_qty_beli) {
        
        $cari_barang = mysqli_query($koneksi, "SELECT id_katalog, harga, stok_masuk FROM tabel_barang WHERE id_barang = '$id_barang' LIMIT 1");
        
        if (mysqli_num_rows($cari_barang) > 0) {
            $data_barang = mysqli_fetch_assoc($cari_barang);
            
            // Cek stok_masuk (Batch FIFO) cukup atau tidak
            if ($data_barang['stok_masuk'] < $total_qty_beli) {
                echo "<script>
                    alert('Stok salah satu barang tidak mencukupi untuk total Qty yang diminta!');
                    window.location='../views/penjualan/penjualan_tampil.php';
                </script>";
                exit();
            }

            // Hitung total bayar (Harga x Qty Beli) untuk baris tersebut
            $total_bayar_per_item = $data_barang['harga'] * $total_qty_beli;

            $data_siap_simpan[] = [
                'id_barang'   => $id_barang,
                'id_katalog'  => $data_barang['id_katalog'],
                'qty'         => $total_qty_beli,
                'total_bayar' => $total_bayar_per_item
            ];
        } else {
            echo "<script>alert('Salah satu barang tidak ditemukan!'); window.location='../views/penjualan/penjualan_tampil.php';</script>";
            exit();
        }
    }

    // TAHAP 3: Eksekusi simpan berulang (Looping) ke tabel_keluar bawaan kamu
    if (!empty($data_siap_simpan)) {
        $sukses = true;

        foreach ($data_siap_simpan as $item) {
            $id_barang   = $item['id_barang'];
            $id_katalog  = $item['id_katalog'];
            $qty         = $item['qty'];
            $total_bayar = $item['total_bayar'];

            // 1. Insert langsung ke tabel_keluar kamu (Akan looping kalau barangnya beda-beda)
            $query_insert = "INSERT INTO tabel_keluar (nama_pembeli, no_hp, id_barang, qty, total_bayar) 
                             VALUES ('$nama_pembeli', '$no_hp', '$id_barang', '$qty', '$total_bayar')";
            
            if (mysqli_query($koneksi, $query_insert)) {
                
                // 2. Kurangi stok barang masuk (tabel_barang)
                mysqli_query($koneksi, "UPDATE tabel_barang SET stok_masuk = stok_masuk - $qty WHERE id_barang = '$id_barang' LIMIT 1");

                // 3. Kurangi stok induk (master_katalog)
                mysqli_query($koneksi, "UPDATE master_katalog SET stok = stok - $qty WHERE id_katalog = '$id_katalog'");

                // 4. Catat riwayat stok keluar
                $keterangan = "TERJUAL KEPADA " . $nama_pembeli . " (QTY: " . $qty . ")";
                mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, qty, keterangan) 
                                 VALUES ('$id_katalog', 'KELUAR', '$qty', '$keterangan')");
            } else {
                $sukses = false;
                echo "Error: " . mysqli_error($koneksi);
                break;
            }
        }

        if ($sukses) {
            echo "<script>
                alert('Penjualan berhasil disimpan!');
                window.location='../views/penjualan/penjualan_tampil.php';
            </script>";
            exit();
        }
    }
}
?>