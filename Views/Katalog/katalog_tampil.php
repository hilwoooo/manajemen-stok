<?php 
include('../../Config/koneksi.php'); 
include('../../layouts/header.php'); 
include('../../layouts/sidebar.php'); 
include('../../layouts/topbar.php'); 
require_once '../../proses/tampil_produk.php';

$produk_katalog = tampilKatalog();
?>

<div class="container-fluid">
    <h1 class="h1 mb-4 text-gray-800 fw-semibold">Master Katalog Produk</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <button class="h3 btn btn-success mb-3" data-toggle="modal" data-target="#addKatalogModal">Tambah Produk Baru</button>
            
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-center">No</th>
                            <th class="text-center">Merek</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Jenis / Kategori</th>
                            <th class="text-center">Stok Global</th> 
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                       
                        $i = 1; 
                        while ($data = mysqli_fetch_array($produk_katalog)) :
                        ?>
                        <tr>
                            <td class="text-center"><?= $i; ?></td>
                            <td style="text-transform:uppercase"><strong><?= $data['merek']; ?></strong></td>
                            <td style="text-transform:uppercase"><?= $data['nama_produk']; ?></td>
                            <td class="text-center">
                                <span class="badge <?= ($data['nama_kategori'] == 'Unit Laptop') ? 'badge-success' : 'badge-info'; ?>">
                                    <?= $data['nama_kategori']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <strong class="<?= ($data['stok'] > 0) ? 'text-primary' : 'text-danger'; ?>">
                                    <?= $data['stok']; ?>
                                </strong>
                                <td class="text-center"><?= date('d-m-Y H:i', strtotime($data['tanggal'])); ?> WIB</td>
                            </td>
                            <td class="text-center">
                                <form action="proses/katalog_proses.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id_katalog" value="<?= $data['id_katalog']; ?>">
                                    <button type="submit" name="hapus_katalog" class="btn btn-danger btn-sm" onclick="return confirm('Menghapus katalog akan menghapus stok barang terkait di gudang. Yakin?');">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php $i++; endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addKatalogModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Daftarkan Produk Baru ke Katalog</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="proses/katalog_proses.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Merek / Brand</label>
                        <input type="text" class="form-control" name="merek" placeholder="Contoh: ASUS, Kingston, Samsung" required style="text-transform:uppercase">
                    </div>
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" class="form-control" name="nama_produk" placeholder="Contoh: RAM DDR4 8GB, ROG Strix G15" required style="text-transform:uppercase">
                    </div>
                    <div class="form-group">
                        <label>Jenis / Kategori</label>
                        <select class="form-control" name="jenis" required>
                            <option value="Sparepart">Sparepart (Komponen Perbaikan)</option>
                            <option value="Unit Laptop">Unit Laptop (Barang Jadi)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_katalog" class="btn btn-success">Simpan ke Katalog</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('../../layouts/footer.php'); ?>