<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Laporan Pengeluaran Detail Per Rumah</title>

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
                            <h6 class="m-0 font-weight-bold text-primary">LAPORAN PENGELUARAN DETAIL PER RUMAH</h6>
                        </div>
                        <div class="card-body p-2">

                            <div class="mb-3">
                                <p class="mb-1"><strong>Perumahan:</strong> <?= htmlspecialchars($rumah_info->nama_perum ?? '-'); ?></p>
                                <p class="mb-1"><strong>No Rumah:</strong> <?= htmlspecialchars($rumah_info->norumah ?? '-'); ?></p>
                                <p class="mb-0"><strong>Tanggal Penarikan Laporan:</strong> <?= htmlspecialchars($report_date); ?></p>
                            </div>

                            <small>
                                * Klik pada nama kategori untuk melihat detail transaksi pengeluaran di rumah tersebut.
                            </small>

                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered" id="laporanPenjualanKeluar">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No.</th>
                                            <th>Kategori</th>
                                            <th>Total Pengeluaran </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($list_rumah)) : ?>
                                            <?php $no = 1; ?>
                                            <?php $grand_total = 0; ?>
                                            <?php foreach ($list_rumah as $data) : ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td>
                                                        <a href="#" class="detail-category" data-id-kateg="<?= $data->id_kateg; ?>" data-id-rumah="<?= $id_rumah; ?>">
                                                            <?= htmlspecialchars($data->nama_kateg); ?>
                                                        </a>
                                                    </td>
                                                    <td>Rp <?= number_format($data->total_pengeluaran); ?></td>

                                                </tr>
                                                <?php $grand_total += $data->total_pengeluaran; ?>
                                            <?php endforeach; ?>
                                            <tr class="table-secondary font-weight-bold">
                                                <td colspan="2" class="text-right">Total Pengeluaran</td>
                                                <td>Rp <?= number_format($grand_total); ?></td>
                                            </tr>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Tidak ada data Pengeluaran untuk periode ini.</td>
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

    <!-- Modal untuk detail transaksi kategori -->
    <div class="modal fade" id="detailKategoriModal" tabindex="-1" role="dialog" aria-labelledby="detailKategoriLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailKategoriLabel">Detail Transaksi Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Kategori:</strong> <span id="detailKategoriNama"></span></p>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="detailKategoriTable">
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
        $(document).ready(function() {
            $('#detailKategoriTable tbody').empty();

            $('.detail-category').on('click', function(e) {
                e.preventDefault();
                var idKateg = $(this).data('id-kateg');
                var idRumah = $(this).data('id-rumah');
                var namaKateg = $(this).text().trim();

                $('#detailKategoriNama').text(namaKateg);
                $('#detailKategoriTable tbody').html('<tr><td colspan="3" class="text-center">Memuat data...</td></tr>');

                $.ajax({
                    url: '<?= base_url('admin/Claporan/lap_out_total_perumah_detail'); ?>',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id_rumah: idRumah,
                        id_kateg: idKateg
                    },
                    success: function(response) {
                        var html = '';
                        if (response.status === 'success' && response.data.length) {
                            response.data.forEach(function(row) {
                                html += '<tr>' +
                                    '<td>' + row.tanggal + '</td>' +
                                    '<td>' + (row.keterangan ? $('<div>').text(row.keterangan).html() : '-') + '</td>' +
                                    '<td class="text-right">Rp ' + Number(row.nominal).toLocaleString('id-ID') + '</td>' +
                                    '</tr>';
                            });
                            // Tambahkan baris total
                            html += '<tr class="table-info font-weight-bold">' +
                                '<td colspan="2" class="text-right">Total:</td>' +
                                '<td class="text-right">Rp ' + Number(response.total).toLocaleString('id-ID') + '</td>' +
                                '</tr>';
                        } else {
                            html = '<tr><td colspan="3" class="text-center text-muted">Tidak ada transaksi.</td></tr>';
                        }
                        $('#detailKategoriTable tbody').html(html);
                    },
                    error: function() {
                        $('#detailKategoriTable tbody').html('<tr><td colspan="3" class="text-center text-danger">Terjadi kesalahan saat memuat data.</td></tr>');
                    }
                });

                $('#detailKategoriModal').modal('show');
            });
        });
    </script>
</body>

</html>