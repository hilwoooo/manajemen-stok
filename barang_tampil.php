<?php 
include('koneksi.php'); 
include('layouts/header.php'); 
include('layouts/sidebar.php'); 
include('layouts/topbar.php'); 
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Riwayat & Log Barang Masuk</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <button class="h3 btn btn-primary mb-3" data-toggle="modal" data-target="#addBarangModal">Input Barang Masuk</button>
            
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Barang / Unit</th>
                            <th class="text-center">Merek</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Harga Satuan</th>
                            <th class="text-center">Tanggal Terdaftar</th>
                            <th class="text-center">Jumlah Masuk</th> <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Query menarik data riwayat transaksi masuk
                        $query = "SELECT b.*, k.nama_produk, k.merek, k.jenis 
                                  FROM tabel_barang b
                                  INNER JOIN master_katalog k ON b.id_katalog = k.id_katalog
                                  ORDER BY b.id_barang DESC";
                        $tampil = mysqli_query($koneksi, $query);
                        $i = 1; 
                        while ($data = mysqli_fetch_array($tampil)) :
                        ?>
                        <tr>
                            <td class="text-center"><?= $i; ?></td>
                            <td style="text-transform:uppercase"><strong><?= $data['nama_produk']; ?></strong></td>
                            <td style="text-transform:uppercase"><?= $data['merek']; ?></td>
                            <td class="text-center">
                                <span class="badge <?= ($data['jenis'] == 'Unit Laptop') ? 'badge-success' : 'badge-info'; ?>">
                                    <?= $data['jenis']; ?>
                                </span>
                            </td>
                            <td><?= 'Rp ' . number_format($data['harga'], 0, ',', '.'); ?></td>
                            <td class="text-center">
                                <strong><?= $data['stok_masuk']; ?></strong>
                            </td>
                            <td class="text-center"><?= date('d-m-Y H:i', strtotime($data['tanggal_masuk'])); ?> WIB</td>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalEdit<?= $data['id_barang']; ?>" title="Edit">
                                    <i class="fas fa-solid fa-fw fa-pen"></i>
                                </button>
                                
                                <form action="proses/barang_proses.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id_barang" value="<?= $data['id_barang']; ?>">
                                    <button type="submit" name="hapus_barang" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus log barang masuk ini? Stok di master katalog akan otomatis berkurang.');">
                                        <i class="fas fa-solid fa-fw fa-trash" title="Delete"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalEdit<?= $data['id_barang']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Catatan Barang Masuk</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="proses/barang_proses.php" method="post">
                                        <div class="modal-body">
                                            <input type="hidden" name="id_barang" value="<?= $data['id_barang']; ?>">
                                            
                                            <div class="form-group">
                                                <label>Pilih Produk (Katalog)</label>
                                                <select class="form-control" name="id_katalog" required>
                                                    <?php 
                                                    $katalog_edit = mysqli_query($koneksi, "SELECT * FROM master_katalog ORDER BY nama_produk ASC");
                                                    while($ke = mysqli_fetch_array($katalog_edit)){
                                                        $selected = ($ke['id_katalog'] == $data['id_katalog']) ? 'selected' : '';
                                                        echo "<option value='".$ke['id_katalog']."' $selected>".strtoupper($ke['merek']." - ".$ke['nama_produk'])." (".$ke['jenis'].")</option>";
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
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="edit_barang" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php $i++; endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addBarangModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Stok Barang Masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="proses/barang_proses.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Pilih Produk (Dari Master Katalog)</label>
                        <select class="form-control" name="id_katalog" required>
                            <option value="">-- Pilih Produk dari Katalog --</option>
                            <?php 
                            $katalog_tambah = mysqli_query($koneksi, "SELECT * FROM master_katalog ORDER BY nama_produk ASC");
                            while($kt = mysqli_fetch_array($katalog_tambah)){
                                echo "<option value='".$kt['id_katalog']."'>".strtoupper($kt['merek']." - ".$kt['nama_produk'])." (".$kt['jenis'].")</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga Gudang (Rupiah)</label>
                        <input type="number" class="form-control" name="harga" placeholder="Masukkan Harga Modal" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Stok Masuk</label>
                        <input type="number" class="form-control" name="stok_masuk" placeholder="Contoh: 10" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="tambah_barang" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('layouts/footer.php'); ?>