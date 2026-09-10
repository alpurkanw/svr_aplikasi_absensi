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
</head>

<body id="page-top">
    <div id="wrapper">
        <?php $this->load->view('admin/01_sidebar'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php $this->load->view('admin/02_topbar'); ?>
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h3 text-gray-800 mb-0">Aplikasi</h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="appConfigTable">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Nilai</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>nama_perusahaan</td>
                                            <td><?= html_escape($config['nama_perusahaan']) ?></td>
                                            <td rowspan="6" class="align-middle"><button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#appConfigModal">Edit</button></td>
                                        </tr>
                                        <tr>
                                            <td>alamat</td>
                                            <td><?= html_escape($config['alamat']) ?></td>
                                        </tr>
                                        <tr>
                                            <td>rule_absensi.mulai_masuk</td>
                                            <td><?= html_escape($config['rule_absensi']['mulai_masuk']) ?>:00</td>
                                        </tr>
                                        <tr>
                                            <td>rule_absensi.akhir_masuk</td>
                                            <td><?= html_escape($config['rule_absensi']['akhir_masuk']) ?>:00</td>
                                        </tr>
                                        <tr>
                                            <td>rule_absensi.mulai_pulang</td>
                                            <td><?= html_escape($config['rule_absensi']['mulai_pulang']) ?>:00</td>
                                        </tr>
                                        <tr>
                                            <td>rule_absensi.akhir_pulang</td>
                                            <td><?= html_escape($config['rule_absensi']['akhir_pulang']) ?>:00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="appConfigModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="appConfigForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Konfigurasi Aplikasi</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label>Nama perusahaan</label><input type="text" name="nama_perusahaan" class="form-control" value="<?= html_escape($config['nama_perusahaan']) ?>" maxlength="150" required></div>
                        <div class="form-group"><label>Alamat</label><input type="text" name="alamat" class="form-control" value="<?= html_escape($config['alamat']) ?>" maxlength="255" required></div>
                        <h6 class="font-weight-bold text-gray-800 mt-4">Rule absensi</h6>
                        <div class="form-row">
                            <div class="form-group col-md-6"><label>Mulai masuk</label><input type="number" name="mulai_masuk" class="form-control" min="0" max="24" value="<?= html_escape($config['rule_absensi']['mulai_masuk']) ?>" required></div>
                            <div class="form-group col-md-6"><label>Akhir masuk</label><input type="number" name="akhir_masuk" class="form-control" min="0" max="24" value="<?= html_escape($config['rule_absensi']['akhir_masuk']) ?>" required></div>
                            <div class="form-group col-md-6"><label>Mulai pulang</label><input type="number" name="mulai_pulang" class="form-control" min="0" max="24" value="<?= html_escape($config['rule_absensi']['mulai_pulang']) ?>" required></div>
                            <div class="form-group col-md-6"><label>Akhir pulang</label><input type="number" name="akhir_pulang" class="form-control" min="0" max="24" value="<?= html_escape($config['rule_absensi']['akhir_pulang']) ?>" required></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button class="btn btn-primary" type="submit">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js') ?>"></script>
    <script>
        $('#appConfigTable').DataTable({
            paging: false,
            info: false,
            language: {
                search: 'Cari:',
                zeroRecords: 'Data tidak ditemukan'
            }
        });

        $('#appConfigForm').on('submit', function(event) {
            event.preventDefault();
            $.post('<?= site_url('admin/app-config/save') ?>', $(this).serialize(), function(response) {
                Swal.fire(response.success ? 'Berhasil' : 'Gagal', response.message, response.success ? 'success' : 'error').then(function() {
                    if (response.success) window.location.reload();
                });
            }, 'json').fail(function(xhr) {
                var response = xhr.responseJSON || {};
                Swal.fire('Gagal', response.message || 'Konfigurasi gagal disimpan.', 'error');
            });
        });
    </script>
</body>

</html>