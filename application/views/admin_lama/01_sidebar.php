<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center mb-4" href="<?= base_url(); ?>">
        <div class="sidebar-brand-icon">
            <i class="fas fa-home fa-2x"></i>
        </div>
        <div class="sidebar-brand-text mx-3">BLG</sup></div>
    </a>



    <!-- Heading -->
    <div class="sidebar-heading">
        DASHBOARD
    </div>
    <!-- Divider -->
    <hr class="sidebar-divider">


    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#dashboard"
            aria-expanded="true" aria-controls="dashboard">
            <i class="fas fa-fw fa-cog"></i>
            <span>Dashboard</span>
        </a>
        <div id="dashboard" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="<?= base_url("admin/Cdashboard/per_perumahan"); ?>">Dashboard Per Perumahan</a>
            </div>
        </div>
    </li>

    <!-- Heading -->
    <div class="sidebar-heading">
        TABEL MASTER
    </div>
    <!-- Divider -->
    <hr class="sidebar-divider">



    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item ">
        <a class="nav-link collapsed " href="#" data-toggle="collapse" data-target="#perumahan"
            aria-expanded="true" aria-controls="perumahan">
            <i class="fas fa-fw fa-cog"></i>
            <span>Perumahan</span>
        </a>
        <div id="perumahan" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="<?= base_url("admin/Cperum"); ?>">List Perumahan</a>
                <a class="collapse-item" href="<?= base_url("admin/Cperum/tambah"); ?>">Tambah Perumahan</a>
            </div>
        </div>
    </li>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#rumah"
            aria-expanded="true" aria-controls="rumah">
            <i class="fas fa-fw fa-cog"></i>
            <span>Rumah</span>
        </a>
        <div id="rumah" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="<?= base_url("admin/Crumah"); ?>">List Rumah</a>
                <a class="collapse-item" href="<?= base_url("admin/Crumah/all"); ?>">List Semua Rumah</a>
                <a class="collapse-item" href="<?= base_url("admin/Crumah/tambah"); ?>">Tambah Rumah</a>
            </div>
        </div>
    </li>


    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Pengeluaran"
            aria-expanded="true" aria-controls="Pengeluaran">
            <i class="fas fa-fw fa-cog"></i>
            <span>Kategori Pengeluaran</span>
        </a>
        <div id="Pengeluaran" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="<?= base_url("admin/Cpengeluaran/list_kateg"); ?>">Kategori Pengeluaran</a>
                <a class="collapse-item" href="<?= base_url("admin/Cpengeluaran/add_kateg_form"); ?>">Tambah Kategori</a>
            </div>
        </div>
    </li>


    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#harga"
            aria-expanded="true" aria-controls="harga">
            <i class="fas fa-fw fa-cog"></i>
            <span>Kategori Pendapatan</span>
        </a>
        <div id="harga" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="<?= base_url("admin/Charga"); ?>">List Pendapatan</a>
                <a class="collapse-item" href="<?= base_url("admin/Charga/add_harga"); ?>">Tambah Pendapatan</a>
            </div>
        </div>
    </li>



    <!-- Heading -->
    <div class="sidebar-heading mt-4">
        Transaksi
    </div>
    <!-- Divider -->
    <hr class="sidebar-divider">


    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#penjualan"
            aria-expanded="true" aria-controls="penjualan">
            <i class="fas fa-fw fa-cog"></i>
            <span>Penjualan Rumah</span>
        </a>
        <div id="penjualan" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <!-- <a class="collapse-item" href="<?= base_url("admin/Charga"); ?>">List harga</a> -->
                <a class="collapse-item" href="<?= base_url("admin/Cjual"); ?>">Input Penjualan</a>
                <a class="collapse-item" href="<?= base_url("admin/Crumah/rumah_add_item_harga"); ?>">Tambahkan Pendapatan</a>
                <!-- <a class="collapse-item" href="<?= base_url("admin/Charga"); ?>">Tambahkan Harga</a> -->
                <!-- <a class="collapse-item" href="<?= base_url("admin/Charga/cek_detail_harga"); ?>">Cek Detail Harga</a> -->
            </div>
        </div>
    </li>


    <!-- Nav Item - Tables
    <li class="nav-item ">
        <a class="nav-link" href="<?= base_url("admin/Cjual"); ?>">
            <i class="fas fa-fw fa-table"></i>
            <span>Penjualan Rumah</span></a>
    </li> -->
    <!-- Nav Item - Tables -->
    <li class="nav-item ">
        <a class="nav-link" href="<?= base_url("admin/Cbayar/trx_bayar_rmh_form"); ?>">
            <i class="fas fa-fw fa-table"></i>
            <span>Pembayaran Rumah</span></a>
    </li>


    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#outcome"
            aria-expanded="true" aria-controls="outcome">
            <i class="fas fa-fw fa-cog"></i>
            <span>Pengeluaran</span>
        </a>
        <div id="outcome" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="<?= base_url("admin/Cpengeluaran/trx_out_rmh_form"); ?>">Pengeluaran Per rumah</a>
                <a class="collapse-item" href="<?= base_url("admin/Cpengeluaran/trx_out_perum_form"); ?>">Pengeluaran Umum</a>
                <!-- <a class="collapse-item" href="cards.html">Pengeluaran Umum</a> -->
            </div>
        </div>
    </li>


    <!-- Heading -->
    <div class="sidebar-heading mt-4">
        LAPORAN
    </div>
    <!-- Divider -->
    <hr class="sidebar-divider">


    <!-- Nav Item - Tables -->

    <li class="nav-item ">
        <a class="nav-link" href="<?= base_url("admin/Claporan/lap_list_dp"); ?>">
            <i class="fas fa-fw fa-table"></i>
            <span>List Pendapatan(DP)</span></a>
    </li>


    <li class="nav-item ">
        <a class="nav-link" href="<?= base_url("admin/Claporan/lap_st_rumah_per_perum_form"); ?>">
            <i class="fas fa-fw fa-table"></i>
            <span>Status Rumah per Perumahan</span></a>
    </li>
    <li class="nav-item ">
        <a class="nav-link" href="<?= base_url("admin/Claporan/lap_hutang_cust_form"); ?>">
            <i class="fas fa-fw fa-table"></i>
            <span>Lap Hutang Customer</span></a>
    </li>


    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#lap_keluar"
            aria-expanded="true" aria-controls="lap_keluar">
            <i class="fas fa-fw fa-cog"></i>
            <span>Laporan Pengeluaran</span>
        </a>
        <div id="lap_keluar" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="<?= base_url("admin/Claporan/lap_out_per_perumahan"); ?>">UMUM Per Perumahan</a>
                <a class="collapse-item" href="<?= base_url("admin/Claporan/lap_out_rumah_per_perumahan"); ?>">RUMAH Per Perumahan</a>
                <!-- <a class="collapse-item" href="<?= base_url("admin/Claporan/lap_out_per_perumahan"); ?>">RUMAH Per Perumahan</a> -->
                <!-- <a class="collapse-item" href="<?= base_url("admin/Claporan/lap_out_per_perumahan"); ?>">Per Perumahan</a> -->
                <a class="collapse-item" href="<?= base_url("admin/Claporan/lap_out_rumah_per_perum_form"); ?>">Per Rumah</a>
                <a class="collapse-item" href="<?= base_url("admin/Claporan/lap_out_total_perumah_form"); ?>">Detail Per Rumah</a>
                <div class="dropdown-divider"></div>
                <a class="collapse-item" href="<?= base_url("admin/Claporan/lap_out_umum_per_perum_form"); ?>">Pengeluaran Umum</a>
                <!-- <a class="collapse-item" href="cards.html">Pengeluaran Umum</a> -->
            </div>
        </div>
    </li>


    <li class="nav-item ">
        <a class="nav-link" href="<?= base_url("admin/Claporan/lap_laba_rugi_form"); ?>">
            <i class="fas fa-fw fa-table"></i>
            <span>Lap Laba Rugi</span></a>
    </li>



    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Nav Item - Tables -->
    <li class="nav-item ">
        <a class="nav-link" href="<?= base_url("Auth/logout"); ?>">
            <i class="fas fa-fw fa-table"></i>
            <span>Logout</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>



</ul>
<!-- End of Sidebar -->
<link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon_04.png') ?>">