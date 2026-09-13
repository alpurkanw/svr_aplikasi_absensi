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
                                            <td rowspan="10" class="align-middle"><button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#appConfigModal">Edit</button></td>
                                        </tr>
                                        <tr>
                                            <td>alamat</td>
                                            <td><?= html_escape($config['alamat']) ?></td>
                                        </tr>
                                        <tr>
                                            <td>potong_gapok</td>
                                            <td><?= $config['potong_gapok'] ? 'true' : 'false' ?></td>
                                        </tr>
                                        <tr>
                                            <td>rule_absensi.mulai_masuk <br>Variabel ini diisi dengan jam Masuk Kantor, <br>Dari sini juga akan mulai dihitung keterlambatan</td>
                                            <td><?= html_escape($config['rule_absensi']['mulai_masuk']) ?></td>
                                        </tr>
                                        <tr>
                                            <td>rule_absensi.akhir_masuk</td>
                                            <td><?= html_escape($config['rule_absensi']['akhir_masuk']) ?></td>
                                        </tr>
                                        <tr>
                                            <td>rule_absensi.mulai_pulang</td>
                                            <td><?= html_escape($config['rule_absensi']['mulai_pulang']) ?></td>
                                        </tr>
                                        <tr>
                                            <td>rule_absensi.akhir_pulang</td>
                                            <td><?= html_escape($config['rule_absensi']['akhir_pulang']) ?></td>
                                        </tr>
                                        <?php foreach ($config['rule_absensi']['potongan_keterlambatan'] as $rule): ?>
                                            <tr>
                                                <td>rule_absensi.potongan_keterlambatan.<?= (int) $rule['mulai_menit'] ?>_<?= (int) $rule['sampai_menit'] ?></td>
                                                <td><?= html_escape($rule['persentase']) ?>%</td>
                                            </tr>
                                        <?php endforeach; ?>
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
                <form id="appConfigForm" method="post" action="<?= site_url('admin/app-config/save') ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Konfigurasi Aplikasi</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group"><label>Nama perusahaan</label><input type="text" name="nama_perusahaan" class="form-control" value="<?= html_escape($config['nama_perusahaan']) ?>" maxlength="150" required></div>
                        <div class="form-group"><label>Alamat</label><input type="text" name="alamat" class="form-control" value="<?= html_escape($config['alamat']) ?>" maxlength="255" required></div>
                        <div class="form-group"><label>Dasar potongan gaji pokok</label><select name="potong_gapok" class="form-control"><option value="true" <?= $config['potong_gapok'] ? 'selected' : '' ?>>true</option><option value="false" <?= !$config['potong_gapok'] ? 'selected' : '' ?>>false</option></select><small class="form-text text-muted">true: hanya Gaji Pokok. false: Gaji Pokok dan komponen earning FIXED sebagai penambah.</small></div>
                        <h6 class="font-weight-bold text-gray-800 mt-4">Rule absensi</h6>
                        <div class="form-row">
                            <div class="form-group col-md-6"><label>Mulai masuk</label><input type="text" name="mulai_masuk" class="form-control" pattern="(?:[01]\d|2[0-4]):[0-5]\d" placeholder="HH:MM" value="<?= html_escape($config['rule_absensi']['mulai_masuk']) ?>" required></div>
                            <div class="form-group col-md-6"><label>Akhir masuk</label><input type="text" name="akhir_masuk" class="form-control" pattern="(?:[01]\d|2[0-4]):[0-5]\d" placeholder="HH:MM" value="<?= html_escape($config['rule_absensi']['akhir_masuk']) ?>" required></div>
                            <div class="form-group col-md-6"><label>Mulai pulang</label><input type="text" name="mulai_pulang" class="form-control" pattern="(?:[01]\d|2[0-4]):[0-5]\d" placeholder="HH:MM" value="<?= html_escape($config['rule_absensi']['mulai_pulang']) ?>" required></div>
                            <div class="form-group col-md-6"><label>Akhir pulang</label><input type="text" name="akhir_pulang" class="form-control" pattern="(?:[01]\d|2[0-4]):[0-5]\d" placeholder="HH:MM" value="<?= html_escape($config['rule_absensi']['akhir_pulang']) ?>" required></div>
                        </div>
                        <h6 class="font-weight-bold text-gray-800 mt-4">Potongan keterlambatan</h6>
                        <div class="form-row">
                            <div class="form-group col-md-4"><label>Telat 6-10 menit (%)</label><input type="number" name="potongan_6_10" class="form-control" min="0" max="100" step="0.01" value="<?= html_escape($config['rule_absensi']['potongan_keterlambatan'][0]['persentase']) ?>" required></div>
                            <div class="form-group col-md-4"><label>Telat 11-15 menit (%)</label><input type="number" name="potongan_11_15" class="form-control" min="0" max="100" step="0.01" value="<?= html_escape($config['rule_absensi']['potongan_keterlambatan'][1]['persentase']) ?>" required></div>
                            <div class="form-group col-md-4"><label>Telat 16-20 menit (%)</label><input type="number" name="potongan_16_20" class="form-control" min="0" max="100" step="0.01" value="<?= html_escape($config['rule_absensi']['potongan_keterlambatan'][2]['persentase']) ?>" required></div>
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
        <?php if ($this->session->flashdata('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: <?= json_encode($this->session->flashdata('success')) ?>
            });
        <?php elseif ($this->session->flashdata('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: <?= json_encode($this->session->flashdata('error')) ?>
            });
        <?php endif; ?>

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
            var akhirMasuk = $('[name="akhir_masuk"]').val().split(':');
            var mulaiPulang = $('[name="mulai_pulang"]').val().split(':');
            var akhirMasukMenit = (Number(akhirMasuk[0]) * 60) + Number(akhirMasuk[1]);
            var mulaiPulangMenit = (Number(mulaiPulang[0]) * 60) + Number(mulaiPulang[1]);
            if (akhirMasukMenit >= mulaiPulangMenit) {
                Swal.fire('Gagal', 'Akhir masuk harus lebih kecil dari mulai pulang.', 'error');
                return;
            }
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