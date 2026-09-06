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
                        <div>
                            <h1 class="h3 text-gray-800 mb-1">Log Absensi</h1>
                            <small class="text-muted">Data presensi tersimpan dari perangkat fingerprint</small>
                        </div>
                        <form method="get" action="<?= site_url('admin/presensi') ?>" class="form-inline">
                            <label for="log-date" class="mr-2">Tanggal</label>
                            <input id="log-date" type="date" name="date" value="<?= html_escape($date) ?>" min="<?= html_escape($minimum_date) ?>" max="<?= html_escape($maximum_date) ?>" class="form-control mr-2" required>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-search mr-1"></i>Tampilkan</button>
                        </form>
                    </div>

                    <?php if ($message): ?><div class="alert alert-warning"><?= html_escape($message) ?></div><?php endif; ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Presensi tanggal <?= html_escape($date) ?></h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>User ID</th>
                                            <th>Nama Karyawan</th>
                                            <th>Waktu</th>
                                            <th>Serial Perangkat</th>
                                            <th>Client Event ID</th>
                                            <th>Tersimpan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!$rows): ?><tr>
                                                <td colspan="7" class="text-center text-muted">Belum ada log absensi pada tanggal ini.</td>
                                            </tr><?php endif; ?>
                                        <?php foreach ($rows as $index => $row): ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= html_escape($row['user_id']) ?></td>
                                                <td><?= $row['employee_name'] ? html_escape($row['employee_name']) : '-' ?></td>
                                                <td><?= html_escape($row['timestamp']) ?></td>
                                                <td><?= html_escape($row['device_sn']) ?></td>
                                                <td><code><?= html_escape($row['client_event_id']) ?></code></td>
                                                <td><?= html_escape($row['created_at']) ?></td>
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
    <script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js') ?>"></script>
</body>

</html>