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
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php $this->load->view('admin/02_topbar'); ?>
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h3 text-gray-800 mb-1">Detail Karyawan</h1>
                            <div class="text-muted"><?= html_escape($employee['employee_code']) ?> - <?= html_escape($employee['name']) ?></div>
                        </div>
                        <a class="btn btn-secondary" href="<?= site_url('admin/employees') ?>"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card shadow mb-4">
                                <div class="card-header"><i class="fas fa-user mr-2"></i>Informasi Karyawan</div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach (
                                            array(
                                                'Employee Code' => 'employee_code',
                                                'Nama' => 'name',
                                                'No. KTP' => 'nik',
                                                'Jenis Kelamin' => 'gender',
                                                'Tempat Lahir' => 'birth_place',
                                                'Tanggal Lahir' => 'birth_date',
                                                'No. Telp' => 'phone',
                                                'Alamat' => 'address',
                                                'Jabatan' => 'position_name',
                                                'Departemen' => 'department_name',
                                                'Status Kepegawaian' => 'employment_status',
                                                'Tanggal Masuk' => 'join_date'
                                            ) as $label => $field
                                        ): ?>
                                            <div class="col-md-6 mb-3"><small class="text-muted d-block"><?= $label ?></small><strong><?= html_escape($employee[$field] ?: '-') ?></strong></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card shadow mb-4">
                                <div class="card-header"><i class="fas fa-fingerprint mr-2"></i>Status Fingerprint</div>
                                <div class="card-body">
                                    <h4 class="text-primary"><?= count($fingerprints) ?>/3</h4>
                                    <p class="mb-2">Fingerprint terdaftar</p>
                                    <?php if ($fingerprints): ?>
                                        <?php foreach ($fingerprints as $fingerprint): ?>
                                            <div class="mb-1"><span class="badge badge-success">Slot <?= (int) $fingerprint['finger_slot'] ?></span> <?= html_escape($fingerprint['device_sn'] ?: 'Semua device') ?></div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Belum ada fingerprint.</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card shadow mb-4">
                                <div class="card-header"><i class="fas fa-money-bill-wave mr-2"></i>Komponen Gaji</div>
                                <div class="card-body">
                                    <h5 class="text-success">Rp <?= number_format((float) $total_salary, 0, ',', '.') ?></h5>
                                    <p><?= count($salary_components) ?>/<?= (int) $total_components ?> komponen aktif</p>
                                    <a class="btn btn-primary btn-block" href="<?= site_url('admin/payroll-details/' . (int) $employee['id']) ?>"><i class="fas fa-cog mr-2"></i>Manage Komponen Gaji</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalEditKaryawan"><i class="fas fa-edit mr-2"></i>Edit</button>
                        <button type="button" class="btn btn-danger" id="btnDeleteKaryawan"><i class="fas fa-trash mr-2"></i>Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditKaryawan" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="formEditKaryawan" method="post" action="<?= site_url('admin/employees/save') ?>">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Karyawan</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="<?= (int) $employee['id'] ?>">
                        <div class="form-row">
                            <div class="form-group col-md-6"><label>Employee Code</label><input required maxlength="100" name="employee_code" class="form-control" value="<?= html_escape($employee['employee_code']) ?>"></div>
                            <div class="form-group col-md-6"><label>Nama</label><input required maxlength="150" name="name" class="form-control" value="<?= html_escape($employee['name']) ?>"></div>
                            <div class="form-group col-md-6"><label>No. KTP</label><input maxlength="50" name="nik" class="form-control" value="<?= html_escape($employee['nik']) ?>"></div>
                            <div class="form-group col-md-6"><label>Jenis Kelamin</label><select name="gender" class="form-control">
                                    <option value="">- Pilih -</option>
                                    <option value="M" <?= $employee['gender'] === 'M' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="F" <?= $employee['gender'] === 'F' ? 'selected' : '' ?>>Perempuan</option>
                                </select></div>
                            <div class="form-group col-md-6"><label>Tempat Lahir</label><input maxlength="100" name="birth_place" class="form-control" value="<?= html_escape($employee['birth_place']) ?>"></div>
                            <div class="form-group col-md-6"><label>Tanggal Lahir</label><input type="date" name="birth_date" class="form-control" value="<?= html_escape($employee['birth_date']) ?>"></div>
                            <div class="form-group col-md-6"><label>No. Telp</label><input maxlength="30" name="phone" class="form-control" value="<?= html_escape($employee['phone']) ?>"></div>
                            <div class="form-group col-md-6"><label>Alamat</label><textarea name="address" class="form-control" rows="2"><?= html_escape($employee['address']) ?></textarea></div>
                            <div class="form-group col-md-6"><label>Jabatan</label><input name="position_name" class="form-control" value="<?= html_escape($employee['position_name']) ?>"></div>
                            <div class="form-group col-md-6"><label>Departemen</label><input name="department_name" class="form-control" value="<?= html_escape($employee['department_name']) ?>"></div>
                            <div class="form-group col-md-6"><label>Status Kepegawaian</label><select name="employment_status" class="form-control">
                                    <option value="TETAP" <?= $employee['employment_status'] === 'TETAP' ? 'selected' : '' ?>>TETAP</option>
                                    <option value="KONTRAK" <?= $employee['employment_status'] === 'KONTRAK' ? 'selected' : '' ?>>KONTRAK</option>
                                    <option value="HARIAN" <?= $employee['employment_status'] === 'HARIAN' ? 'selected' : '' ?>>HARIAN</option>
                                </select></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button class="btn btn-primary" type="submit">Simpan Perubahan</button></div>
                </form>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js') ?>"></script>
    <script>
        $('#formEditKaryawan').on('submit', function(event) {
            event.preventDefault();
            var form = this;
            $.post(form.action, $(form).serialize(), function(response) {
                if (response.success) {
                    $('#modalEditKaryawan').modal('hide');
                    Swal.fire('Berhasil', 'Data karyawan berhasil diperbarui.', 'success').then(function() {
                        window.location.reload();
                    });
                    return;
                }
                Swal.fire('Gagal', response.message || 'Data karyawan gagal diperbarui.', 'error');
            }, 'json').fail(function(xhr) {
                var response = xhr.responseJSON || {};
                Swal.fire('Gagal', response.message || 'Data karyawan gagal diperbarui.', 'error');
            });
        });
        $('#btnDeleteKaryawan').on('click', function() {
            Swal.fire({
                icon: 'warning',
                title: 'Hapus karyawan?',
                text: 'Karyawan akan dinonaktifkan. Histori absensi dan payroll tetap aman.',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (!result.isConfirmed) return;
                $.post('<?= site_url('admin/employees/delete/' . (int) $employee['id']) ?>', {}, function(response) {
                    if (!response.success) {
                        Swal.fire('Gagal', response.message || 'Karyawan gagal dihapus.', 'error');
                        return;
                    }
                    Swal.fire('Berhasil', 'Karyawan berhasil dinonaktifkan.', 'success').then(function() {
                        window.location.href = '<?= site_url('admin/employees') ?>';
                    });
                }, 'json').fail(function(xhr) {
                    var response = xhr.responseJSON || {};
                    Swal.fire('Gagal', response.message || 'Karyawan gagal dihapus.', 'error');
                });
            });
        });
    </script>
</body>

</html>