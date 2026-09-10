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
            <h1 class="h3 text-gray-800 mb-4">Lihat Slip Gaji</h1>
            <form method="get" action="<?= site_url('admin/payslips/view') ?>">
                <div class="card shadow mb-4"><div class="card-body">
                    <div class="form-row align-items-end">
                        <div class="form-group col-md-4 mb-md-0">
                            <label for="period">Periode Pembayaran</label>
                            <input type="month" id="period" name="period" value="<?= html_escape($period) ?>" class="form-control" required>
                        </div>
                        <div class="form-group col-md-8 mb-md-0 text-md-right">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan Slip Gaji</button>
                        </div>
                    </div>
                </div></div>
            </form>
            <div class="card shadow mb-4"><div class="card-body">
                <h6 class="font-weight-bold text-primary mb-3">Karyawan dengan slip gaji periode <?= html_escape($period_label) ?>: <?= count($employees) ?></h6>
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%">
                        <thead><tr><th>No.</th><th>Kode</th><th>Nama</th><th>Jabatan</th><th>Departemen</th><th>Total Penambah</th><th>Total Pengurang</th><th>Total Dibayarkan</th></tr></thead>
                        <tbody>
                        <?php if (!$employees): ?>
                            <tr><td colspan="8" class="text-center text-muted">Belum ada slip gaji pada periode ini.</td></tr>
                        <?php else: ?>
                            <?php foreach ($employees as $index => $employee): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= html_escape($employee['employee_code']) ?></td>
                                    <td><a href="#" class="employee-slip-link" data-url="<?= site_url('admin/payslips/detail/' . (int) $employee['id'] . '?period=' . rawurlencode($period)) ?>"><?= html_escape($employee['name']) ?></a></td>
                                    <td><?= html_escape($employee['position_name'] ?: '-') ?></td>
                                    <td><?= html_escape($employee['department_name'] ?: '-') ?></td>
                                    <td class="text-right"><?= number_format((float) $employee['total_earning'], 0, ',', '.') ?></td>
                                    <td class="text-right"><?= number_format((float) $employee['total_deduction'], 0, ',', '.') ?></td>
                                    <td class="text-right font-weight-bold"><?= number_format((float) $employee['total_earning'] - (float) $employee['total_deduction'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div></div>
        </div>
    </div></div>
</div>
<div class="modal fade" id="slipModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Preview Payslip</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
            <div class="modal-body" id="slipModalBody"><div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div></div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script>
$(function () {
    $('.employee-slip-link').on('click', function (event) {
        event.preventDefault();
        $('#slipModalBody').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>');
        $('#slipModal').modal('show');
        $.get($(this).data('url'), function (html) {
            $('#slipModalBody').html(html);
        }).fail(function () {
            $('#slipModalBody').html('<div class="alert alert-danger mb-0">Slip gaji tidak dapat dimuat.</div>');
        });
    });
});
</script>
</body>
</html>
