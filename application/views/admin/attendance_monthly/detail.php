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
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php $this->load->view('admin/02_topbar'); ?>
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h3 text-gray-800 mb-1">Detail Absensi Bulanan</h1>
                            <div class="text-muted"><?= html_escape($employee['employee_code'] . ' - ' . $employee['name']) ?></div>
                        </div>
                        <a class="btn btn-secondary" href="<?= site_url('admin/attendance-monthly?month=' . rawurlencode($month)) ?>"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card border-left-primary shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Nominal Denda</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format((float) $total_fine, 0, ',', '.') ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="card border-left-warning shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Jumlah Hari Terlambat</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int) $late_days ?> hari</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-left-danger shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Jumlah Hari Tidak Masuk</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int) $absent_days ?> hari</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                Periode: <strong><?= html_escape($period_start) ?> s/d <?= html_escape($period_end) ?></strong>
                            </p>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="attendanceDetailTable">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Hari</th>
                                            <th>Masuk</th>
                                            <th>Pulang</th>
                                            <th>Terlambat</th>
                                            <th>Denda</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $row): ?>
                                            <tr>
                                                <td><?= html_escape($row['date']) ?></td>
                                                <td><?= html_escape($row['day_name']) ?></td>
                                                <td><?= $row['check_in'] ? html_escape(date('H:i', $row['check_in']->getTimestamp())) : '-' ?></td>
                                                <td><?= $row['check_out'] ? html_escape(date('H:i', $row['check_out']->getTimestamp())) : '-' ?></td>
                                                <td><?= (int) $row['late_minutes'] ?> menit</td>
                                                <td>Rp <?= number_format($row['fine'], 0, ',', '.') ?></td>
                                                <td>
                                                    <?php if ($row['status'] === 'HARI LIBUR'): ?>
                                                        Hari Libur: <?= html_escape($row['holiday_name']) ?>
                                                    <?php else: ?>
                                                        <?= html_escape($row['status']) ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
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