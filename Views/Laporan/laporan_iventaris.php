<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}
include('../../Config/koneksi.php'); 
require_once '../../proses/dashboard_data.php';
include('../../layouts/header.php'); 
include('../../layouts/sidebar.php'); 
include('../../layouts/topbar.php'); 

require_once '../../proses/tampil_produk.php';

$tampil = getPenjualan();
?>

<div class="container-fluid">
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan</h1>
    </div>

    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4 ">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2 ">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ragam Produk / Sparepart</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_barang; ?> Jenis</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Antrean Servis Baru</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_antrean; ?> Laptop</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Servis Selesai</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_selesai; ?> Unit</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

       <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Hasil Seluruhnya</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= isset($total_hasil_seluruhnya) ? number_format($total_hasil_seluruhnya, 0, ',', '.') : 0; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Penjualan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_penjualan ?> Unit</div>
                        </div>
                        <div class="col-auto">
                            <img src="../../img/transaksi.png" alt="Icon Transaksi" style="width: 45px; height: 45px; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Servis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jumlah_service; ?> Unit</div>
                        </div>
                        <div class="col-auto">
                            <img src="../../img/service1.png" alt="Icon Service" style="width: 45px; height: 45px; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Pendapatan Penjualan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800 penjualan_card">Rp <?= number_format($total_pendapatan_penjualan, 0, ',', '.'); ?></div>
                        </div>
                        <div class="col-auto">
                            <img src="../../img/penjualan.png" alt="Icon Penjualan" style="width: 45px; height: 45px; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pendapatan Servis</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="container-fluid">
    <div class="card shadow mb-4">
         <h2 class="h3 p-3 text-gray-800 fw-bold">Laporan Penjualan</h2>
        <div class="card-body">
            <a href="../../proses/export_penjualan_pdf.php" class="btn1 my-2 bg-success text-white p-2 rounded d-inline-flex align-items-center text-decoration-none">
                <img src="../../img/unduhan.png" class="icon-unduh mx-1" style="width: 18px; height: 18px; object-fit: contain; filter: brightness(0) invert(1);">Export PDF
            </a>

            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered nowrap " style="width:100%">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Pembeli</th>
                            <th class="text-center">No. HP</th>
                            <th class="text-center">Sparepart Keluar</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Harga Jual Transaksi</th>
                            <th class="text-center">Tanggal Transaksi</th>
                        </tr>    
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($data = mysqli_fetch_array($tampil)) :
                        ?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td style="text-transform:uppercase"><strong><?= $data['nama_pembeli']; ?></strong></td>
                                <td><?= $data['no_hp']; ?></td>
                                <td style="text-transform:uppercase"><?= $data['merek'] . ' - ' . $data['nama_produk']; ?></td>
                                <td class="text-center">
                                    <span class="badge badge-info"><?= $data['nama_kategori']; ?></span>
                                </td>
                                <td><strong><?= 'Rp ' . number_format($data['total_bayar'], 0, ',', '.'); ?></strong></td>
                                <td class="text-center"><?= date('d-m-Y H:i', strtotime($data['tanggal_keluar'])); ?> WIB</td>
                            </tr>
                        <?php $i++;
                        endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <h1 class="h3 p-3 text-gray-800">Laporan Servis</h1>
        <div class="card-body">
            <a href="../../proses/export_servis_pdf.php" class="btn2 my-2 bg-success text-white p-2 rounded d-inline-flex align-items-center text-decoration-none">
                <img src="../../img/unduhan.png" class="icon-unduh mx-1" style="width: 18px; height: 18px; object-fit: contain; filter: brightness(0) invert(1);">Export PDF
            </a>
            <div class="table-responsive">
                <table id="dataTableServis" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-center">No</th>
                            <th class="text-center">Pelanggan</th>
                            <th class="text-center">No HP</th>
                            <th class="text-center">Tipe Laptop</th>
                            <th class="text-center">Keluhan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM tabel_servis ORDER BY id_servis DESC";
                        $tampil_servis = mysqli_query($koneksi, $query);
                        $i = 1;
                        while ($data = mysqli_fetch_array($tampil_servis)) :
                        ?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td style="text-transform:uppercase"><strong><?= $data['nama_pelanggan']; ?></strong></td>
                                <td><?= $data['no_hp']; ?></td>
                                <td style="text-transform:uppercase"><?= $data['tipe_laptop']; ?></td>
                                <td><?= $data['keluhan']; ?></td>
                                <td class="text-center">
                                    <span class="badge <?= ($data['status_servis'] == 'Antrean') ? 'badge-warning' : 'badge-success'; ?>">
                                        <?= $data['status_servis']; ?>
                                    </span>
                                </td>
                                <td><?= 'Rp ' . number_format($data['total_biaya'], 0, ',', '.'); ?></td>
                            </tr>

                            <div class="modal fade" id="modalSelesai<?= $data['id_servis']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Penyelesaian Servis: <?= strtoupper($data['nama_pelanggan']); ?></h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <form action="/manajemen-stok/proses/servis_proses.php" method="post">
                                            <div class="modal-body">
                                                <input type="hidden" name="id_servis" value="<?= $data['id_servis']; ?>">

                                                <div class="form-group">
                                                    <label>Ganti Sparepart? (Ambil dari Stok Gudang)</label>
                                                    <select class="form-control" name="id_barang">
                                                        <option value="">-- Tidak Ada Pergantian / Hanya Jasa --</option>
                                                        <?php
                                                        global $koneksi;
                                                        $g_barang = mysqli_query($koneksi, "SELECT b.id_barang, b.id_katalog, b.harga, k.stok, k.nama_produk, k.merek 
                                                                                                FROM tabel_barang b
                                                                                                INNER JOIN master_katalog AS k ON b.id_katalog = k.id_katalog
                                                                                                INNER JOIN tabel_kategori AS ka ON k.id_kategori = ka.id_kategori
                                                                                                WHERE ka.nama_kategori='Sparepart' AND k.stok > 0");
                                                        while ($b = mysqli_fetch_array($g_barang)) {
                                                            echo "<option value='" . $b['id_barang'] . "'>" . strtoupper($b['merek'] . " - " . $b['nama_produk']) . " (Stok Gudang: " . $b['stok'] . ") - Rp " . number_format($b['harga'], 0, ',', '.') . "</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                    <small class="text-muted">*Jika dipilih, stok sparepart di master katalog akan otomatis dikurangi 1.</small>
                                                </div>

                                                <div class="form-group">
                                                    <label>Biaya Jasa Teknisi (Rupiah)</label>
                                                    <input type="number" class="form-control" name="biaya_jasa" placeholder="Contoh: 100000" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                                <button type="submit" name="servis_selesai" class="btn btn-success">Simpan & Selesai</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php $i++;
                        endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../../layouts/footer.php'); ?>