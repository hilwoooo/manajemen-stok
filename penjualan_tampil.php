<?php 
include('koneksi.php'); 
include('layouts/header.php'); 
include('layouts/sidebar.php'); 
include('layouts/topbar.php'); 
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Riwayat & Log Penjualan Langsung (Barang Keluar)</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <button class="h3 btn btn-primary mb-3" data-toggle="modal" data-target="#addPenjualanModal">
                Input Penjualan 
            </button>
            
            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Nama Pembeli</th>
                            <th class="text-center">No. HP</th>
                            <th class="text-center">Sparepart Keluar</th>
                            <th class="text-center">Jenis</th>
                            <th class="text-center">Harga Jual Transaksi</th>
                            <th class="text-center">Tanggal Transaksi</th>
                    </thead>
                    <tbody>
                        <?php 
                        // Query mengambil b.harga (harga modal saat masuk) dan b.tanggal_masuk sebagai bukti FIFO
                        $query = "SELECT tk.*, b.harga AS harga_modal, b.tanggal_masuk, k.nama_produk, k.merek, k.jenis 
                                  FROM tabel_keluar tk
                                  INNER JOIN tabel_barang b ON tk.id_barang = b.id_barang
                                  INNER JOIN master_katalog k ON b.id_katalog = k.id_katalog
                                  ORDER BY tk.id_keluar DESC";
                        
                        $tampil = mysqli_query($koneksi, $query);
                        $i = 1; 
                        while ($data = mysqli_fetch_array($tampil)) :
                        ?>
                        <tr>
                            <td class="text-center"><?= $i; ?></td>
                            <td style="text-transform:uppercase"><strong><?= $data['nama_pembeli']; ?></strong></td>
                            <td><?= $data['no_hp']; ?></td>
                            <td style="text-transform:uppercase"><?= $data['merek'] . ' - ' . $data['nama_produk']; ?></td>
                            <td class="text-center">
                                <span class="badge badge-info"><?= $data['jenis']; ?></span>
                            </td>
                            <td><strong><?= 'Rp ' . number_format($data['total_bayar'], 0, ',', '.'); ?></strong></td>
                            <td class="text-center"><?= date('d-m-Y H:i', strtotime($data['tanggal_keluar'])); ?> WIB</td>
                            
                        </tr>
                        <?php $i++; endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addPenjualanModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Form Transaksi Kasir</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="proses/penjualan_proses.php" method="post">
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label>Nama Pembeli</label>
                        <input type="text" class="form-control" name="nama_pembeli" placeholder="Contoh: CASH / BAPAK BUDI" required style="text-transform:uppercase">
                    </div>

                    <div class="form-group">
                        <label>No. HP (Opsional)</label>
                        <input type="text" class="form-control" name="no_hp" placeholder="Contoh: 081234xxx" value="-">
                    </div>
                        
                    <div class="form-group">
                        <label>Pilih Sparepart (Garansi Ringkas - 1 Produk 1 Baris FIFO)</label>
                        <select class="form-control" name="id_barang" required>
                            <option value="">-- Pilih Sparepart yang Dibeli --</option>
                            <?php 
        // QUERY BARU: Mencari ID Barang yang paling kecil (paling dulu masuk) untuk setiap katalog yang stoknya masih ada
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
        
        while($b = mysqli_fetch_array($q_barang)){
            echo "<option value='".$b['id_barang']."'>".strtoupper($b['merek']." - ".$b['nama_produk'])." | Harga: Rp ".number_format($b['harga'],0,',','.')." (Batch Terlama: ".date('d-m-Y', strtotime($b['tanggal_masuk'])).")</option>";
        }
        ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="tambah_penjualan" class="btn btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('layouts/footer.php'); ?>