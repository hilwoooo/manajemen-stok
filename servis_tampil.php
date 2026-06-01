<?php 
include('koneksi.php'); 
include('layouts/header.php'); 
include('layouts/sidebar.php'); 
include('layouts/topbar.php'); 
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Daftar Antrean Servis Laptop</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <button class="h3 btn btn-primary mb-3" data-toggle="modal" data-target="#addServisModal">Daftar Servis Baru</button>
            
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Pelanggan</th>
                            <th class="text-center">No HP</th>
                            <th class="text-center">Tipe Laptop</th>
                            <th class="text-center">Keluhan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Total Biaya</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query = "SELECT * FROM tabel_servis ORDER BY id_servis DESC";
                        $tampil = mysqli_query($koneksi, $query);
                        $i = 1; 
                        while ($data = mysqli_fetch_array($tampil)) :
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
                            <td class="text-center">
                                <?php if($data['status_servis'] != 'Selesai'): ?>
                                    <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalSelesai<?= $data['id_servis']; ?>" title="Selesaikan Servis">
                                        <i class="fas fa-check-circle"></i> Selesai
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="fas fa-check"></i> Selesai
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalSelesai<?= $data['id_servis']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Penyelesaian Servis: <?= strtoupper($data['nama_pelanggan']); ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="proses/servis_proses.php" method="post">
                                        <div class="modal-body">
                                            <input type="hidden" name="id_servis" value="<?= $data['id_servis']; ?>">
                                            
                                            <div class="form-group">
                                                <label>Ganti Sparepart? (Ambil dari Stok Gudang)</label>
                                                <select class="form-control" name="id_barang">
                                                    <option value="">-- Tidak Ada Pergantian / Hanya Jasa --</option>
                                                    <?php 
                                                    // PERBAIKAN: Stok diambil dari k.stok milik master_katalog bukan b.stok
                                                    $g_barang = mysqli_query($koneksi, "SELECT b.id_barang, b.id_katalog, b.harga, k.stok, k.nama_produk, k.merek 
                                                                                        FROM tabel_barang b
                                                                                        INNER JOIN master_katalog k ON b.id_katalog = k.id_katalog
                                                                                        WHERE k.jenis='Sparepart' AND k.stok > 0");
                                                    while($b = mysqli_fetch_array($g_barang)){
                                                        echo "<option value='".$b['id_barang']."'>".strtoupper($b['merek']." - ".$b['nama_produk'])." (Stok Gudang: ".$b['stok'].") - Rp ".number_format($b['harga'],0,',','.')."</option>";
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
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" name="servis_selesai" class="btn btn-success">Simpan & Selesai</button>
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

<div class="modal fade" id="addServisModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pendaftaran Servis Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="proses/servis_proses.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Pelanggan</label>
                        <input type="text" class="form-control" name="nama_pelanggan" placeholder="Masukkan nama pemilik" required style="text-transform:uppercase">
                    </div>
                    <div class="form-group">
                        <label>No. HP / WhatsApp</label>
                        <input type="text" class="form-control" name="no_hp" placeholder="Contoh: 081234xxx" required>
                    </div>
                    <div class="form-group">
                        <label>Tipe / Model Laptop Konsumen</label>
                        <input type="text" class="form-control" name="tipe_laptop" placeholder="Contoh: ASUS ROG, MACBOOK AIR, LENOVO IDEAPAD" required style="text-transform:uppercase">
                    </div>
                    <div class="form-group">
                        <label>Keluhan / Kerusakan</label>
                        <textarea class="form-control" name="keluhan" rows="3" placeholder="Contoh: Laptop mati total setelah kena air" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="tambah_servis" class="btn btn-primary">Daftarkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('layouts/footer.php'); ?>