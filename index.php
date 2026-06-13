<?php 
include('proses/dashboard_data.php'); 
include('layouts/header.php'); 
include('layouts/sidebar.php'); 
include('layouts/topbar.php'); 
?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Utama Toko Komputer</h1>
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Sedang Dikerjakan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_proses; ?> Unit</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-primary"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kas Pendapatan</div>
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

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sistem Informasi Manajemen Toko Komputer</h6>
                </div>
                <div class="card-body">
                    <p>Selamat Datang di Web Admin Toko Komputer & Servis Laptop. Gunakan menu di sebelah kiri untuk mengelola stok gudang, memantau antrean laptop konsumen, serta memproses transaksi biaya servis secara real-time.</p>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include('layouts/footer.php'); ?>