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
                        <h1 class="h3 text-gray-800 mb-0">Deduction Berdasarkan Jenis</h1>
                        <form method="get" action="<?= site_url('admin/payroll-reports/deductions') ?>" class="form-inline"><input type="month" name="period" value="<?= html_escape($period) ?>" class="form-control mr-2" required><button class="btn btn-primary"><i class="fas fa-search mr-1"></i>Tampilkan</button></form>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Deduction</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total, 0, ',', '.') ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jenis Deduction</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($rows) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Rincian Potongan Periode <?= html_escape($parts['label']) ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Kode</th>
                                            <th>Nama Komponen</th>
                                            <th>Jumlah Karyawan</th>
                                            <th>Total Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!$rows): ?><tr>
                                                <td colspan="5" class="text-center text-muted">Belum ada deduction untuk periode ini.</td>
                                            </tr><?php else: ?><?php foreach ($rows as $index => $row): ?><tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= html_escape($row['component_code']) ?></td>
                                                <td><?= html_escape($row['component_name']) ?></td>
                                                <td class="text-center"><?= (int) $row['employee_count'] ?></td>
                                                <td class="text-right font-weight-bold">Rp <?= number_format((float) $row['total_amount'], 0, ',', '.') ?></td>
                                            </tr><?php endforeach; ?><?php endif; ?></tbody>
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