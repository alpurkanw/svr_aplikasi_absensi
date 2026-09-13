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
                        <h1 class="h3 text-gray-800">Data Karyawan</h1><button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahKaryawan">Tambah Karyawan</button>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Departemen</th>
                                            <th>Total Salary</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($employees as $employee): ?><tr>
                                                <td><a href="<?= site_url('admin/employees/detail-page/' . (int) $employee['id']) ?>"><?= html_escape($employee['employee_code']) ?></a></td>
                                                <td><a href="<?= site_url('admin/employees/detail-page/' . (int) $employee['id']) ?>"><?= html_escape($employee['name']) ?></a></td>
                                                <td><?= html_escape($employee['position_name']) ?></td>
                                                <td><?= html_escape($employee['department_name']) ?></td>
                                                <td>
                                                    <div>Rp <?= number_format((float) $employee['total_salary'], 0, ',', '.') ?></div>
                                                    <small class="text-muted"><?= (int) $employee['salary_component_count'] ?>/<?= (int) $total_components ?> komponen</small>
                                                </td>
                                                <td><a class="btn btn-sm btn-primary" href="<?= site_url('admin/employees/detail-page/' . (int) $employee['id']) ?>"><i class="fas fa-eye"></i> Lihat Detail</a></td>
                                            </tr><?php endforeach; ?>
                                    </tbody>
                                </table>
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
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
    <script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js') ?>"></script>
    <div class="modal fade" id="modalTambahKaryawan" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="post" action="<?= site_url('admin/employees/save') ?>" id="formTambahKaryawan">
                    <div class="modal-header">
                        <h5 class="modal-title" id="judulFormKaryawan">Tambah Karyawan</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="employeeId">
                        <div class="form-row">
                            <div class="form-group col-md-6"><label>Employee Code</label><input required maxlength="100" name="employee_code" class="form-control"></div>
                            <div class="form-group col-md-6"><label>Nama</label><input required maxlength="150" name="name" class="form-control"></div>
                            <div class="form-group col-md-6"><label>No. KTP</label><input maxlength="50" name="nik" class="form-control"></div>
                            <div class="form-group col-md-6"><label>Jenis Kelamin</label><select name="gender" class="form-control">
                                    <option value="">- Pilih -</option>
                                    <option value="M">Laki-laki</option>
                                    <option value="F">Perempuan</option>
                                </select></div>
                            <div class="form-group col-md-6"><label>Tempat Lahir</label><input maxlength="100" name="birth_place" class="form-control"></div>
                            <div class="form-group col-md-6"><label>Tanggal Lahir</label><input type="date" name="birth_date" class="form-control"></div>
                            <div class="form-group col-md-6"><label>No. Telp</label><input maxlength="30" name="phone" class="form-control"></div>
                            <div class="form-group col-md-6"><label>Alamat</label><textarea name="address" class="form-control" rows="2"></textarea></div>
                            <div class="form-group col-md-6"><label>Jabatan</label><input name="position_name" class="form-control"></div>
                            <div class="form-group col-md-6"><label>Departemen</label><input name="department_name" class="form-control"></div>
                            <div class="form-group col-md-6"><label>Status Kepegawaian</label><select name="employment_status" class="form-control">
                                    <option value="TETAP">TETAP</option>
                                    <option value="KONTRAK">KONTRAK</option>
                                    <option value="HARIAN">HARIAN</option>
                                </select></div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button class="btn btn-success" type="submit" id="btnSimpanKaryawan">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDetailGaji" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form id="formDetailGaji">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Gaji <span id="namaKaryawanGaji"></span></h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="employeeGajiId">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-success">Penambah Gaji</h6>
                                <?php foreach ($components as $component): if ($component['component_type'] === 'EARNING'): ?>
                                        <div class="form-group"><label><?= html_escape($component['name']) ?></label><input type="number" min="0" step="0.01" class="form-control" name="amount[<?= (int) $component['id'] ?>]" data-component="<?= (int) $component['id'] ?>"></div>
                                <?php endif;
                                endforeach; ?>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-danger">Pengurang Gaji</h6>
                                <?php foreach ($components as $component): if ($component['component_type'] === 'DEDUCTION'): ?>
                                        <div class="form-group"><label><?= html_escape($component['name']) ?></label><input type="number" min="0" step="0.01" class="form-control" name="amount[<?= (int) $component['id'] ?>]" data-component="<?= (int) $component['id'] ?>"></div>
                                <?php endif;
                                endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button><button class="btn btn-primary" type="submit">Simpan Detail Gaji</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalDetailKaryawan" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Karyawan</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body" id="slipCetak">
                    <div class="text-center mb-3">
                        <h4>SLIP GAJI</h4>
                        <div id="periodeSlip"><?= date('F Y') ?></div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div><strong>Nama:</strong> <span id="detailNama"></span></div>
                            <div><strong>Kode:</strong> <span id="detailKode"></span></div>
                            <div><strong>No. KTP:</strong> <span id="detailNik"></span></div>
                            <div><strong>Jenis Kelamin:</strong> <span id="detailGender"></span></div>
                        </div>
                        <div class="col-md-6">
                            <div><strong>Tempat Lahir:</strong> <span id="detailBirthPlace"></span></div>
                            <div><strong>Tanggal Lahir:</strong> <span id="detailBirthDate"></span></div>
                            <div><strong>No. Telp:</strong> <span id="detailPhone"></span></div>
                            <div><strong>Alamat:</strong> <span id="detailAddress"></span></div>
                            <div><strong>Jabatan:</strong> <span id="detailJabatan"></span></div>
                            <div><strong>Departemen:</strong> <span id="detailDepartemen"></span></div>
                            <div><strong>Status:</strong> <span id="detailStatus"></span></div>
                            <div><strong>Tanggal Masuk:</strong> <span id="detailJoin"></span></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-success">Pendapatan</h6>
                            <table class="table table-sm">
                                <tbody id="slipPendapatan"></tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total Pendapatan</th>
                                        <th class="text-right" id="totalPendapatan"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-danger">Potongan</h6>
                            <table class="table table-sm">
                                <tbody id="slipPotongan"></tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total Potongan</th>
                                        <th class="text-right" id="totalPotongan"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="border-top pt-3 text-right"><strong>TAKE HOME PAY: <span id="takeHomePay"></span></strong></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button><button type="button" class="btn btn-primary" id="btnPrintSlip"><i class="fas fa-print"></i> Print Slip Gaji</button></div>
            </div>
        </div>
    </div>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #slipCetak,
            #slipCetak * {
                visibility: visible;
            }

            #slipCetak {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
    <script>
        $('#dataTable').DataTable({
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
            },
            order: [
                [1, 'asc']
            ]
        });

        $('#formTambahKaryawan').on('submit', function(event) {
            event.preventDefault();
            var form = this;
            var submitButton = $(form).find('button[type="submit"]');
            var isEdit = $('#employeeId').val() !== '';
            submitButton.prop('disabled', true);
            $.ajax({
                url: form.action,
                type: 'POST',
                data: $(form).serialize(),
                dataType: 'json'
            }).done(function(response) {
                if (response.success) {
                    $('#modalTambahKaryawan').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: isEdit ? 'Data karyawan berhasil diperbarui.' : 'Karyawan berhasil ditambahkan.',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.reload();
                    });
                    return;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.message || (isEdit ? 'Data karyawan gagal diperbarui.' : 'Karyawan gagal ditambahkan.')
                });
            }).fail(function(xhr) {
                var message = isEdit ? 'Data karyawan gagal diperbarui.' : 'Karyawan gagal ditambahkan.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = $('<div>').html(xhr.responseJSON.message).text();
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message
                });
            }).always(function() {
                submitButton.prop('disabled', false);
            });
        });

        function resetEmployeeForm() {
            $('#formTambahKaryawan')[0].reset();
            $('#employeeId').val('');
            $('#judulFormKaryawan').text('Tambah Karyawan');
            $('#btnSimpanKaryawan').text('Simpan');
        }

        $('.btn-edit-karyawan').on('click', function() {
            var id = $(this).data('id');
            $.getJSON('<?= site_url('admin/employees/detail') ?>/' + id, function(response) {
                var employee = response.data.employee;
                $('#employeeId').val(id);
                $('#formTambahKaryawan [name="employee_code"]').val(employee.employee_code);
                $('#formTambahKaryawan [name="name"]').val(employee.name);
                $('#formTambahKaryawan [name="nik"]').val(employee.nik === '-' ? '' : employee.nik);
                $('#formTambahKaryawan [name="gender"]').val(employee.gender === '-' ? '' : employee.gender);
                $('#formTambahKaryawan [name="birth_place"]').val(employee.birth_place === '-' ? '' : employee.birth_place);
                $('#formTambahKaryawan [name="birth_date"]').val(employee.birth_date === '-' ? '' : employee.birth_date);
                $('#formTambahKaryawan [name="phone"]').val(employee.phone === '-' ? '' : employee.phone);
                $('#formTambahKaryawan [name="address"]').val(employee.address === '-' ? '' : employee.address);
                $('#formTambahKaryawan [name="position_name"]').val(employee.position_name === '-' ? '' : employee.position_name);
                $('#formTambahKaryawan [name="department_name"]').val(employee.department_name === '-' ? '' : employee.department_name);
                $('#formTambahKaryawan [name="employment_status"]').val(employee.employment_status);
                $('#judulFormKaryawan').text('Edit Karyawan');
                $('#btnSimpanKaryawan').text('Perbarui');
                $('#modalTambahKaryawan').modal('show');
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Data karyawan tidak dapat dimuat.'
                });
            });
        });

        $('#modalTambahKaryawan').on('hidden.bs.modal', resetEmployeeForm);

        $('.btn-delete-karyawan').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            Swal.fire({
                icon: 'warning',
                title: 'Nonaktifkan karyawan?',
                text: 'Karyawan ' + name + ' akan disembunyikan dari daftar aktif. Histori absensi dan payroll tetap aman.',
                showCancelButton: true,
                confirmButtonText: 'Ya, nonaktifkan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (!result.isConfirmed) {
                    return;
                }
                $.post('<?= site_url('admin/employees/delete') ?>/' + id, {}, function(response) {
                    if (!response.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Karyawan gagal dinonaktifkan.'
                        });
                        return;
                    }
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Karyawan berhasil dinonaktifkan.'
                    }).then(function() {
                        window.location.reload();
                    });
                }, 'json').fail(function(xhr) {
                    var message = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Karyawan gagal dinonaktifkan.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: message
                    });
                });
            });
        });

        function rupiah(value) {
            return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
        }
        $('.btn-detail-karyawan').on('click', function() {
            $.getJSON('<?= site_url('admin/employees/detail') ?>/' + $(this).data('id'), function(response) {
                var employee = response.data.employee,
                    earnings = response.data.salary || [],
                    income = 0,
                    deduction = 0;
                $('#detailNama').text(employee.name);
                $('#detailKode').text(employee.employee_code);
                $('#detailNik').text(employee.nik || '-');
                $('#detailGender').text(employee.gender === 'M' ? 'Laki-laki' : (employee.gender === 'F' ? 'Perempuan' : '-'));
                $('#detailBirthPlace').text(employee.birth_place || '-');
                $('#detailBirthDate').text(employee.birth_date || '-');
                $('#detailPhone').text(employee.phone || '-');
                $('#detailAddress').text(employee.address || '-');
                $('#detailJabatan').text(employee.position_name || '-');
                $('#detailDepartemen').text(employee.department_name || '-');
                $('#detailStatus').text(employee.employment_status || '-');
                $('#detailJoin').text(employee.join_date || '-');
                $('#slipPendapatan, #slipPotongan').empty();
                $.each(earnings, function(_, item) {
                    var amount = Number(item.amount || 0),
                        row = '<tr><td>' + $('<div>').text(item.name).html() + '</td><td class="text-right">' + rupiah(amount) + '</td></tr>';
                    if (item.component_type === 'EARNING') {
                        income += amount;
                        $('#slipPendapatan').append(row);
                    } else {
                        deduction += amount;
                        $('#slipPotongan').append(row);
                    }
                });
                $('#totalPendapatan').text(rupiah(income));
                $('#totalPotongan').text(rupiah(deduction));
                $('#takeHomePay').text(rupiah(income - deduction));
                $('#modalDetailKaryawan').modal('show');
            });
        });
        $('#btnPrintSlip').on('click', function() {
            window.print();
        });
        $('.btn-detail-gaji').on('click', function() {
            var id = $(this).data('id');
            $('#employeeGajiId').val(id);
            $('#namaKaryawanGaji').text('- ' + $(this).data('name'));
            $('#formDetailGaji input[data-component]').val('');
            $.getJSON('<?= site_url('admin/employees/salary-details') ?>/' + id, function(response) {
                $.each(response.data || {}, function(componentId, amount) {
                    $('#formDetailGaji input[data-component="' + componentId + '"]').val(amount);
                });
                $('#modalDetailGaji').modal('show');
            });
        });
        $('#formDetailGaji').on('submit', function(event) {
            event.preventDefault();
            var id = $('#employeeGajiId').val();
            $.post('<?= site_url('admin/employees/salary-details') ?>/' + id + '/save', $(this).serialize(), function() {
                $('#modalDetailGaji').modal('hide');
                alert('Detail gaji berhasil disimpan.');
            }, 'json');
        });
    </script>
</body>

</html>