<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit;
}
require_once '../../Config/koneksi.php';
require_once '../../layouts/header.php';
include('../../layouts/sidebar.php');
include('../../layouts/topbar.php');
require_once '../../proses/tampil_produk.php';
require_once '../../proses/filter_produk.php';
require_once '../../proses/cari_barang.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$kategori_db = getKategoriData();

// Memvalidasi id kategori / pencarian
if (isset($_GET['id_kategori'])) {
    $barang_masuk = filterBarang($_GET['id_kategori']);
} elseif (isset($_GET['search']) && !empty($_GET['input'])) {
    $barang_masuk = cariItemsBarang($_GET['input']);
} else {
    $barang_masuk = tampilBarangMasuk();
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Barang Masuk</h1>

    <div class="card shadow mb-4">
        <div class="card-body">

            <button class="h3 btn btn-success mb-3" data-toggle="modal" data-target="#addBarangModal">
                Input Barang Masuk
            </button>

            <div class="row align-items-center my-3">
                <div class="col-md-8 mb-2 mb-md-0">
                    <a href="?" class="btn btn-outline-primary mx-1">All</a>
                    <?php foreach ($kategori_db as $kt) : ?>
                        <a href="?id_kategori=<?= $kt['id_kategori'] ?>" class="btn btn-outline-primary mx-1">
                            <?= $kt['nama_kategori'] ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="col-md-4">
                    <form class="d-flex float-md-right" role="search">
                        <input class="form-control me-2 input-search" type="search" placeholder="Search" name="input" aria-label="Search" value="<?= isset($_GET['input']) ? htmlspecialchars($_GET['input']) : ''; ?>" />
                        <button class="btn btn-outline-success mx-2" type="submit" name="search">Search</button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Barang</th>
                            <th class="text-center">Merek</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Harga Satuan</th>
                            <th class="text-center">Jumlah Masuk</th>
                            <th class="text-center">Tanggal Terdaftar</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        $modals = []; 
                        while ($data = mysqli_fetch_array($barang_masuk)) :
                            $modals[] = $data; 
                        ?>
                            <tr>
                                <td class="text-center"><?= $i++; ?></td>
                                <td style="text-transform:uppercase"><strong><?= $data['nama_produk']; ?></strong></td>
                                <td style="text-transform:uppercase"><?= $data['merek']; ?></td>
                                <td class="text-center">
                                    <span class="badge <?= ($data['nama_kategori'] == 'Unit Laptop') ? 'badge-success' : (($data['nama_kategori'] == 'Aksesoris') ? 'badge-secondary' : 'badge-info'); ?>">
                                        <?= $data['nama_kategori']; ?>
                                    </span>
                                </td>
                                <td><?= 'Rp ' . number_format($data['harga'], 0, ',', '.'); ?></td>
                                <td class="text-center"><strong><?= $data['stok_masuk']; ?></strong></td>
                                <td class="text-center"><?= date('d-m-Y H:i', strtotime($data['tanggal_masuk'])); ?> WIB</td>
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalEdit<?= $data['id_barang']; ?>" title="Edit">
                                        <i class="fas fa-solid fa-fw fa-pen"></i>
                                    </button>

                                    <form action="../../proses/barang_proses.php" method="post" style="display:inline;">
                                        <input type="hidden" name="id_barang" value="<?= $data['id_barang']; ?>">
                                        <button type="submit" name="hapus_barang" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus log barang masuk ini? Stok di master katalog akan otomatis berkurang.');">
                                            <i class="fas fa-solid fa-fw fa-trash" title="Delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addBarangModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> 
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Input Stok Barang Masuk</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="../../proses/barang_proses.php" method="post">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="text-gray-800 font-weight-bold">Pilih Produk (Dari Master Katalog)</label>
                        <select class="form-control" name="id_katalog" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php
                            $katalog_tambah = mysqli_query($koneksi, "SELECT * FROM master_katalog ORDER BY nama_produk ASC");
                            while ($kt = mysqli_fetch_array($katalog_tambah)) {
                                echo "<option value='" . $kt['id_katalog'] . "'>" . strtoupper($kt['merek'] . " - " . $kt['nama_produk']) . " (" . $kt['jenis'] . ")</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-8 form-group mb-3">
                            <label class="text-gray-800 font-weight-bold">Harga Gudang (Rupiah)</label>
                            <input type="number" class="form-control" name="harga" placeholder="Masukkan Harga Modal Gudang" required>
                        </div>
                        
                        <div class="col-md-4 form-group mb-3">
                            <label class="text-gray-800 font-weight-bold">Jumlah Stok</label>
                            <input type="number" class="form-control" name="stok_masuk" min="1" placeholder="Contoh: 10" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="tambah_barang" class="btn btn-success">Simpan Catatan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($modals as $data) : ?>
    <div class="modal fade" id="modalEdit<?= $data['id_barang']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Catatan Barang Masuk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="../../proses/barang_proses.php" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="id_barang" value="<?= $data['id_barang']; ?>">

                        <div class="form-group">
                            <label>Pilih Produk (Katalog)</label>
                            <select class="form-control" name="id_katalog" required>
                                <?php
                                $katalog_edit = mysqli_query($koneksi, "SELECT * FROM master_katalog ORDER BY nama_produk ASC");
                                while ($ke = mysqli_fetch_array($katalog_edit)) {
                                    $selected = ($ke['id_katalog'] == $data['id_katalog']) ? 'selected' : '';
                                    echo "<option value='" . $ke['id_katalog'] . "' $selected>" . strtoupper($ke['merek'] . " - " . $ke['nama_produk']) . " (" . $ke['jenis'] . ")</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Harga Satuan (Rupiah)</label>
                            <input type="number" class="form-control" name="harga" value="<?= $data['harga']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Barang Masuk</label>
                            <input type="number" class="form-control" name="stok_masuk" value="<?= $data['stok_masuk']; ?>" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" name="edit_barang" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php include('../../layouts/footer.php'); ?>
