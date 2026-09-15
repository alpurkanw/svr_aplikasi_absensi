<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html_escape($title) ?></title>
    <link href="<?= base_url('assets/adminsb/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
    <style>
        .clickable-row {
            cursor: pointer;
        }

        .clickable-row:hover {
            background-color: #f5f5f5;
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
                        <h1 class="h3 text-gray-800 mb-0">Rekap Absensi Bulanan</h1>
                        <form method="get" action="<?= site_url('admin/attendance-monthly') ?>" class="form-inline">
                            <input type="month" name="month" value="<?= html_escape($month) ?>" class="form-control mr-2" required>
                            <button class="btn btn-primary"><i class="fas fa-search mr-1"></i>Tampilkan</button>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Denda</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format((float) $total_fine, 0, ',', '.') ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Jumlah Karyawan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int) $employee_count ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Hari Kerja</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= (int) $workdays ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                Periode: <strong><?= html_escape($period_start) ?> s/d <?= html_escape($period_end) ?></strong>
                                <span class="mx-2">|</span>
                                Hari kerja: <strong><?= (int) $workdays ?></strong>
                                <span class="mx-2">|</span>
                                Hari libur: <strong><?= (int) $holiday_count ?></strong>
                            </p>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="monthlyAttendanceTable">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Hadir</th>
                                            <th>Terlambat (&gt;5 menit)</th>
                                            <th>Total Denda</th>
                                            <th>Total Tidak Hadir</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $row): ?>
                                            <tr class="clickable-row" data-href="<?= site_url('admin/attendance-monthly/detail/' . (int) $row['employee_id'] . '?month=' . rawurlencode($month)) ?>">
                                                <td><?= html_escape($row['employee_code']) ?></td>
                                                <td><a href="<?= site_url('admin/attendance-monthly/detail/' . (int) $row['employee_id'] . '?month=' . rawurlencode($month)) ?>"><?= html_escape($row['name']) ?></a></td>
                                                <td><?= (int) $row['hadir'] ?> / <?= (int) $row['workdays'] ?></td>
                                                <td><?= (int) $row['terlambat_lebih_5'] ?></td>
                                                <td>Rp <?= number_format($row['total_denda'], 0, ',', '.') ?></td>
                                                <td><?= (int) $row['tidak_hadir'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
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
    <script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
    <script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js') ?>"></script>
    <script>
        $('#monthlyAttendanceTable').DataTable({
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                zeroRecords: 'Data tidak ditemukan',
                paginate: {
                    next: 'Berikutnya',
                    previous: 'Sebelumnya'
                }
            }
        });

        $('#monthlyAttendanceTable tbody').on('click', '.clickable-row', function(event) {
            if (!$(event.target).closest('a').length) {
                window.location.href = $(this).data('href');
            }
        });
    </script>
</body>

</html>