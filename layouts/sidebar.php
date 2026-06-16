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
    
    $request_uri = $_SERVER['SCRIPT_NAME'];
    $halaman_aktif = str_replace('/manajemen-stok/', '', $request_uri);

    // QUERY 1: Ambil semua Menu Utama (parent_id = 0)
    $query_main = mysqli_query($koneksi, "SELECT * FROM tabel_menu WHERE parent_id = 0 ORDER BY urutan ASC");
    while ($main = mysqli_fetch_array($query_main)) :
        
        $id_main = $main['id_menu'];
        
        // QUERY 2: Cek apakah menu utama ini memiliki sub-menu
        $query_sub = mysqli_query($koneksi, "SELECT * FROM tabel_menu WHERE parent_id = '$id_main' ORDER BY urutan ASC");
        $punya_sub = mysqli_num_rows($query_sub);

        if ($punya_sub == 0) :
            // Menghapus slash di awal link database jika ada, agar pencocokan string bersih
            $menu_link = ltrim($main['link'], '/');
            $status_active = ($halaman_aktif == $menu_link) ? 'active' : '';
    ?>
            <li class="nav-item <?= $status_active; ?>">
                <a class="nav-link" href="/manajemen-stok/<?= $menu_link; ?>">
                    <i class="<?= $main['icon']; ?>"></i>
                    <span><?= $main['nama_menu']; ?></span>
                </a>
            </li>
    <?php 
        
        else : 
            $sub_links = [];
            $sub_menu_items = [];

            // Tampung data sub-menu ke dalam array agar tidak merusak perulangan query di bawah
            while($sm = mysqli_fetch_array($query_sub)) {
                $sub_menu_items[] = $sm;
                $sub_links[] = ltrim($sm['link'], '/');
            }
            
            // Cek apakah halaman yang sedang diakses ada di dalam daftar sub-menu ini
            $is_show = in_array($halaman_aktif, $sub_links) ? 'show' : '';
            $is_active = in_array($halaman_aktif, $sub_links) ? 'active' : '';
            $is_collapsed = in_array($halaman_aktif, $sub_links) ? '' : 'collapsed';
    ?>
            <li class="nav-item <?= $is_active; ?>">
                <a class="nav-link <?= $is_collapsed; ?>" href="#" data-toggle="collapse" data-target="#collapse<?= $id_main; ?>"
                    aria-expanded="<?= in_array($halaman_aktif, $sub_links) ? 'true' : 'false'; ?>" aria-controls="collapse<?= $id_main; ?>">
                    <i class="<?= $main['icon']; ?>"></i>
                    <span><?= $main['nama_menu']; ?></span>
                </a>
                <div id="collapse<?= $id_main; ?>" class="collapse <?= $is_show; ?>" data-parent="#accordionSidebar">
                    <div class="py-2 collapse-inner rounded dwn">
                        <?php 
                        // Loop data sub-menu dari array yang sudah kita simpan tadi
                        foreach ($sub_menu_items as $sub) : 
                            $clean_sub_link = ltrim($sub['link'], '/');
                            $sub_active = ($halaman_aktif == $clean_sub_link) ? 'active font-weight-bold bg-none' : '';
                        ?>
                            <a class="collapse-item ac <?= $sub_active; ?> text-white" href="/manajemen-stok/<?= $clean_sub_link; ?>">
                                <?= $sub['nama_menu']; ?>
                            </a>
                        <?php endforeach; ?>
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