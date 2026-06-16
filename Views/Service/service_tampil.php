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
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Daftar Antrean Servis Laptop</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <button class="h3 btn btn-danger mb-3" data-toggle="modal" data-target="#addServisModal">Daftar Servis Baru</button>

            <div class="table-responsive">
                <table id="dataTable" class="table table-striped table-bordered nowrap" style="width:100%">
                    <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-center">No</th>
                            <th class="text-center">Pelanggan</th>
                            <th class="text-center">No HP</th>
                            <th class="text-center">Tipe Laptop</th>
                            <th class="text-center">Keluhan</th>
                            <th class="text-center">Sparepart Diganti</th>
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
                            
                            $id_servis_sekarang = $data['id_servis'];
                            $daftar_sparepart_tampil = "";

                            $q_detail = mysqli_query($koneksi, "SELECT k.nama_produk, k.merek FROM tabel_detail_servis d 
                                                                INNER JOIN tabel_barang b ON d.id_barang = b.id_barang 
                                                                INNER JOIN master_katalog k ON b.id_katalog = k.id_katalog 
                                                                WHERE d.id_servis = '$id_servis_sekarang'");
                            
                            if(mysqli_num_rows($q_detail) > 0) {
                                $arr_sp = [];
                                while($dt_d = mysqli_fetch_array($q_detail)) {
                                    $arr_sp[] = "- " . strtoupper($dt_d['merek'] . " - " . $dt_d['nama_produk']);
                                }
                                $daftar_sparepart_tampil = implode("<br>", $arr_sp);
                            }
                        ?>
                            <tr>
                                <td class="text-center"><?= $i; ?></td>
                                <td style="text-transform:uppercase"><strong><?= $data['nama_pelanggan']; ?></strong></td>
                                <td><?= $data['no_hp']; ?></td>
                                <td style="text-transform:uppercase"><?= $data['tipe_laptop']; ?></td>
                                <td><?= $data['keluhan']; ?></td>
                                <td><?= (!empty($daftar_sparepart_tampil)) ? $daftar_sparepart_tampil : '<em class="text-muted">Hanya Jasa</em>'; ?></td>
                                <td class="text-center">
                                    <span class="badge <?= ($data['status_servis'] == 'Antrean') ? 'badge-warning' : 'badge-success'; ?>">
                                        <?= $data['status_servis']; ?>
                                    </span>
                                </td>
                                <td><?= 'Rp ' . number_format($data['total_biaya'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <?php if ($data['status_servis'] != 'Selesai'): ?>
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
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">Penyelesaian Servis: <?= strtoupper($data['nama_pelanggan']); ?></h5>
                                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" id="id_servis_<?= $data['id_servis']; ?>" value="<?= $data['id_servis']; ?>">

                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold">Biaya Jasa Teknisi (Rupiah)</label>
                                                <input type="number" class="form-control" id="biaya_jasa_<?= $data['id_servis']; ?>" placeholder="Contoh: 100000" required>
                                            </div>

                                            <hr>
                                            <h5 class="mb-3 text-gray-800 font-weight-bold">Sparepart Yang Diganti (Opsional)</h5>
                                            
                                            <div class="wrapper-sparepart-servis" id="wrapper_servis_<?= $data['id_servis']; ?>">
                                                <div class="row form-group baris-sparepart mb-2 align-items-center">
                                                    <div class="col-md-9">
                                                        <select class="form-control select-sparepart">
                                                            <option value="">-- Tanpa Sparepart / Selesai Diganti --</option>
                                                            <?php
                                                            $g_barang = mysqli_query($koneksi, "SELECT b.id_barang, b.harga, k.stok, k.nama_produk, k.merek 
                                                            FROM tabel_barang b
                                                            INNER JOIN master_katalog AS k ON b.id_katalog = k.id_katalog
                                                            INNER JOIN tabel_kategori AS ka ON k.id_kategori = ka.id_kategori
                                                            WHERE LOWER(ka.nama_kategori) = 'sparepart' AND k.stok > 0
                                                            AND b.id_barang IN (
                                                                SELECT MIN(id_barang) 
                                                                FROM tabel_barang 
                                                                WHERE stok_masuk > 0 
                                                                GROUP BY id_katalog
                                                            )
                                                            ORDER BY k.nama_produk ASC");
                                                                                                
                                                            while ($b = mysqli_fetch_array($g_barang)) {
                                                                echo "<option value='" . $b['id_barang'] . "'>" . strtoupper($b['merek'] . " - " . $b['nama_produk']) . " (Stok: " . $b['stok'] . ") - Rp " . number_format($b['harga'], 0, ',', '.') . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="btn-group d-flex" role="group">
                                                            <button type="button" class="btn btn-success btn-tambah-sp w-100" data-id="<?= $data['id_servis']; ?>">+</button>
                                                            <button type="button" class="btn btn-danger btn-hapus-sp w-100" disabled>-</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                            <button type="button" class="btn btn-success btn-submit-selesai" data-id="<?= $data['id_servis']; ?>">Simpan & Selesai</button>
                                        </div>
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

<div class="modal fade" id="addServisModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Pendaftaran Servis Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../../proses/servis_proses.php" method="POST">
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
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" name="tambah_servis" class="btn btn-success">Daftarkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('../../layouts/footer.php'); ?>

<script>
// Tambah baris sparepart baru
$(document).on('click', '.btn-tambah-sp', function() {
    var idServis = $(this).attr('data-id');
    var wrapper = $('#wrapper_servis_' + idServis);
    var barisBaru = wrapper.find('.baris-sparepart:first').clone();
    barisBaru.find('select').val('');
    barisBaru.find('.btn-hapus-sp').removeAttr('disabled');
    wrapper.append(barisBaru);
});

// Hapus baris sparepart
$(document).on('click', '.btn-hapus-sp', function() {
    $(this).closest('.baris-sparepart').remove();
});

// Submit pakai form dinamis agar tidak terpengaruh posisi modal di DOM
$(document).on('click', '.btn-submit-selesai', function() {
    var idServis = $(this).attr('data-id');
    var biayaJasa = $('#biaya_jasa_' + idServis).val();

    if (!biayaJasa) {
        alert('Biaya jasa wajib diisi!');
        return;
    }

    // Kumpulkan semua id_barang yang dipilih
    var barangList = [];
    $('#wrapper_servis_' + idServis).find('select.select-sparepart').each(function() {
        if ($(this).val() != '') {
            barangList.push($(this).val());
        }
    });

    // Buat form dinamis dan submit ke servis_proses.php
    var form = $('<form>', {
        method: 'POST',
        action: '../../proses/servis_proses.php'
    });

    form.append($('<input>', { type: 'hidden', name: 'id_servis',     value: idServis  }));
    form.append($('<input>', { type: 'hidden', name: 'biaya_jasa',    value: biayaJasa }));
    form.append($('<input>', { type: 'hidden', name: 'servis_selesai', value: ''       }));

    $.each(barangList, function(i, val) {
        form.append($('<input>', { type: 'hidden', name: 'id_barang[]', value: val }));
    });

    $('body').append(form);
    form.submit();
});
</script>