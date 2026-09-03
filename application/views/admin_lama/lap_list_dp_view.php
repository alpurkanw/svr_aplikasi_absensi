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

                    <a href="#" class="btn mb-2  btn-primary btn_print " target="_blank">
                        Print</a>

                    <div class="card shadow mb-4 printed_area">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">LIST PENDAPATAN(DP) PENJUALAN RUMAH </<h6 class="m-0 font-weight-bold text-primary">
                                <br><?= "PERUMAHAN : " . htmlspecialchars(
                                        (isset($list_rumah[0]) && $list_rumah[0]->nama_perum != "")
                                            ? $list_rumah[0]->nama_perum
                                            : "Tidak Ada Data Rumah Terjual"
                                    ); ?>
                            </h6>
                        </div>
                        <div class="card-body p-2">

                            <!-- <h6>Jenis Transaksi : Uang Keluar | Kategori :                            </h6> -->

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped" role="grid" id="laporanPenjualanKeluar">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th>No Rumah </th>
                                            <th>Pemilik </th>
                                            <th>Jenis Pendapatan(DP) </th>
                                            <th>Nominal</th>
                                            <!-- <th>Terbayar</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // print_r($list_rumah); 
                                        ?>
                                        <?php if (!empty($list_rumah)) : ?>
                                            <?php $no = 1; ?>
                                            <?php $grand_total = 0; ?>
                                            <?php foreach ($list_rumah as $data) : ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= htmlspecialchars($data->norumah); ?></td>
                                                    <td><?= htmlspecialchars($data->nama_cust); ?></td>
                                                    <td><?= htmlspecialchars($data->nama_harga); ?></td>
                                                    <td><?= number_format($data->nominal); ?></td>

                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">Tidak ada data Pengeluaran untuk periode ini.</td>
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

    <script>
        $(document).ready(function() {

            // Initialize DataTable for the DP list
            $('#laporanPenjualanKeluar').DataTable({
                "pageLength": 25,
                "ordering": true,
                "order": [
                    [1, "asc"]
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": 0
                }]
            });

            $(".btn_print").click(function() {

                var table = $('#laporanPenjualanKeluar').DataTable();

                // Ambil header dan semua baris (seluruh data, bukan halaman saat ini)
                var headerHtml = $('#laporanPenjualanKeluar thead').prop('outerHTML');
                var rowsNodes = table.rows().nodes();
                var bodyHtml = '';
                for (var i = 0; i < rowsNodes.length; i++) {
                    bodyHtml += rowsNodes[i].outerHTML;
                }

                var fullTable = '<table class="table table-sm table-bordered table-striped" role="grid">' + headerHtml + '<tbody>' + bodyHtml + '</tbody></table>';

                // Buka jendela baru berisi tabel lengkap tanpa elemen search/pagination
                var newWindow = window.open('', '_blank');
                newWindow.document.write(`<!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="utf-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                        <title><?= isset($judul) ? $judul : 'Print'; ?></title>
                        <link href="<?= base_url("assets/adminsb/"); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
                        <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
                        <link href="<?= base_url("assets/adminsb/"); ?>css/sb-admin-2.min.css" rel="stylesheet">
                        <link href="<?= base_url("assets/adminsb/"); ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
                        <style>body{padding:20px;} .dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate{display:none !important;}</style>
                    </head>
                    <body>
                        <h4>LIST PENDAPATAN(DP) PENJUALAN RUMAH</h4>
                        <h5>PERUMAHAN : <?= isset($list_rumah[0]) && $list_rumah[0]->nama_perum ? htmlspecialchars($list_rumah[0]->nama_perum) : 'Tidak Ada Data Rumah Terjual'; ?></h5>
                        ` + fullTable + `
                    </body>
                    </html>`);

                newWindow.document.close();
            });



        });
    </script>

</body>

</html>