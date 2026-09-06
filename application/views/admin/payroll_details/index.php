<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html_escape($title) ?></title>
    <link href="<?= base_url('assets/adminsb/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
    <?php $this->load->view('admin/01_sidebar'); ?>
    <div id="content-wrapper" class="d-flex flex-column"><div id="content">
        <?php $this->load->view('admin/02_topbar'); ?>
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div><h1 class="h3 text-gray-800 mb-0">Detail Komponen Gaji</h1><small class="text-muted">Atur nominal komponen tetap setiap karyawan.</small></div>
            </div>
            <div class="card shadow mb-4"><div class="card-body"><div class="table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>Kode</th><th>Nama</th><th>Jabatan</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php if (!$employees): ?><tr><td colspan="5" class="text-center text-muted">Belum ada karyawan aktif.</td></tr><?php endif; ?>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?= html_escape($employee['employee_code']) ?></td>
                            <td><?= html_escape($employee['name']) ?></td>
                            <td><?= html_escape($employee['position_name'] ?: '-') ?></td>
                            <td><?= html_escape($employee['employment_status']) ?></td>
                            <td><a class="btn btn-sm btn-primary" href="<?= site_url('admin/payroll-details/' . (int) $employee['id']) ?>"><i class="fas fa-edit"></i> Atur Komponen</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div></div></div>
        </div>
    </div></div>
</div>
<script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
