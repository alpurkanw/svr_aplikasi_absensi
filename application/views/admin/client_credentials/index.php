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
                        <h1 class="h3 text-gray-800 mb-0">Credential client</h1>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="credentialTable">
                                    <thead>
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Nilai</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>pin_open_daftar_karyawan</td>
                                            <td><span>********</span></td>
                                            <td><button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#credentialModal">Edit</button></td>
                                        </tr>
                                        <tr>
                                            <td>token_client_credential</td>
                                            <td><span>********</span></td>
                                            <td><button class="btn btn-sm btn-outline-warning btn-generate-token">Generate</button></td>
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
    <div class="modal fade" id="credentialModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="credentialForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit PIN Client</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label>PIN buka Daftar Karyawan</label><input type="password" name="pin_open_daftar_karyawan" class="form-control" maxlength="100" value="<?= html_escape($pin_open_daftar_karyawan) ?>" required></div>
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
        $('#credentialTable').DataTable({
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                zeroRecords: 'Data tidak ditemukan',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });

        $('#credentialForm').on('submit', function(event) {
            event.preventDefault();
            $.post('<?= site_url('admin/client-credentials/save') ?>', $(this).serialize(), function(response) {
                Swal.fire(response.success ? 'Berhasil' : 'Gagal', response.message, response.success ? 'success' : 'error').then(function() {
                    if (response.success) window.location.reload();
                });
            }, 'json');
        });
        $('.btn-generate-token').on('click', function() {
            Swal.fire({
                title: 'Generate token baru?',
                text: 'Token lama tidak akan berlaku lagi untuk client yang menggunakannya.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Generate',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (!result.isConfirmed) return;
                $.post('<?= site_url('admin/client-credentials/generate') ?>', function(response) {
                    if (!response.success) {
                        Swal.fire('Gagal', response.message, 'error');
                        return;
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Token berhasil dibuat',
                        html: '<p>Simpan token ini untuk konfigurasi client desktop.</p><textarea class="form-control" rows="3" readonly>' + $('<div>').text(response.token).html() + '</textarea>',
                        confirmButtonText: 'Selesai'
                    });
                }, 'json');
            });
        });
    </script>
</body>

</html>