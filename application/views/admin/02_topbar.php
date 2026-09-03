<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" type="button"><i class="fa fa-bars"></i></button>
    <span class="h5 mb-0 text-gray-800">Payroll &amp; Employee Management</span>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= html_escape($this->session->userdata('nama') ?: 'Pengguna') ?></span>
                <img class="img-profile rounded-circle" src="<?= base_url('assets/adminsb/img/undraw_profile.svg') ?>">
            </a>
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <div class="dropdown-item-text"><strong>ADMIN</strong></div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?= site_url('Auth/gantiPass') ?>"><i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>Ganti Password</a>
                <a class="dropdown-item" href="<?= site_url('Auth/logout') ?>"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout</a>
            </div>
        </li>
    </ul>
</nav>
<!-- End Topbar -->