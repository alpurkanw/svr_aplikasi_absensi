<!doctype html>
<html lang="id">

<?php
$period_value = (isset($period) && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $period))
    ? $period
    : date('Y-m');
?>

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
                        <h1 class="h3 text-gray-800 mb-0">Payroll</h1>
                        <a class="btn btn-outline-primary" href="<?= site_url('admin/payslips?period=' . rawurlencode($period)) ?>"><i class="fas fa-file-invoice-dollar mr-1"></i>Lihat Slip Gaji</a>
                    </div>

                    <!-- ========================================== -->
                    <!-- TARUH KODE ALERT FLASHDATA DI SINI         -->
                    <!-- ========================================== -->
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-ban"></i> Perhatian!</h4>
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php endif; ?>
                    <!-- ========================================== -->


                    <!-- <?php if ($this->session->flashdata('success')): ?><div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div><?php endif; ?> -->
                    <?php if ($this->session->flashdata('error')): ?><div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div><?php endif; ?>

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="form-row align-items-end">
                                <div class="form-group col-md-4 mb-md-0">
                                    <label for="period">Periode Payroll</label>
                                    <form method="get" action="<?= site_url('admin/payroll') ?>">
                                        <div class="input-group">
                                            <input type="month" id="period" name="period" value="<?= html_escape($period_value) ?>" class="form-control" required>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-outline-primary">Tampilkan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="form-group col-md-8 mb-md-0 text-md-right">
                                    <form method="post" action="<?= site_url('admin/payroll/process') ?>">
                                        <input type="hidden" name="period" value="<?= html_escape($period_value) ?>">
                                        <?php if ($period_row['status'] !== 'FINALIZED'): ?>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-calculator mr-1"></i>Proses Payroll</button>
                                        <?php else: ?>
                                            <span class="badge badge-success p-2"><i class="fas fa-lock mr-1"></i>Periode Terkunci</span>
                                        <?php endif; ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $status_badges = array('DRAFT' => 'secondary', 'PROCESSING' => 'warning', 'REVIEW' => 'info', 'FINALIZED' => 'success');
                    $status_badge = isset($status_badges[$period_row['status']]) ? $status_badges[$period_row['status']] : 'secondary';
                    ?>
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-left-primary shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Status Periode</div>
                                    <div class="h5 mb-0"><span class="badge badge-<?= $status_badge ?>"><?= html_escape($period_row['status']) ?></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-left-info shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Data Attendance</div>
                                    <div class="h5 mb-0"><?= (int) $attendance['attendance_rows'] ?> baris</div>
                                    <small class="text-muted"><?= (int) $attendance['employees'] ?> karyawan</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-left-warning shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Terlambat</div>
                                    <div class="h5 mb-0"><?= (int) $attendance['late_minutes'] ?> menit</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-3">
                            <div class="card border-left-success shadow-sm h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Lembur</div>
                                    <div class="h5 mb-0"><?= (int) $attendance['overtime_minutes'] ?> menit</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Review Payroll <?= html_escape($period_row['period_label']) ?></h6>
                            <?php if ($period_row['status'] === 'REVIEW'): ?>
                                <form method="post" action="<?= site_url('admin/payroll/finalize') ?>" onsubmit="return confirm('Finalisasi periode ini? Setelah final, payroll tidak dapat dihitung ulang.');">
                                    <input type="hidden" name="period" value="<?= html_escape($period) ?>">
                                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-lock mr-1"></i>Finalisasi</button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <?php
                            $total_deduction = 0;
                            $total_net_salary = 0;
                            foreach ($rows as $row) {
                                $total_deduction += (float) $row['total_deduction'];
                                $total_net_salary += (float) $row['total_earning'] - (float) $row['total_deduction'];
                            }
                            ?>
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Komponen</th>
                                            <th>Total Earning</th>
                                            <th>Total Deduction</th>
                                            <th>Net Salary</th>
                                            <th>Attendance</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!$rows): ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">Belum ada hasil payroll untuk periode ini.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($rows as $index => $row): ?>
                                                <tr>
                                                    <td><?= $index + 1 ?></td>
                                                    <td><?= html_escape($row['employee_code']) ?></td>
                                                    <td><?= html_escape($row['name']) ?></td>
                                                    <td><?= (int) $row['component_count'] ?></td>
                                                    <td class="text-right">Rp <?= number_format((float) $row['total_earning'], 0, ',', '.') ?></td>
                                                    <td class="text-right">Rp <?= number_format((float) $row['total_deduction'], 0, ',', '.') ?></td>
                                                    <td class="text-right font-weight-bold">Rp <?= number_format((float) $row['total_earning'] - (float) $row['total_deduction'], 0, ',', '.') ?></td>
                                                    <td><small><?= (int) $row['late_minutes'] ?> m terlambat<br><?= (int) $row['overtime_minutes'] ?> m lembur</small></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-outline-primary btn-sm payroll-detail" title="Lihat detail komponen" data-url="<?= html_escape(site_url('admin/payslips/detail/' . (int) $row['id'] . '?period=' . rawurlencode($period))) ?>">
                                                            <i class="fas fa-list-ul"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row justify-content-end mt-3">
                                <div class="col-md-5 col-lg-4">
                                    <div class="border-top pt-2">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Total Deduction</span>
                                            <strong>Rp <?= number_format($total_deduction, 0, ',', '.') ?></strong>
                                        </div>
                                        <div class="d-flex justify-content-between h5 mb-0">
                                            <span>Total Nominal Payroll</span>
                                            <strong class="text-primary">Rp <?= number_format($total_net_salary, 0, ',', '.') ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="payrollDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Komponen Payroll</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body" id="payrollDetailBody">
                            <div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>
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
    <script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js') ?>"></script>
    <script>
        $('.payroll-detail').on('click', function() {
            var url = $(this).data('url');
            $('#payrollDetailBody').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>');
            $('#payrollDetailModal').modal('show');
            $.get(url)
                .done(function(html) {
                    $('#payrollDetailBody').html(html);
                })
                .fail(function() {
                    $('#payrollDetailBody').html('<div class="alert alert-danger mb-0">Detail payroll tidak dapat dimuat.</div>');
                });
        });
    </script>
</body>

</html>