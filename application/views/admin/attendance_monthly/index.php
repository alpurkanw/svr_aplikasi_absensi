<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html_escape($title) ?></title>
    <link href="<?= base_url('assets/adminsb/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">
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
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <p class="text-muted mb-3">Hari kerja bulan ini: <strong><?= (int) $workdays ?></strong></p>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="monthlyAttendanceTable">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Hadir</th>
                                            <th>Terlambat</th>
                                            <th>Menit Terlambat</th>
                                            <th>Pulang Cepat</th>
                                            <th>Tidak Hadir</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $row): ?>
                                            <tr>
                                                <td><?= html_escape($row['employee_code']) ?></td>
                                                <td><?= html_escape($row['name']) ?></td>
                                                <td><?= (int) $row['hadir'] ?> / <?= (int) $row['workdays'] ?></td>
                                                <td><?= (int) $row['terlambat'] ?></td>
                                                <td><?= (int) $row['total_menit_terlambat'] ?> menit</td>
                                                <td><?= (int) $row['pulang_cepat'] ?></td>
                                                <td><?= (int) $row['tidak_hadir'] ?></td>
                                                <td>
                                                    <?php $status_class = $row['tidak_hadir'] > 0 ? 'danger' : ($row['terlambat'] > 0 ? 'warning' : 'success'); ?>
                                                    <span class="badge badge-<?= $status_class ?>"><?= $row['tidak_hadir'] > 0 ? 'Perlu Review' : ($row['terlambat'] > 0 ? 'Terlambat' : 'Baik') ?></span>
                                                </td>
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
    </script>
</body>

</html>