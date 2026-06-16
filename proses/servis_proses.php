<?php
require_once __DIR__ . '/../Config/koneksi.php';

global $koneksi;

//tambah orang servis
if (isset($_POST['tambah_servis'])) {
    $nama_pelanggan = mysqli_real_escape_string($koneksi, $_POST['nama_pelanggan']);
    $no_hp          = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $tipe_laptop    = mysqli_real_escape_string($koneksi, $_POST['tipe_laptop']);
    $keluhan        = mysqli_real_escape_string($koneksi, $_POST['keluhan']);

    $query_tambah = "INSERT INTO tabel_servis (nama_pelanggan, no_hp, tipe_laptop, keluhan, status_servis, total_biaya) 
                     VALUES ('$nama_pelanggan', '$no_hp', '$tipe_laptop', '$keluhan', 'Antrean', 0)";

    if (mysqli_query($koneksi, $query_tambah)) {
        echo "<script>alert('Pendaftaran Antrean Servis Berhasil!'); window.location='../views/service/service_tampil.php';</script>";
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

//proses servis selesai
if (isset($_POST['servis_selesai'])) {
    $id_servis  = mysqli_real_escape_string($koneksi, $_POST['id_servis']);
    $biaya_jasa = (int)$_POST['biaya_jasa'];

    // Ambil array id_barang berdasarkan id_servis aktif
    $id_barang_array = isset($_POST['id_barang'][$id_servis]) ? $_POST['id_barang'][$id_servis] : [];

    // Ambil info awal data antrean pelanggan
    $cari_antrean = mysqli_query($koneksi, "SELECT * FROM tabel_servis WHERE id_servis = '$id_servis'");
    $data_awal    = mysqli_fetch_array($cari_antrean);

    $nama_pelanggan = mysqli_real_escape_string($koneksi, $data_awal['nama_pelanggan']);
    $tipe_laptop    = mysqli_real_escape_string($koneksi, $data_awal['tipe_laptop']);

    // TAHAP 1: Gabungkan qty jika ada id_barang yang sama dipilih lebih dari sekali
    $barang_tergabung = [];
    foreach ($id_barang_array as $id_barang_mentah) {
        if (empty($id_barang_mentah)) continue;

        $id_barang = mysqli_real_escape_string($koneksi, $id_barang_mentah);

        if (isset($barang_tergabung[$id_barang])) {
            $barang_tergabung[$id_barang] += 1;
        } else {
            $barang_tergabung[$id_barang] = 1;
        }
    }

    // jika hanya jas (Tanpa ganti part)
    if (empty($barang_tergabung)) {
        mysqli_query($koneksi, "UPDATE tabel_servis SET 
                                status_servis = 'Selesai', 
                                id_barang = NULL, 
                                biaya_jasa = '$biaya_jasa', 
                                total_biaya = '$biaya_jasa' 
                                WHERE id_servis = '$id_servis'");

        echo "<script>alert('Servis Berhasil Diselesaikan (Hanya Jasa)!'); window.location='../views/service/service_tampil.php';</script>";
        exit();
    }

    // Validasi stok terlebih dahulu sebelum eksekusi
    $data_siap_simpan = [];
    $total_harga_sparepart = 0;

    foreach ($barang_tergabung as $id_barang => $total_qty) {
        $cari_barang = mysqli_query($koneksi, "SELECT id_katalog, harga, stok_masuk FROM tabel_barang WHERE id_barang = '$id_barang' LIMIT 1");

        if (mysqli_num_rows($cari_barang) > 0) {
            $data_barang = mysqli_fetch_assoc($cari_barang);

            if ($data_barang['stok_masuk'] < $total_qty) {
                echo "<script>alert('Stok sparepart " . $id_barang . " tidak mencukupi!'); window.location='../views/service/service_tampil.php';</script>";
                exit();
            }

            $subtotal = $data_barang['harga'] * $total_qty;
            $total_harga_sparepart += $subtotal;

            $data_siap_simpan[] = [
                'id_barang'    => $id_barang,
                'id_katalog'   => $data_barang['id_katalog'],
                'harga_satuan' => $data_barang['harga'],
                'qty'          => $total_qty,
                'subtotal'     => $subtotal
            ];
        } else {
            echo "<script>alert('Salah satu sparepart tidak ditemukan!'); window.location='../views/service/service_tampil.php';</script>";
            exit();
        }
    }

    // HITUNG GRAND TOTAL NOTA
    $grand_total_servis = $biaya_jasa + $total_harga_sparepart;
    $id_barang_utama    = $data_siap_simpan[0]['id_barang'];

    // Update baris utama nota transaksi di tabel_servis
    $query_update_servis = "UPDATE tabel_servis SET 
                            status_servis = 'Selesai', 
                            id_barang = '$id_barang_utama', 
                            biaya_jasa = '$biaya_jasa', 
                            total_biaya = '$grand_total_servis' 
                            WHERE id_servis = '$id_servis'";

    if (mysqli_query($koneksi, $query_update_servis)) {
        $sukses = true;

        // TAHAP 3: Eksekusi simpan berulang ke tabel_detail_servis
        foreach ($data_siap_simpan as $item) {
            $id_barang    = $item['id_barang'];
            $id_katalog   = $item['id_katalog'];
            $harga_satuan = $item['harga_satuan'];
            $qty          = $item['qty'];

            for ($k = 0; $k < $qty; $k++) {

                // 1. Insert ke tabel_detail_servis (tanpa kolom qty)
                $query_insert = "INSERT INTO tabel_detail_servis (id_servis, id_barang, harga_satuan) 
                                 VALUES ('$id_servis', '$id_barang', '$harga_satuan')";

                if (!mysqli_query($koneksi, $query_insert)) {
                    $sukses = false;
                    echo "Error: " . mysqli_error($koneksi);
                    break 2;
                }
            }

            // 2. Potong stok masuk di tabel_barang
            mysqli_query($koneksi, "UPDATE tabel_barang SET stok_masuk = stok_masuk - $qty WHERE id_barang = '$id_barang' LIMIT 1");

            // 3. Potong stok master di master_katalog
            mysqli_query($koneksi, "UPDATE master_katalog SET stok = stok - $qty WHERE id_katalog = '$id_katalog'");

            // 4. Catat log arus keluar barang di gudang
            $keterangan_log = "DIGUNAKAN UNTUK SERVIS LAPTOP [" . strtoupper($tipe_laptop) . "] AN. " . strtoupper($nama_pelanggan) . " (QTY: " . $qty . ")";
            mysqli_query($koneksi, "INSERT INTO tabel_riwayat_stok (id_katalog, jenis_arus, qty, keterangan) 
                                    VALUES ('$id_katalog', 'KELUAR', '$qty', '$keterangan_log')");
        }
    } else {
        $sukses = false;
        echo "Error: " . mysqli_error($koneksi);
    }

    if ($sukses) {
        echo "<script>alert('Data Servis Berhasil Diselesaikan! Seluruh sparepart berhasil disimpan.'); window.location='../views/service/service_tampil.php';</script>";
        exit();
    }
}
?>