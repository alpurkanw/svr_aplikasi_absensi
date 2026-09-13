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
                    <h1 class="h3 text-gray-800 mb-0">Manage Holiday</h1>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#holidayModal"><i class="fas fa-plus mr-1"></i>Tambah Holiday</button>
                </div>
                <?php if ($this->session->flashdata('success')): ?><div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div><?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div><?php endif; ?>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead><tr><th>Tanggal</th><th>Nama Libur</th><th>Tipe</th><th>Status</th><th>Aksi</th></tr></thead>
                                <tbody>
                                <?php foreach ($holidays as $holiday): ?>
                                    <tr>
                                        <td><?= html_escape($holiday['holiday_date']) ?></td>
                                        <td><?= html_escape($holiday['name']) ?></td>
                                        <td><?= html_escape($holiday['holiday_type']) ?></td>
                                        <td><?= (int) $holiday['is_active'] === 1 ? 'Aktif' : 'Nonaktif' ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-holiday" data-toggle="modal" data-target="#holidayModal"
                                                data-id="<?= (int) $holiday['id'] ?>" data-date="<?= html_escape($holiday['holiday_date']) ?>"
                                                data-name="<?= html_escape($holiday['name']) ?>" data-type="<?= html_escape($holiday['holiday_type']) ?>"
                                                data-active="<?= (int) $holiday['is_active'] ?>">Edit</button>
                                            <a class="btn btn-sm btn-danger" href="<?= site_url('admin/holidays/delete/' . (int) $holiday['id']) ?>" onclick="return confirm('Hapus data hari libur ini?')">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php if (!$holidays): ?><tr><td colspan="5" class="text-center">Belum ada data hari libur.</td></tr><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="holidayModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <form method="post" action="<?= site_url('admin/holidays/save') ?>">
            <div class="modal-header"><h5 class="modal-title">Tambah Holiday</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
            <div class="modal-body">
                <input type="hidden" name="id" id="holidayId" value="0">
                <div class="form-group"><label>Tanggal</label><input type="date" name="holiday_date" id="holidayDate" class="form-control" required></div>
                <div class="form-group"><label>Nama libur</label><input type="text" name="name" id="holidayName" class="form-control" maxlength="150" required></div>
                <div class="form-group"><label>Tipe</label><select name="holiday_type" id="holidayType" class="form-control"><option value="NASIONAL">Nasional</option><option value="CUTI_BERSAMA">Cuti Bersama</option><option value="PERUSAHAAN">Perusahaan</option></select></div>
                <div class="form-group"><label>Status</label><select name="is_active" id="holidayActive" class="form-control"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div></div>
</div>
<script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js') ?>"></script>
<script>
$('.edit-holiday').on('click', function () {
    var button = $(this);
    $('#holidayId').val(button.data('id'));
    $('#holidayDate').val(button.data('date'));
    $('#holidayName').val(button.data('name'));
    $('#holidayType').val(button.data('type'));
    $('#holidayActive').val(button.data('active'));
    $('#holidayModal .modal-title').text('Edit Holiday');
});
$('#holidayModal').on('hidden.bs.modal', function () {
    $('#holidayId').val('0');
    $('#holidayDate').val('');
    $('#holidayName').val('');
    $('#holidayType').val('NASIONAL');
    $('#holidayActive').val('1');
    $('#holidayModal .modal-title').text('Tambah Holiday');
});
</script>
</body>
</html>
