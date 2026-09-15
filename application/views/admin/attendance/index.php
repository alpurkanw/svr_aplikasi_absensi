<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html_escape($title) ?></title>
    <link href="<?= base_url('assets/adminsb/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/css/sb-admin-2.min.css') ?>" rel="stylesheet">
</head>

<body>
    <div id="wrapper">
        <?php $this->load->view('admin/01_sidebar'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php $this->load->view('admin/02_topbar'); ?>
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3 text-gray-800">Rekap Absensi Harian</h1>
                        <form method="get" action="<?= site_url('admin/attendance') ?>" class="form-inline"><input type="date" name="date" value="<?= html_escape($date) ?>" class="form-control mr-2"><button class="btn btn-primary">Tampilkan</button></form>
                    </div>
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                            <div class="card border-left-primary shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Karyawan Absen / Total</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int) $attendance_count ?> / <?= (int) $total_employee_count ?></div>
                                    <small class="text-muted">Masih ada <?= (int) max(0, $total_employee_count - $attendance_count) ?> orang yang belum absen.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                            <div class="card border-left-info shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Nominal Keterlambatan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format((float) $total_fine, 0, ',', '.') ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                            <div class="card border-left-warning shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Jumlah Karyawan Terlambat</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int) $late_count ?> orang</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                            <div class="card border-left-danger shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Jumlah Tidak Terlambat</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int) $not_late_count ?> orang</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Masuk</th>
                                            <th>Pulang</th>
                                            <th>Terlambat</th>
                                            <th>Denda</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody><?php foreach ($rows as $row): ?><tr>
                                                <td><?= html_escape($row['employee_code']) ?></td>
                                                <td><?= html_escape($row['name']) ?></td>
                                                <td><?= $row['check_in'] ? html_escape(date('H:i', strtotime($row['check_in']))) : '-' ?></td>
                                                <td><?= $row['check_out'] ? html_escape(date('H:i', strtotime($row['check_out']))) : '-' ?></td>
                                                <td><?= (int) $row['late_minutes'] ?> menit </td>
                                                <td>Rp <?= number_format((float) $row['late_penalty_amount'], 0, ',', '.') ?> (<?= number_format((float) $row['late_penalty_percentage'], 0, ',', '.') ?>%)</td>
                                                <?php $status_badge = $row['attendance_status'] === 'TERLAMBAT' ? 'warning' : ($row['attendance_status'] === 'TIDAK HADIR' ? 'danger' : 'success'); ?>
                                                <td><span class="badge badge-<?= $status_badge ?>"><?= html_escape($row['attendance_status']) ?></span></td>
                                            </tr><?php endforeach; ?></tbody>
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
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
    <script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js') ?>"></script>
</body>

</html>