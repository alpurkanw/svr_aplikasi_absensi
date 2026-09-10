<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html_escape($title) ?></title>
    <link href="<?= base_url('assets/adminsb/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #d1d3e2;
            border-radius: .35rem;
            padding: .375rem .75rem;
        }

        table.dataTable thead th {
            white-space: nowrap;
        }

        .component-type {
            min-width: 110px;
        }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
    <?php $this->load->view('admin/01_sidebar'); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php $this->load->view('admin/02_topbar'); ?>
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 text-gray-800 mb-0">Komponen Gaji</h1>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalKomponenGaji">
                        <i class="fas fa-plus"></i> Tambah Komponen
                    </button>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="componentsTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No. Urut</th>
                                        <th>Kode</th>
                                        <th>Nama Komponen</th>
                                        <th>Kelompok</th>
                                        <th>Metode Perhitungan</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach (array('EARNING' => 'Penambah', 'DEDUCTION' => 'Pengurang') as $type => $type_label): ?>
                                    <?php foreach ($components[$type] as $component): ?>
                                        <tr>
                                            <td><?= (int) $component['sort_order'] ?></td>
                                            <td><?= html_escape($component['code']) ?></td>
                                            <td><?= html_escape($component['name']) ?></td>
                                            <td>
                                                <span class="badge badge-<?= $type === 'EARNING' ? 'success' : 'danger' ?> component-type">
                                                    <?= $type_label ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $calculation_labels = array(
                                                    'FIXED' => 'Nilai tetap',
                                                    'PERCENTAGE' => 'Persentase',
                                                    'PER_MINUTE' => 'Per menit',
                                                    'RANGE' => 'Berdasarkan rentang',
                                                );
                                                echo html_escape($calculation_labels[$component['calculation_type']] ?? $component['calculation_type']);
                                                ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?= (int) $component['is_active'] === 1 ? 'success' : 'secondary' ?>">
                                                    <?= (int) $component['is_active'] === 1 ? 'Aktif' : 'Nonaktif' ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-edit-component"
                                                    data-id="<?= (int) $component['id'] ?>"
                                                    data-sort-order="<?= (int) $component['sort_order'] ?>"
                                                    data-code="<?= html_escape($component['code']) ?>"
                                                    data-name="<?= html_escape($component['name']) ?>"
                                                    data-type="<?= html_escape($component['component_type']) ?>"
                                                    data-calculation="<?= html_escape($component['calculation_type']) ?>"
                                                    data-status="<?= (int) $component['is_active'] ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-toggle-status"
                                                    data-id="<?= (int) $component['id'] ?>"
                                                    title="<?= (int) $component['is_active'] === 1 ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                                    <i class="fas fa-<?= (int) $component['is_active'] === 1 ? 'eye-slash' : 'eye' ?>"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="sticky-footer bg-white">
            <div class="container my-auto"><div class="copyright text-center my-auto"><span>HCIS Payroll</span></div></div>
        </footer>
    </div>
</div>

<div class="modal fade" id="modalKomponenGaji" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document"><div class="modal-content">
        <form id="formKomponenGaji">
            <input type="hidden" name="id" id="componentId">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKomponenTitle">Tambah Komponen Gaji</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-4"><label>No. Urut</label><input type="number" name="sort_order" id="componentSortOrder" class="form-control" min="1" required></div>
                    <div class="form-group col-md-8"><label>Kode Komponen</label><input type="text" name="code" id="componentCode" class="form-control" maxlength="50" required></div>
                </div>
                <div class="form-group"><label>Nama Komponen</label><input type="text" name="name" id="componentName" class="form-control" maxlength="100" required></div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Kelompok</label>
                        <select name="component_type" id="componentType" class="form-control" required>
                            <option value="EARNING">Penambah / Pendapatan</option>
                            <option value="DEDUCTION">Pengurang / Potongan</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Metode Perhitungan</label>
                        <select name="calculation_type" id="componentCalculation" class="form-control" required>
                            <option value="FIXED">Nilai tetap</option>
                            <option value="PERCENTAGE">Persentase</option>
                            <option value="PER_MINUTE">Per menit</option>
                            <option value="RANGE">Berdasarkan rentang</option>
                        </select>
                    </div>
                </div>
                <div class="form-group"><label>Status</label><select name="is_active" id="componentStatus" class="form-control"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button type="submit" class="btn btn-primary">Simpan</button></div>
        </form>
    </div></div>
</div>

<script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js') ?>"></script>
<script>
$(function () {
    $('#componentsTable').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
        language: {
            lengthMenu: 'Tampilkan _MENU_ data',
            search: 'Cari:',
            zeroRecords: 'Data komponen tidak ditemukan',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Belum ada data komponen',
            paginate: { previous: 'Sebelumnya', next: 'Berikutnya' }
        },
        columnDefs: [{ orderable: false, targets: 6 }],
        order: [[0, 'asc']]
    });

    $('#formKomponenGaji').on('submit', function (event) {
        event.preventDefault();
        $.ajax({
            url: '<?= site_url('admin/payroll-components/save') ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json'
        }).done(function (response) {
            if (!response.success) {
                Swal.fire('Gagal', response.message || 'Data gagal disimpan.', 'error');
                return;
            }
            $('#modalKomponenGaji').modal('hide');
            Swal.fire('Berhasil', response.message, 'success').then(function () { window.location.reload(); });
        }).fail(function (xhr) {
            Swal.fire('Gagal', xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Data gagal disimpan.', 'error');
        });

        $('#modalKomponenGaji').on('show.bs.modal', function (event) {
            if (!$(event.relatedTarget).hasClass('btn-edit-component')) {
                $('#componentSortOrder').val('1');
            }
        });
    });

    $('.btn-edit-component').on('click', function () {
        $('#componentId').val($(this).data('id'));
        $('#componentSortOrder').val($(this).data('sort-order'));
        $('#componentCode').val($(this).data('code'));
        $('#componentName').val($(this).data('name'));
        $('#componentType').val($(this).data('type'));
        $('#componentCalculation').val($(this).data('calculation'));
        $('#componentStatus').val($(this).data('status'));
        $('#modalKomponenTitle').text('Edit Komponen Gaji');
        $('#modalKomponenGaji').modal('show');
    });

    $('.btn-toggle-status').on('click', function () {
        var id = $(this).data('id');
        $.post('<?= site_url('admin/payroll-components/toggle-status') ?>/' + id, {}, function (response) {
            Swal.fire(response.success ? 'Berhasil' : 'Gagal', response.message, response.success ? 'success' : 'error')
                .then(function () { if (response.success) window.location.reload(); });
        }, 'json');
    });
});
</script>
</body>
</html>
