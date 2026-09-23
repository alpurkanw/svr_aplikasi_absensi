<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center mb-4"
        href="<?= site_url('admin/attendance') ?>">
        <div class="sidebar-brand-icon">
            <i class="fas fa-building"></i>
        </div>
        <div class="sidebar-brand-text mx-2">HCIS PAYROLL</div>
    </a>


    <!-- MASTER KARYAWAN -->
    <div class="sidebar-heading">MASTER KARYAWAN</div>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('admin/employees') ?>">
            <i class="fas fa-fw fa-users"></i>
            <span>Data Karyawan</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('admin/payroll-components') ?>">
            <i class="fas fa-fw fa-id-card"></i>
            <span>Komponen Gaji</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('admin/payslips') ?>">
            <i class="fas fa-fw fa-file-invoice-dollar"></i>
            <span>Lihat Slip Gaji</span>
        </a>
    </li>


    <!-- MONITORING -->
    <div class="sidebar-heading">MONITORING</div>

    <li class="nav-item py-0">
        <a class="nav-link my-0" href="<?= site_url('admin/presensi') ?>">
            <i class="fas fa-fw fa-list"></i>
            <span>Log Absensi</span>
        </a>
    </li>

    <li class="nav-item py-0">
        <a class="nav-link my-0" href="<?= site_url('admin/attendance') ?>">
            <i class="fas fa-fw fa-list"></i>
            <span>Rekap Absensi Harian</span>
        </a>
    </li>

    <li class="nav-item py-0">
        <a class="nav-link my-0" href="<?= site_url('admin/attendance-monthly') ?>">
            <i class="fas fa-fw fa-list"></i>
            <span>Rekap Absensi Bulanan</span>
        </a>
    </li>

    <li class="nav-item py-0">
        <a class="nav-link my-0" href="<?= site_url('admin/holidays') ?>">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Manage Holiday</span>
        </a>
    </li>


    <!-- TRANSAKSI -->
    <div class="sidebar-heading">TRANSAKSI</div>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('admin/payroll') ?>">
            <i class="fas fa-fw fa-calculator"></i>
            <span>Payroll</span>
        </a>
    </li>

    <!-- LAPORAN PAYROLL -->
    <div class="sidebar-heading">LAPORAN PAYROLL</div>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('admin/payroll-reports/summary') ?>">
            <i class="fas fa-fw fa-chart-bar"></i>
            <span>Rekap Payroll Periode</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('admin/payroll-reports/issues') ?>">
            <i class="fas fa-fw fa-exclamation-triangle"></i>
            <span>Karyawan Bermasalah</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('admin/payroll-reports/deductions') ?>">
            <i class="fas fa-fw fa-minus-circle"></i>
            <span>Deduction per Jenis</span>
        </a>
    </li>


    <!-- SETTING -->
    <div class="sidebar-heading">SETTING</div>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('admin/app-config') ?>">
            <i class="fas fa-fw fa-cog"></i>
            <span>Aplikasi</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('admin/client-credentials') ?>">
            <i class="fas fa-fw fa-cog"></i>
            <span>Client Credentials</span>
        </a>
    </li>


    <!-- SIDEBAR DIVIDER -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- LOGOUT -->
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('Auth/logout') ?>">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </li>

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End Sidebar -->