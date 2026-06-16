<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}
include('../../Config/koneksi.php'); 
include('../../layouts/header.php'); 
include('../../layouts/sidebar.php'); 
include('../../layouts/topbar.php'); 
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Riwayat Stok</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-dark">
            <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-exchange-alt"></i> Semua Aktivitas Keluar & Masuk Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-center" width="5%">No</th>
                            <th class="text-center">Tanggal & Waktu</th>
                            <th class="text-center">Merek & Nama Barang</th>
                            <th class="text-center">Jenis Arus</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Keterangan Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Query menyatukan linimasa arus barang berdasarkan tanggal terbaru
                        $query = "SELECT r.*, k.nama_produk, k.merek 
                                  FROM tabel_riwayat_stok r
                                  INNER JOIN master_katalog k ON r.id_katalog = k.id_katalog
                                  ORDER BY r.id_riwayat DESC";
                        
                        $tampil = mysqli_query($koneksi, $query);
                        $i = 1; 
                        while ($data = mysqli_fetch_array($tampil)) :
                            
                            if($data['jenis_arus'] == 'MASUK') {
                                $badge = '<span class="badge badge-success"><i class="fas fa-arrow-down"></i> BARANG MASUK</span>';
                                $qty_color = 'text-success font-weight-bold';
                                $sign = '+';
                            } else {
                                $badge = '<span class="badge badge-danger"><i class="fas fa-arrow-up"></i> BARANG KELUAR</span>';
                                $qty_color = 'text-danger font-weight-bold';
                                $sign = '-';
                            }
                        ?>
                        <tr>
                            <td class="text-center"><?= $i; ?></td>
                            <td class="text-center"><?= date('d-m-Y H:i', strtotime($data['tanggal'])); ?> WIB</td>
                            <td style="text-transform:uppercase"><?= $data['merek'] . ' - ' . $data['nama_produk']; ?></td>
                            <td class="text-center"><?= $badge; ?></td>
                            <td class="text-center <?= $qty_color; ?>"><?= $sign . ' ' . $data['qty']; ?> Pcs</td>
                            <td><?= $data['keterangan']; ?></td>
                        </tr>
                        <?php $i++; endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('../../layouts/footer.php'); ?>