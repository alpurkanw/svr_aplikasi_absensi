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
                        <div class="card-header px-2 py-3">
                            <h6 class="m-0 font-weight-bold text-primary">LAPORAN STATUS PENJUALAN RUMAH </h6>
                        </div>
                        <div class="card-body p-2">
                            <?php
                            $terjual = 0;
                            foreach ($list_rumah as $data) : ($data->nama_cust) ? $terjual++ : $terjual;
                            endforeach; ?>
                            <!-- <h6>Jenis Transaksi : Uang Keluar | Kategori :                            </h6> -->
                            <h5>
                                <span class="badge badge-primary">Nama Perumahan : <?= $list_rumah[0]->nama_perum; ?></span> <span class="badge badge-primary">Total Perumahan : <?= count($list_rumah); ?> Unit</span> <span class="badge badge-primary">Terjual : <?= $terjual; ?> Unit</span>
                            </h5>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-striped" role="grid" id="laporanPenjualanKeluar">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th>Perumahan</th>
                                            <th>No RUmah </th>
                                            <th>Harga Jual </th>
                                            <th>Pemilik</th>
                                            <th>Status</th>
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
                                                    <td><?= htmlspecialchars($data->nama_perum); ?></td>
                                                    <td><?= htmlspecialchars($data->norumah); ?></td>
                                                    <td>Rp <?= number_format($data->harga_jual); ?></td>
                                                    <td><?= ($data->nama_cust) ? $data->nama_cust : ''; ?> </td>
                                                    <td><?= ($data->nama_cust) ? '<h1-6><span class="badge badge-primary">Sudah Terjual</span></h1-6>' : '<h1-6><span class="badge badge-warning">Belum Terjual</span></h1-6>'; ?> </td>
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



            $(".btn_print").click(function() {

                var classToCopy = $(".printed_area").html()

                // Membuka jendela baru dan menambahkan elemen dengan class yang disalin
                var newWindow = window.open('', '_blank');
                newWindow.document.write(`<!DOCTYPE html>
                                            <html>

                                            <head>

                                                <meta charset="utf-8">
                                                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                                                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                                                <meta name="description" content="">
                                                <meta name="author" content="">

                                                <title><?= $judul; ?> ?></title>

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
                                            <body class="hold-transition sidebar-mini">
                                            <!-- Site wrapper -->
                                            <div class="wrapper">
                                            
                                            `);
                newWindow.document.write(classToCopy);


                newWindow.document.write(`<!DOCTYPE html>
                                            <html>

                                            <head>

                                                <meta charset="utf-8">
                                                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                                                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                                                <meta name="description" content="">
                                                <meta name="author" content="">

                                                <title><?= $judul; ?> ?></title>

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
                                            <body class="hold-transition sidebar-mini">
                                            <!-- Site wrapper -->
                                            <div class="wrapper">
                                            
                                            `);



                newWindow.document.write(` </div></body></html>`);
                newWindow.document.close();

            })



        });
    </script>

</body>

</html>