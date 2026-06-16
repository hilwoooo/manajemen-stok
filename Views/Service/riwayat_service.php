<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}
require_once('../../Config/koneksi.php'); 
include('../../layouts/header.php'); 
include('../../layouts/sidebar.php'); 
include('../../layouts/topbar.php'); 
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen & Riwayat Servis Laptop</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-warning text-dark">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-tools"></i> Antrean Servis Aktif (Sedang Berjalan)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered nowrap dataTableServis" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th class="text-center">Nama Pelanggan</th>
                            <th class="text-center">No. HP</th>
                            <th class="text-center">Tipe Laptop</th>
                            <th class="text-center">Keluhan / Kerusakan</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Query mengambil data servis yang statusnya masih Antrean
                        $query_antrean = "SELECT * FROM tabel_servis WHERE status_servis = 'Antrean' ORDER BY id_servis ASC";
                        $tampil_antrean = mysqli_query($koneksi, $query_antrean);
                        $no_antrean = 1; 
                        
                        while ($row_antrean = mysqli_fetch_array($tampil_antrean)) :
                        ?>
                        <tr>
                            <td class="text-center"><?= $no_antrean; ?></td>
                            <td style="text-transform:uppercase"><strong><?= $row_antrean['nama_pelanggan']; ?></strong></td>
                            <td class="text-center"><?= $row_antrean['no_hp']; ?></td>
                            <td style="text-transform:uppercase"><?= $row_antrean['tipe_laptop']; ?></td>
                            <td><?= $row_antrean['keluhan']; ?></td>
                            <td class="text-center">
                                <span class="badge badge-warning"><i class="fas fa-spinner fa-spin"></i> Dalam Antrean</span>
                            </td>
                        </tr>
                        <?php $no_antrean++; endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-info text-white">
            <h6 class="m-0 font-weight-bold"><i class="fas fa-check-circle"></i> Riwayat Servis Selesai & Rincian Biaya</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered nowrap dataTableServis" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th class="text-center">Nama Pelanggan</th>
                            <th class="text-center">Tipe Laptop</th>
                            <th class="text-center">Sparepart Dipasang</th>
                            <th class="text-center">Biaya Jasa</th>
                            <th class="text-center">Total Bayar</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // query mengambil data servis selesai yg direlasikan ke tabel_barang dan master_katalog untuk ambil nama sparepartnya
                        $query_selesai = "SELECT s.*, k.nama_produk, k.merek 
                                          FROM tabel_servis s
                                          LEFT JOIN tabel_barang b ON s.id_barang = b.id_barang
                                          LEFT JOIN master_katalog k ON b.id_katalog = k.id_katalog
                                          WHERE s.status_servis = 'Selesai' 
                                          ORDER BY s.id_servis DESC";
                        
                        $tampil_selesai = mysqli_query($koneksi, $query_selesai);
                        $no_selesai = 1; 
                        
                        while ($row_selesai = mysqli_fetch_array($tampil_selesai)) :
                        ?>
                        <tr>
                            <td class="text-center"><?= $no_selesai; ?></td>
                            <td style="text-transform:uppercase"><strong><?= $row_selesai['nama_pelanggan']; ?></strong></td>
                            <td style="text-transform:uppercase"><?= $row_selesai['tipe_laptop']; ?></td>
                            <td style="text-transform:uppercase">
                                <?php 
                                if (!empty($row_selesai['id_barang'])) {
                                    echo "<span class='text-primary'>" . $row_selesai['merek'] . " - " . $row_selesai['nama_produk'] . "</span>";
                                } else {
                                    echo "<span class='text-muted'>Tidak Ada Ganti Sparepart</span>";
                                }
                                ?>
                            </td>
                            <td><?= 'Rp ' . number_format($row_selesai['biaya_jasa'], 0, ',', '.'); ?></td>
                            <td class="font-weight-bold text-success">
                                <?= 'Rp ' . number_format($row_selesai['total_biaya'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-success"><i class="fas fa-check"></i> Selesai</span>
                            </td>
                        </tr>
                        <?php $no_selesai++; endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../../layouts/footer.php'); ?>

<script>
$(document).ready(function() {
    $('.dataTableServis').DataTable({
        "order": [] 
    });
});
</script>