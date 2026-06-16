<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}
require_once '../../Config/koneksi.php';
include('../../layouts/header.php');
include('../../layouts/sidebar.php');
include('../../layouts/topbar.php');

require_once '../../proses/tampil_produk.php';
require_once '../../proses/filter_produk.php';
require_once '../../proses/cari_barang.php';
$kategori_db = getKategoriData();

if (isset($_GET['id_kategori'])) {
    $tampil = filterPenjualan($_GET['id_kategori']);
} elseif (isset($_GET['search']) && !empty($_GET['input'])) {
    $tampil = cariItemsPenjualan($_GET['input']);
} else {
    $tampil = getPenjualan();
}
?>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"> Penjualan (Barang Keluar)</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            
            <button class="h3 btn btn-success mb-3" data-toggle="modal" data-target="#addPenjualanModal">
                Input Penjualan
            </button>

            <div class="row align-items-center my-3">
                <div class="col-md-8">
                    <a href="?" class="btn btn-outline-primary mx-1">All</a>
                    <?php foreach ($kategori_db as $kt) : ?>
                        <a href="?id_kategori=<?= $kt['id_kategori']; ?>" class="btn btn-outline-primary mx-1">
                            <?= $kt['nama_kategori']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="col-md-4">
                    <form class="form-inline float-right">
                        <input type="text" name="input" class="form-control" placeholder="Cari produk / merek" value="<?= isset($_GET['input']) ? htmlspecialchars($_GET['input']) : ''; ?>">
                        <button type="submit" name="search" class="btn btn-success mx-2">Search</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Pembeli</th>
                            <th class="text-center">No. HP</th>
                            <th class="text-center">Barang Keluar</th>
                            <th class="text-center">Kategori</th>
                            <th class="text-center">Qty</th> <!-- Penambahan Kolom Qty Baru -->
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
                                <td class="text-center"><?= $i++; ?></td>
                                <td style="text-transform:uppercase">
                                    <strong><?= $data['nama_pembeli']; ?></strong>
                                </td>
                                <td><?= $data['no_hp']; ?></td>
                                <td style="text-transform:uppercase">
                                    <?= $data['merek'] . ' - ' . $data['nama_produk']; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge <?= ($data['nama_kategori'] == 'Unit Laptop') ? 'badge-success' : (($data['nama_kategori'] == 'Aksesoris') ? 'badge-secondary' : 'badge-info'); ?>">
                                        <?= $data['nama_kategori']; ?>
                                    </span>
                                </td>
                                <!-- Menampilkan Data Qty -->
                                <td class="text-center">
                                    <strong><?= $data['qty']; ?></strong> Pcs
                                </td>
                                <td>
                                    <strong>Rp <?= number_format($data['total_bayar'], 0, ',', '.'); ?></strong>
                                </td>
                                <td class="text-center">
                                    <?= date('d-m-Y H:i', strtotime($data['tanggal_keluar'])); ?> WIB
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<div class="modal fade" id="addPenjualanModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> 
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Form Transaksi Kasir</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="../../proses/penjualan_proses.php" method="post">
                <div class="modal-body">

                    <div class="form-group">
                        <label>Nama Pembeli</label>
                        <input type="text" class="form-control" name="nama_pembeli" placeholder="Contoh: CASH / BAPAK BUDI" required style="text-transform:uppercase">
                    </div>

                    <div class="form-group">
                        <label>No. HP (Opsional)</label>
                        <input type="text" class="form-control" name="no_hp" placeholder="Contoh: 081234xxx" value="-">
                    </div>

                    <hr>
                    <h5 class="mb-3">Daftar Belanja Sparepart</h5>
                    
                    <div id="wrapper-barang">
                        
                        <!-- Baris Input Utama (Termasuk Dropdown, Qty, dan Tombol Operasional) -->
                        <div class="row form-group barang-item mb-3">
                            <div class="col-md-7 mb-2 mb-md-0">
                                <select class="form-control" name="id_barang[]" required>
                                    <option value="">-- Pilih Sparepart (FIFO System) --</option>
                                    <?php
                                    $q_barang = mysqli_query($koneksi, "SELECT b.id_barang, b.harga, b.tanggal_masuk, k.nama_produk, k.merek 
                                                                        FROM tabel_barang b
                                                                        INNER JOIN master_katalog k ON b.id_katalog = k.id_katalog
                                                                        WHERE b.id_barang IN (
                                                                            SELECT MIN(id_barang) 
                                                                            FROM tabel_barang 
                                                                            WHERE stok_masuk > 0 
                                                                            GROUP BY id_katalog
                                                                        )
                                                                        ORDER BY k.nama_produk ASC");

                                    while ($b = mysqli_fetch_array($q_barang)) :
                                        $nama_barang = strtoupper($b['merek'] . " - " . $b['nama_produk']);
                                        $harga = number_format($b['harga'], 0, ',', '.');
                                        $tgl_masuk = date('d-m-Y', strtotime($b['tanggal_masuk']));
                                    ?>
                                        <option value="<?= $b['id_barang']; ?>">
                                            <?= "{$nama_barang} | Rp {$harga} (Batch: {$tgl_masuk})"; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <!-- Input Kolom Qty (Jumlah) -->
                            <div class="col-md-2 col-6">
                                <input type="number" class="form-control" name="qty[]" value="1" min="1" placeholder="Qty" required>
                            </div>
                            
                            <div class="col-md-3 col-6">
                                <div class="btn-group d-flex" role="group">
                                    <button type="button" class="btn btn-success btn-tambah w-100" title="Tambah Baris Barang">+</button>
                                    <button type="button" class="btn btn-danger btn-hapus w-100" title="Hapus Baris Ini" disabled>-</button>
                                </div>
                            </div>
                        </div>

                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="tambah_penjualan" class="btn btn-success">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // 1. Logika Klik Tombol Plus (+)
    $(document).on('click', '.btn-tambah', function() {
        var barisBaru = $('.barang-item:first').clone();
        
        // Bersihkan isi pilihan dropdown & reset kolom Qty kembali ke angka 1
        barisBaru.find('select').val('');
        barisBaru.find('input[type="number"]').val('1');
        
        // Buka proteksi disabled tombol minus di baris baru
        barisBaru.find('.btn-hapus').removeAttr('disabled');
        
        $('#wrapper-barang').append(barisBaru);
        validasiTombolHapus();
    });

    // 2. Logika Klik Tombol Minus (-)
    $(document).on('click', '.btn-hapus', function() {
        $(this).closest('.barang-item').remove();
        validasiTombolHapus();
    });

    // 3. Fungsi Pengontrol Kunci Tombol Minus
    function validasiTombolHapus() {
        var totalBaris = $('.barang-item').length;
        if (totalBaris === 1) {
            $('.barang-item').find('.btn-hapus').attr('disabled', true);
        } else {
            $('.barang-item').find('.btn-hapus').removeAttr('disabled');
        }
    }
});
</script>

<?php include('../../layouts/footer.php'); ?>