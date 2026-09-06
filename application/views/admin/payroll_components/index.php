<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html_escape($title) ?></title>
    <link href="<?= base_url('assets/adminsb/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .component-card {
            border: 1px solid #e3e6f0;
            border-radius: .75rem;
            overflow: hidden;
            background: #fff;
        }

        .component-card .card-header {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .component-card .table th,
        .component-card .table td {
            vertical-align: middle;
        }

        .badge-group {
            min-width: 120px;
            text-align: center;
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
                        <div>
                            <h1 class="h3 text-gray-800 mb-0">Komponen Gaji</h1>
                            <small class="text-muted">Daftar komponen pendapatan dan potongan payroll</small>
                        </div>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalKomponenGaji">
                            <i class="fas fa-plus"></i> Tambah Komponen
                        </button>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 mb-4">
                            <div class="card shadow h-100 py-2 border-left-success">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pendapatan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($components['EARNING']) ?> komponen</div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-wallet fa-2x text-success"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-4">
                            <div class="card shadow h-100 py-2 border-left-danger">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Potongan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= count($components['DEDUCTION']) ?> komponen</div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-minus-circle fa-2x text-danger"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="component-card shadow-sm">
                                <div class="card-header bg-success text-white py-3">
                                    <i class="fas fa-wallet mr-2"></i> Pendapatan
                                </div>
                                <div class="card-body p-0">
                                    <table class="table mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Nama</th>
                                                <th>Kode</th>
                                                <th>Hitung</th>
                                                <th>Status</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($components['EARNING'])): ?>
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-4">Belum ada komponen pendapatan.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($components['EARNING'] as $component): ?>
                                                    <tr>
                                                        <td><?= html_escape($component['name']) ?></td>
                                                        <td><?= html_escape($component['code']) ?></td>
                                                        <td><?= html_escape($component['calculation_type']) ?></td>
                                                        <td>
                                                            <?php if ((int) $component['is_active'] === 1): ?>
                                                                <span class="badge badge-success badge-group">Aktif</span>
                                                            <?php else: ?>
                                                                <span class="badge badge-secondary badge-group">Nonaktif</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-component" data-id="<?= (int) $component['id'] ?>" data-code="<?= html_escape($component['code']) ?>" data-name="<?= html_escape($component['name']) ?>" data-type="<?= html_escape($component['component_type']) ?>" data-calculation="<?= html_escape($component['calculation_type']) ?>" data-status="<?= (int) $component['is_active'] ?>">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary btn-toggle-status" data-id="<?= (int) $component['id'] ?>">
                                                                <?= (int) $component['is_active'] === 1 ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>' ?>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-4">
                            <div class="component-card shadow-sm">
                                <div class="card-header bg-danger text-white py-3">
                                    <i class="fas fa-minus-circle mr-2"></i> Potongan
                                </div>
                                <div class="card-body p-0">
                                    <table class="table mb-0">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Nama</th>
                                                <th>Kode</th>
                                                <th>Hitung</th>
                                                <th>Status</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($components['DEDUCTION'])): ?>
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-4">Belum ada komponen potongan.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($components['DEDUCTION'] as $component): ?>
                                                    <tr>
                                                        <td><?= html_escape($component['name']) ?></td>
                                                        <td><?= html_escape($component['code']) ?></td>
                                                        <td><?= html_escape($component['calculation_type']) ?></td>
                                                        <td>
                                                            <?php if ((int) $component['is_active'] === 1): ?>
                                                                <span class="badge badge-success badge-group">Aktif</span>
                                                            <?php else: ?>
                                                                <span class="badge badge-secondary badge-group">Nonaktif</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-component" data-id="<?= (int) $component['id'] ?>" data-code="<?= html_escape($component['code']) ?>" data-name="<?= html_escape($component['name']) ?>" data-type="<?= html_escape($component['component_type']) ?>" data-calculation="<?= html_escape($component['calculation_type']) ?>" data-status="<?= (int) $component['is_active'] ?>">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-secondary btn-toggle-status" data-id="<?= (int) $component['id'] ?>">
                                                                <?= (int) $component['is_active'] === 1 ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>' ?>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
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

    <div class="modal fade" id="modalKomponenGaji" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formKomponenGaji">
                    <input type="hidden" name="id" id="componentId">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalKomponenTitle">Tambah Komponen Gaji</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kode Komponen</label>
                            <input type="text" name="code" id="componentCode" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Komponen</label>
                            <input type="text" name="name" id="componentName" class="form-control" required>
                        </div>
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
                        <div class="form-group">
                            <label>Status</label>
                            <select name="is_active" id="componentStatus" class="form-control">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
    <script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js') ?>"></script>
    <script>
        function resetModal() {
            $('#componentId').val('');
            $('#modalKomponenTitle').text('Tambah Komponen Gaji');
            $('#formKomponenGaji')[0].reset();
            $('#componentStatus').val('1');
        }

        $('#formKomponenGaji').on('submit', function (event) {
            event.preventDefault();
            $.ajax({
                url: '<?= site_url('admin/payroll-components/save') ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json'
            }).done(function (response) {
                if (response.success) {
                    $('#modalKomponenGaji').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message || 'Data tersimpan.',
                        confirmButtonText: 'OK'
                    }).then(function () {
                        window.location.reload();
                    });
                    return;
                }
                Swal.fire({ icon: 'error', title: 'Gagal', text: response.message || 'Terjadi kesalahan.' });
            }).fail(function (xhr) {
                var message = 'Terjadi kesalahan.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({ icon: 'error', title: 'Gagal', text: message });
            });
        });

        $('.btn-edit-component').on('click', function () {
            $('#componentId').val($(this).data('id'));
            $('#componentCode').val($(this).data('code'));
            $('#componentName').val($(this).data('name'));
            $('#componentType').val($(this).data('type'));
            $('#componentCalculation').val($(this).data('calculation'));
            $('#componentStatus').val($(this).data('status'));
            $('#modalKomponenTitle').text('Edit Komponen Gaji');
            $('#modalKomponenGaji').modal('show');
        });

        $('.btn-toggle-status').on('click', function () {
            var componentId = $(this).data('id');
            $.ajax({
                url: '<?= site_url('admin/payroll-components/toggle-status') ?>/' + componentId,
                type: 'POST',
                dataType: 'json'
            }).done(function (response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message || 'Status berhasil diubah.',
                        confirmButtonText: 'OK'
                    }).then(function () {
                        window.location.reload();
                    });
                    return;
                }
                Swal.fire({ icon: 'error', title: 'Gagal', text: response.message || 'Gagal mengubah status.' });
            }).fail(function (xhr) {
                var message = 'Gagal mengubah status.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({ icon: 'error', title: 'Gagal', text: message });
            });
        });

        $('#modalKomponenGaji').on('hidden.bs.modal', function () {
            resetModal();
        });
    </script>
</body>

</html>
