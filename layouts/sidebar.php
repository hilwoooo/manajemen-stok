<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-desktop"></i>
        </div>
        <div class="sidebar-brand-text mx-4">Ungu Campus</div>
    </a>

    <hr class="sidebar-divider my-0">

    <?php

    global $koneksi;
    // Ambil nama file halaman aktif saat ini (misal: index.php)
    $halaman_aktif = basename($_SERVER['PHP_SELF']);

    // QUERY 1: Ambil semua Menu Utama (yang parent_id nya 0)
    $query_main = mysqli_query($koneksi, "SELECT * FROM tabel_menu WHERE parent_id = 0 ORDER BY urutan ASC");
    while ($main = mysqli_fetch_array($query_main)) :
        
        $id_main = $main['id_menu'];
        
        // QUERY 2: Cek apakah menu utama ini memiliki sub-menu
        $query_sub = mysqli_query($koneksi, "SELECT * FROM tabel_menu WHERE parent_id = '$id_main' ORDER BY urutan ASC");
        $punya_sub = mysqli_num_rows($query_sub);

        // JIKA TIDAK PUNYA SUB-MENU (Contoh: Dashboard)
        if ($punya_sub == 0) :
            $status_active = ($halaman_aktif == $main['link']) ? 'active' : '';
    ?>
            <li class="nav-item <?= $status_active; ?>">
                <a class="nav-link" href="/manajemen-stok/<?= $main['link']; ?>">
                    <i class="<?= $main['icon']; ?>"></i>
                    <span><?= $main['nama_menu']; ?></span>
                </a>
            </li>
    <?php 
        // JIKA PUNYA SUB-MENU (Contoh: Manajemen Gudang, Ruang Teknisi)
        else : 
            // Logika Biar Otomatis Terbuka (Expand) kalau sub-menunya sedang dibuka
            $sub_links = [];
            // Ambil semua link sub-menu untuk dicek
            $query_cek_links = mysqli_query($koneksi, "SELECT link FROM tabel_menu WHERE parent_id = '$id_main'");
            while($cl = mysqli_fetch_array($query_cek_links)) { $sub_links[] = $cl['link']; }
            
            $is_show = in_array($halaman_aktif, $sub_links) ? 'show' : '';
            $is_active = in_array($halaman_aktif, $sub_links) ? 'active' : '';
            $is_collapsed = in_array($halaman_aktif, $sub_links) ? '' : 'collapsed';
    ?>
            <li class="nav-item <?= $is_active; ?>">
                <a class="nav-link <?= $is_collapsed; ?>" href="#" data-toggle="collapse" data-target="#collapse<?= $id_main; ?>"
                    aria-expanded="true" aria-controls="collapse<?= $id_main; ?>">
                    <i class="<?= $main['icon']; ?>"></i>
                    <span><?= $main['nama_menu']; ?></span>
                </a>
                <div id="collapse<?= $id_main; ?>" class="collapse <?= $is_show; ?>" data-parent="#accordionSidebar">
                    <div class=" py-2 collapse-inner rounded dwn">
                        <?php 
                        // Tampilkan semua anak/sub-menu di dalam drop-down
                        while ($sub = mysqli_fetch_array($query_sub)) : 
                            $sub_active = ($halaman_aktif == $sub['link']) ? 'active font-weight-bold bg-none' : '';
                        ?>
                            <a class="collapse-item ac <?= $sub_active; ?> text-white" href="/manajemen-stok/<?= $sub['link']; ?>"><?= $sub['nama_menu']; ?></a>
                        <?php endwhile; ?>
                    </div>
                </div>
            </li>
    <?php 
        endif; 
    endwhile; 
    ?>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">