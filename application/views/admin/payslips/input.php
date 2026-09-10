<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html_escape($title) ?></title>
    <link href="<?= base_url('assets/adminsb/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body id="page-top">
<div id="wrapper">
    <?php $this->load->view('admin/01_sidebar'); ?>
    <div id="content-wrapper" class="d-flex flex-column"><div id="content">
        <?php $this->load->view('admin/02_topbar'); ?>
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-gray-800 mb-0">Input Slip Gaji</h1>
            </div>
            <form id="payslipForm">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="form-row align-items-end">
                            <div class="form-group col-md-4 mb-md-0">
                                <label for="period">Periode Pembayaran</label>
                                <input type="month" id="period" name="period" value="<?= html_escape($period) ?>" class="form-control" required>
                            </div>
                            <div class="form-group col-md-8 mb-md-0 text-md-right">
                                <button type="button" id="selectAll" class="btn btn-outline-primary mr-2"><i class="fas fa-check-double"></i> Select All</button>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-file-invoice-dollar"></i> Buat Slip Gaji</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-1"></i>
                    <strong><?= (int) $ready_count ?></strong> karyawan siap diproses.
                    <span class="mx-2">|</span>
                    <strong><?= (int) $without_components_count ?></strong> karyawan belum memiliki komponen gaji dan tidak ditampilkan.
                </div>
                <div class="card shadow mb-4"><div class="card-body"><div class="table-responsive">
                    <table class="table table-bordered" id="employeesTable" width="100%">
                        <thead><tr><th class="text-center"><input type="checkbox" id="selectAllCheckbox"></th><th>Kode</th><th>Nama</th><th>Jabatan</th><th>Departemen</th><th>Status</th></tr></thead>
                        <tbody>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td class="text-center"><input type="checkbox" class="employee-checkbox" name="employee_ids[]" value="<?= (int) $employee['id'] ?>"></td>
                                <td><?= html_escape($employee['employee_code']) ?></td>
                                <td><?= html_escape($employee['name']) ?></td>
                                <td><?= html_escape($employee['position_name'] ?: '-') ?></td>
                                <td><?= html_escape($employee['department_name'] ?: '-') ?></td>
                                <td><?= html_escape($employee['employment_status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div></div></div>
            </form>
        </div>
    </div></div>
</div>
<script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script>
$(function () {
    function setAll(checked) {
        $('.employee-checkbox').prop('checked', checked);
        $('#selectAllCheckbox').prop('checked', checked);
    }

    $('#selectAll, #selectAllCheckbox').on('click', function () {
        setAll(!$('.employee-checkbox:checked').length);
    });

    $('#payslipForm').on('submit', function (event) {
        event.preventDefault();
        if (!$('.employee-checkbox:checked').length) {
            Swal.fire('Perhatian', 'Pilih minimal satu karyawan.', 'warning');
            return;
        }
        $.post('<?= site_url('admin/payslips/generate') ?>', $(this).serialize(), function (response) {
            Swal.fire(response.success ? 'Berhasil' : 'Gagal', response.message, response.success ? 'success' : 'error');
        }, 'json').fail(function (xhr) {
            Swal.fire('Gagal', xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Slip gaji gagal dibuat.', 'error');
        });
    });
});
</script>
</body>
</html>
