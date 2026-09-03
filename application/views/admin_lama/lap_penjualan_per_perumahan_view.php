<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Laporan Penjualan Per Perumahan</title>

    <link href="<?= base_url('assets/adminsb/'); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/'); ?>css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/'); ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php $this->load->view('admin/01_sidebar'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php $this->load->view('admin/02_topbar'); ?>
                <div class="container-fluid p-2">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">LAPORAN PENJUALAN PER PERUMAHAN</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="mb-4">
                                <span class="mr-4"><strong>Tanggal Penarikan Data:</strong> <?= htmlspecialchars($report_date); ?></span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="lap_penjualan_per_perumahan_table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="width: 5%;">No.</th>
                                            <th>Nama Perumahan</th>
                                            <th class="text-right">Total Penjualan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($list_perumahan)) : ?>
                                            <?php $no = 1; ?>
                                            <?php foreach ($list_perumahan as $row) : ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= htmlspecialchars($row->nama_perum); ?></td>
                                                    <td class="text-right">Rp <?= number_format($row->total_penjualan); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-secondary font-weight-bold">
                                                <td colspan="2" class="text-right">TOTAL</td>
                                                <td class="text-right">Rp <?= number_format($grand_total); ?></td>
                                            </tr>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Tidak ada data penjualan per perumahan.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <script src="<?= base_url('assets/adminsb/'); ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/adminsb/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/adminsb/'); ?>vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= base_url('assets/adminsb/'); ?>js/sb-admin-2.min.js"></script>
    <script src="<?= base_url('assets/adminsb/'); ?>vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('assets/adminsb/'); ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#lap_penjualan_per_perumahan_table').DataTable({
                ordering: false,
                searching: false,
                paging: false,
                info: false,
                lengthChange: false
            });
        });
    </script>
</body>

</html>