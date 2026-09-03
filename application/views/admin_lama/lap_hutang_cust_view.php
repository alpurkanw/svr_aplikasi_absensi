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

                    <div class="row">
                        <div class="col">
                            <h5>LAPORAN HUTANG CUSTOMER</h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Tagihan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800 tot_rumah"></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-home fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Pemdapatan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800 tot_pendapatan"></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Pembayaran</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800 tot_terbayar"></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pending Requests Card Example -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Total Sisa </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800 tot_sisa"></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <a href="#" class="btn mb-2  btn-primary btn_print " target="_blank">
                        Print</a>


                    <!-- DataTales Example -->
                    <div class="card shadow mb-4 printed_area">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">LAPORAN HUTANG CUSTOMER
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


                                <table class="table table-striped table-hover table-bordered" id="laporanPenjualanKeluar">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>No.</th>
                                            <th>No Rumah</th>
                                            <th>Kategori Harga</th>
                                            <th>Nominal</th>
                                            <th>Terbayar</th>
                                            <th>Sisa</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($list_rumah)) : ?>
                                            <?php
                                            $no = 0;
                                            // Inisialisasi variabel total
                                            $total_nominal = 0;
                                            $total_terbayar = 0;
                                            $total_sisa = 0;
                                            ?>
                                            <?php foreach ($list_rumah as $data) :
                                                $sisa = $data->nominal - $data->nom_terbayar;

                                                // Menjumlahkan nilai ke total
                                                $total_nominal += $data->nominal;
                                                $total_terbayar += $data->nom_terbayar;
                                                $total_sisa += $sisa;
                                                $no++;
                                            ?>
                                                <tr class="row-clickable" data-id="<?= $data->id; ?>">
                                                    <td><?= $no; ?></td>
                                                    <td><?= htmlspecialchars($data->norumah); ?></td>
                                                    <td><?= $data->kategori_dp; ?> </td>
                                                    <td>Rp <?= number_format($data->nominal); ?></td>
                                                    <td>Rp <?= number_format($data->nom_terbayar); ?></td>
                                                    <td>Rp <?= number_format($sisa); ?></td>
                                                    <td><?= ($sisa <> 0) ? '<span class="badge badge-danger">Belum Lunas</span>' : '<span class="badge badge-primary">LUNAS</span>'; ?> </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">Tidak ada data Pengeluaran untuk periode ini.</td>
                                            </tr>
                                        <?php $no = 0;
                                        endif; ?>
                                    </tbody>

                                    <?php if (!empty($list_rumah)) : ?>
                                        <tfoot class="table-secondary" style="font-weight: bold;">
                                            <tr>
                                                <td></td>
                                                <td colspan="2" class="text-center">TOTAL</td>
                                                <td>Rp <?= number_format($total_nominal); ?></td>
                                                <td>Rp <?= number_format($total_terbayar); ?></td>
                                                <td>Rp <?= number_format($total_sisa); ?></td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    <?php

                                    endif; ?>
                                </table>



                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid p-2 -->

            </div>
            <!-- End of Main Content -->



        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->



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

            $(".tot_rumah").text("<?= $no . ' Tagihan'; ?>");
            $(".tot_pendapatan").text("Rp " + "<?= number_format($total_nominal); ?>");
            $(".tot_terbayar").text("Rp " + "<?= number_format($total_terbayar); ?>");
            $(".tot_sisa").text("Rp " + "<?= number_format($total_sisa); ?>");

            // Handle row click untuk membuka detail
            $(document).on('click', '.row-clickable', function() {
                var id = $(this).data('id');
                window.location.href = '<?= base_url('admin/claporan/lap_hutang_cust_detail'); ?>/' + id;
            });

            // Ubah cursor menjadi pointer saat hover
            $('.row-clickable').css('cursor', 'pointer');
            $('.row-clickable').hover(
                function() {
                    $(this).css('background-color', '#f0f0f0');
                },
                function() {
                    $(this).css('background-color', '');
                }
            );
        })
    </script>


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