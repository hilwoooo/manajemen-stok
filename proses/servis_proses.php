<?php
require_once __DIR__ . '/../Config/koneksi.php';

global $koneksi;

//tambah antrean
if (isset($_POST['tambah_servis'])) {
    // input data antrean servis baru
    $nama_pelanggan = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $no_hp          = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $tipe_laptop    = mysqli_real_escape_string($koneksi, $_POST['tipe_laptop']);
    $keluhan        = mysqli_real_escape_string($koneksi, $_POST['keluhan']);

    // Query untuk menyimpan data antrean baru
    $query_tambah = "INSERT INTO tabel_servis (nama_pelanggan, no_hp, tipe_laptop, keluhan, status_servis, total_biaya) 
                     VALUES ('$nama_pelanggan', '$no_hp', '$tipe_laptop', '$keluhan', 'Antrean', 0)";
    
    if (mysqli_query($koneksi, $query_tambah)) {
        echo "<script>alert('Pendaftaran Antrean Servis Berhasil!'); window.location='../views/service/service_tampil.php';</script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

// proses servis selesai
if (isset($_POST['servis_selesai'])) {

    // input data penyelesaian servis
    $id_servis  = mysqli_real_escape_string($koneksi, $_POST['id_servis']);
    $biaya_jasa = (int)$_POST['biaya_jasa'];
    
    // ambil data array sparepart dari form input dinamis
    $id_barang_array = isset($_POST['id_barang']) ? $_POST['id_barang'] : []; 

    // mengambil data pelanggan dan tipe laptop berdasarkan id servis
    $cari_antrean = mysqli_query($koneksi, "SELECT * FROM tabel_servis WHERE id_servis = '$id_servis'");
    $data_awal    = mysqli_fetch_array($cari_antrean);

    $nama_pelanggan = mysqli_real_escape_string($koneksi, $data_awal['nama_pelanggan']);
    $tipe_laptop    = mysqli_real_escape_string($koneksi, $data_awal['tipe_laptop']);

    $total_harga_sparepart = 0;
    $valid_barang_list = [];

    // proses validasi pengecekan stok sparepart
    foreach ($id_barang_array as $id_barang_mentah) {
        if (empty($id_barang_mentah)) continue; 

        $id_barang = mysqli_real_escape_string($koneksi, $id_barang_mentah);
        
        // mengambil data katalog, harga, dan stok masuk di tabel barang
        $cari_barang = mysqli_query($koneksi, "SELECT id_katalog, harga, stok_masuk FROM tabel_barang WHERE id_barang = '$id_barang' LIMIT 1");
        
        if (mysqli_num_rows($cari_barang) > 0) {
            $data_barang = mysqli_fetch_assoc($cari_barang);
            
            // Cek stok_masuk (Batch FIFO) cukup atau tidak
            if ($data_barang['stok_masuk'] < 1) {
                echo "<script>alert('Stok sparepart dengan ID " . $id_barang . " tidak mencukupi!'); window.location='../views/service/service_tampil.php';</script>";
                exit();
            }

            // hitung total harga sparepart yang digunakan
            $total_harga_sparepart += $data_barang['harga'];
            
            // tampung data sparepart yang lolos cek stok ke array
            $valid_barang_list[] = [
                'id_barang'    => $id_barang,
                'id_katalog'   => $data_barang['id_katalog'],
                'harga_satuan' => $data_barang['harga']
            ];
        }
    }

    // hitung grand total penjumlahan biaya jasa dan sparepart
    $grand_total_servis = $biaya_jasa + $total_harga_sparepart;

    // proses update data servis jika tanpa sparepart (hanya jasa)
    if (empty($valid_barang_list)) {
        mysqli_query($koneksi, "UPDATE tabel_servis SET 
                                status_servis = 'Selesai', 
                                id_barang = NULL, 
                                biaya_jasa = '$biaya_jasa', 
                                total_biaya = '$biaya_jasa' 
                                WHERE id_servis = '$id_servis'");

        echo "<script>alert('Servis Berhasil Diselesaikan (Hanya Jasa)!'); window.location='../views/service/service_tampil.php';</script>";
        exit();
    }

    $id_barang_utama = $valid_barang_list[0]['id_barang'];

    // Query update data servis selesai dengan sparepart
    $query_update_servis = "UPDATE tabel_servis SET 
                            status_servis = 'Selesai', 
                            id_barang = '$id_barang_utama', 
                            biaya_jasa = '$biaya_jasa', 
                            total_biaya = '$grand_total_servis' 
                            WHERE id_servis = '$id_servis'";

    if (mysqli_query($koneksi, $query_update_servis)) {
        
        // Query hapus detail servis lama untuk reset data
        mysqli_query($koneksi, "DELETE FROM tabel_detail_servis WHERE id_servis = '$id_servis'");

        // proses simpan detail sparepart dan update stok menggunakan looping
        foreach ($valid_barang_list as $item) {
            $id_barang    = $item['id_barang'];
            $id_katalog   = $item['id_katalog'];
            $harga_satuan = $item['harga_satuan'];

            // Query untuk menyimpan data ke tabel detail servis
            mysqli_query($koneksi, "INSERT INTO tabel_detail_servis (id_servis, id_barang, harga_satuan) 
                                    VALUES ('$id_servis', '$id_barang', '$harga_satuan')");

            // update pengurangan stok masuk di tabel barang
            mysqli_query($koneksi, "UPDATE tabel_barang SET stok_masuk = stok_masuk - 1 WHERE id_barang = '$id_barang' LIMIT 1");

            // update pengurangan stok ke master
            mysqli_query($koneksi, "UPDATE master_katalog SET stok = stok - 1 WHERE id_katalog = '$id_katalog'");

            // Mencatat ke riwayat stok penggunaan barang untuk servis
            $keterangan_log = "DIGUNAKAN UNTUK SERVIS LAPTOP [" . strtoupper($tipe_laptop) . "] AN. " . strtoupper($nama_pelanggan) . " (QTY: 1)";
            mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, qty, keterangan) 
                                    VALUES ('$id_katalog', 'KELUAR', 1, '$keterangan_log')");
        }

        echo "<script>alert('Data Servis Berhasil Diselesaikan! Semua sparepart berhasil tersimpan.'); window.location='../views/service/service_tampil.php';</script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>