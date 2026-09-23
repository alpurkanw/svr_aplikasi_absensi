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
        <?php $this->load->view('admin/01_sidebar'); ?><div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php $this->load->view('admin/02_topbar'); ?><div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3 text-gray-800 mb-0">Karyawan Bermasalah</h1>
                        <form method="get" action="<?= site_url('admin/payroll-reports/issues') ?>" class="form-inline"><input type="month" name="period" value="<?= html_escape($period) ?>" class="form-control mr-2" required><button class="btn btn-primary"><i class="fas fa-search mr-1"></i>Tampilkan</button></form>
                    </div>
                    <div class="alert alert-info"><strong><?= count($rows) ?></strong> karyawan aktif memiliki catatan untuk periode <strong><?= html_escape($parts['label']) ?></strong>. Status payroll: <strong><?= html_escape($period_status) ?></strong>.</div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Pemeriksaan Sebelum Payroll</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Departemen</th>
                                            <th>Komponen</th>
                                            <th>Attendance</th>
                                            <th>Payslip</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!$rows): ?><tr>
                                                <td colspan="8" class="text-center text-success"><i class="fas fa-check-circle mr-1"></i>Tidak ada karyawan bermasalah pada pemeriksaan ini.</td>
                                            </tr><?php else: ?>
                                            <?php foreach ($rows as $index => $row): ?><tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= html_escape($row['employee_code']) ?></td>
                                                    <td><?= html_escape($row['name']) ?></td>
                                                    <td><?= html_escape($row['department_name'] ?: '-') ?></td>
                                                    <td class="text-center"><?= (int) $row['component_count'] ?></td>
                                                    <td class="text-center"><?= (int) $row['attendance_count'] ?></td>
                                                    <td class="text-center"><?= (int) $row['payslip_count'] ?></td>
                                                    <td><?php foreach ($row['issues'] as $issue): ?><span class="badge badge-warning mr-1 mb-1"><?= html_escape($issue) ?></span><?php endforeach; ?></td>
                                                </tr><?php endforeach; ?>
                                        <?php endif; ?></tbody>
                                </table>
                            </div><small class="text-muted">Pemeriksaan mencakup karyawan aktif yang diikutkan payroll. Periode DRAFT tidak dianggap bermasalah hanya karena belum memiliki payslip atau attendance hasil proses.</small>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto"><span>HCIS Payroll</span></div>
                </div>
            </footer>
        </div>
    </div>
    <script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js') ?>"></script>
</body>

</html>