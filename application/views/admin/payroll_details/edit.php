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
                <div><h1 class="h3 text-gray-800 mb-0">Detail Komponen Gaji</h1><small class="text-muted"><?= html_escape($employee['employee_code'] . ' - ' . $employee['name']) ?> &middot; seluruh nominal gaji diatur melalui komponen</small></div>
                <a class="btn btn-secondary" href="<?= site_url('admin/payroll-details') ?>"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
            <form id="detailForm">
                <div class="row">
                    <?php foreach (array('EARNING' => array('title' => 'Penambah / Pendapatan', 'class' => 'success'), 'DEDUCTION' => array('title' => 'Pengurang / Potongan', 'class' => 'danger')) as $type => $group): ?>
                        <div class="col-lg-6 mb-4"><div class="card shadow">
                            <div class="card-header bg-<?= $group['class'] ?> text-white"><i class="fas fa-<?= $type === 'EARNING' ? 'plus' : 'minus' ?>-circle mr-2"></i><?= $group['title'] ?></div>
                            <div class="card-body">
                            <?php $found = false; foreach ($components as $component): if ($component['component_type'] !== $type) continue; $found = true; ?>
                                <div class="form-group component-row">
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input component-toggle" id="component-<?= (int) $component['id'] ?>" name="selected_components[]" value="<?= (int) $component['id'] ?>" <?= array_key_exists($component['id'], $amounts) ? 'checked' : '' ?>>
                                        <label class="custom-control-label" for="component-<?= (int) $component['id'] ?>">
                                            <?= html_escape($component['name']) ?> <small class="text-muted">(<?= html_escape($component['code']) ?>)</small>
                                        </label>
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">Rp</span></div>
                                        <input type="number" min="0" step="0.01" class="form-control component-amount" name="amount[<?= (int) $component['id'] ?>]" value="<?= html_escape(isset($amounts[$component['id']]) ? $amounts[$component['id']] : '0') ?>" <?= array_key_exists($component['id'], $amounts) ? '' : 'disabled' ?>>
                                    </div>
                                    <?php if ($component['calculation_type'] !== 'FIXED'): ?><small class="form-text text-muted">Nilai ini dapat dihitung ulang oleh proses payroll berdasarkan metode <?= html_escape($component['calculation_type']) ?>.</small><?php endif; ?>
                                </div>
                            <?php endforeach; if (!$found): ?><p class="text-muted mb-0">Belum ada komponen aktif.</p><?php endif; ?>
                            </div>
                        </div></div>
                    <?php endforeach; ?>
                </div>
                <div class="text-right mb-4"><button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Detail</button></div>
            </form>
        </div>
    </div></div>
</div>
<script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script>
$('.component-toggle').on('change', function () {
    $(this).closest('.component-row').find('.component-amount').prop('disabled', !this.checked);
});

$('#detailForm').on('submit', function (event) {
    event.preventDefault();
    $.post('<?= site_url('admin/payroll-details/' . (int) $employee['id'] . '/save') ?>', $(this).serialize(), function (response) {
        Swal.fire(response.success ? 'Berhasil' : 'Gagal', response.message, response.success ? 'success' : 'error');
    }, 'json').fail(function () { Swal.fire('Gagal', 'Detail komponen gagal disimpan.', 'error'); });
});
</script>
</body>
</html>
