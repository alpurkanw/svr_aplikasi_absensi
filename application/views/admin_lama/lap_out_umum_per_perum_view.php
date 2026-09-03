<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bintang Lacita Group</title>

    <!-- Custom fonts for this template -->
    <link href="<?= base_url("assets/adminsb/"); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?= base_url("assets/adminsb/"); ?>css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Custom styles for this page -->
    <link href="<?= base_url("assets/adminsb/"); ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <?php
    $fmt_tanggal = function ($ymd) {
        if (empty($ymd)) {
            return '-';
        }
        $dt = DateTime::createFromFormat('Ymd', $ymd);
        return $dt ? $dt->format('d/m/Y') : htmlspecialchars($ymd);
    };
    ?>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php $this->load->view('admin/01_sidebar'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php $this->load->view('admin/02_topbar');                ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid p-2">

                    <!-- Page Heading
                    <h1 class="h3 mb-2 text-gray-800">Tables</h1>
                    <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below.
                        For more information about DataTables, please visit the <a target="_blank"
                            href="https://datatables.net">official DataTables documentation</a>.</p> -->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">LAPORAN PENGELUARAN UMUM PER PERUMAHAN </h6>
                            <small>Pengeluaran selain untuk rumah</small>
                        </div>
                        <div class="card-body p-2">

                            <div class="mb-3">
                                <span class="mr-4"><strong>Perumahan:</strong> <?= htmlspecialchars($nama_perumahan ?? '-'); ?></span>
                                <span class="mr-4"><strong>Tanggal Penarikan Data:</strong> <?= htmlspecialchars($report_date ?? '-'); ?></span>
                                <span class="mr-4"><strong>Periode Transaksi:</strong> <?= $fmt_tanggal($periode_awal ?? null); ?> s/d <?= $fmt_tanggal($periode_akhir ?? null); ?></span>
                            </div>

                            <small>
                                * Klik nama kategori untuk melihat detail transaksi pada periode ini.
                            </small>

                            <!-- <h6>Jenis Transaksi : Uang Keluar | Kategori :                            </h6> -->

                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="laporanPenjualanKeluar">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No.</th>
                                            <th>Kategori Pengeluaran</th>
                                            <th>Total Pengeluaran </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // print_r($list_rumah); 
                                        ?>
                                        <?php if (!empty($list_rumah)) : ?>
                                            <?php $no = 1; ?>
                                            <?php foreach ($list_rumah as $data) : ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td>
                                                        <a href="#" class="detail-umum-kategori" data-id-perum="<?= (int) ($id_perum ?? 0); ?>" data-id-kateg="<?= (int) $data->id_kateg; ?>">
                                                            <?= htmlspecialchars($data->nama_kateg); ?>
                                                        </a>
                                                    </td>
                                                    <td>Rp <?= number_format($data->total_pengeluaran); ?></td>

                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-secondary font-weight-bold">
                                                <td colspan="2" class="text-right">Total Pengeluaran</td>
                                                <td>Rp <?= number_format((float) ($grand_total ?? 0)); ?></td>
                                            </tr>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Tidak ada data Pengeluaran.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid p-2 -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url("assets/adminsb/"); ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url("assets/adminsb/"); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url("assets/adminsb/"); ?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url("assets/adminsb/"); ?>js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= base_url("assets/adminsb/"); ?>vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url("assets/adminsb/"); ?>vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="<?= base_url("assets/adminsb/"); ?>js/demo/datatables-demo.js"></script>

    <!-- Modal detail transaksi per kategori -->
    <div class="modal fade" id="detailUmumModal" tabindex="-1" role="dialog" aria-labelledby="detailUmumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailUmumModalLabel">Detail Transaksi Pengeluaran Umum</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Perumahan:</strong> <?= htmlspecialchars($nama_perumahan ?? '-'); ?></p>
                    <p><strong>Kategori:</strong> <span id="detailUmumNamaKategori"></span></p>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="detailUmumTable">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th class="text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function formatTanggalYmd(ymd) {
            if (!ymd) {
                return '-';
            }
            var text = String(ymd);
            if (text.length !== 8) {
                return text;
            }
            return text.substring(6, 8) + '/' + text.substring(4, 6) + '/' + text.substring(0, 4);
        }

        $(document).ready(function() {
            $('.detail-umum-kategori').on('click', function(e) {
                e.preventDefault();

                var idPerum = $(this).data('id-perum');
                var idKateg = $(this).data('id-kateg');
                var namaKateg = $(this).text().trim();

                $('#detailUmumNamaKategori').text(namaKateg);
                $('#detailUmumTable tbody').html('<tr><td colspan="3" class="text-center">Memuat data...</td></tr>');

                $.ajax({
                    url: '<?= base_url('admin/Claporan/lap_out_umum_per_perum_detail'); ?>',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id_perum: idPerum,
                        id_kateg: idKateg
                    },
                    success: function(response) {
                        var html = '';
                        if (response.status === 'success' && response.data.length) {
                            response.data.forEach(function(row) {
                                html += '<tr>' +
                                    '<td>' + formatTanggalYmd(row.tanggal) + '</td>' +
                                    '<td>' + (row.keterangan ? $('<div>').text(row.keterangan).html() : '-') + '</td>' +
                                    '<td class="text-right">Rp ' + Number(row.nominal).toLocaleString('id-ID') + '</td>' +
                                    '</tr>';
                            });

                            html += '<tr class="table-info font-weight-bold">' +
                                '<td colspan="2" class="text-right">Total</td>' +
                                '<td class="text-right">Rp ' + Number(response.total).toLocaleString('id-ID') + '</td>' +
                                '</tr>';
                        } else {
                            html = '<tr><td colspan="3" class="text-center text-muted">Tidak ada transaksi untuk kategori ini.</td></tr>';
                        }

                        $('#detailUmumTable tbody').html(html);
                    },
                    error: function() {
                        $('#detailUmumTable tbody').html('<tr><td colspan="3" class="text-center text-danger">Terjadi kesalahan saat memuat detail transaksi.</td></tr>');
                    }
                });

                $('#detailUmumModal').modal('show');
            });
        });
    </script>

</body>

</html>