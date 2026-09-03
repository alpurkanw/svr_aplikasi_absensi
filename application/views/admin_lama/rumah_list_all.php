<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $judul; ?></title>

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
                            <h6 class="m-0 font-weight-bold text-primary">LIST SEMUA RUMAH</h6>
                        </div>
                        <div class="card-body p-2">
                            <div class="row my-2">
                                <div class="col text-left">
                                    <a href="<?= base_url("admin/Crumah/tambah"); ?>" class=" d-none d-sm-inline-block btn btn-sm btn-primary  shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Tambah Rumah</a>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="list_semuarumah" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Perumahan</th>
                                            <th>No Rumah</th>
                                            <th>Harga Jual</th>
                                            <th>Keterangan</th>

                                        </tr>
                                    </thead>

                                    <tbody>

                                        <?php if (!empty($list_rumah)) :
                                            // print_r($list_rumah);
                                            $no = 1;
                                            foreach ($list_rumah as $data) :
                                                // Dapatkan ID yang benar dari data array (Gunakan alias yang benar di query Anda)
                                                $id_rumah = $data["id_rumah"]; // Ganti dengan alias yang benar dari query JOIN Anda
                                                $id_perum = $data["id_perum"]; // Ganti dengan alias yang benar dari query JOIN Anda
                                        ?>

                                                <tr class="clickable-row"
                                                    data-id-rumah="<?= $id_rumah; ?>"
                                                    data-id-perum="<?= $id_perum; ?>"
                                                    data-no-rumah="<?= $data["norumah"]; ?>"
                                                    data-toggle="modal"
                                                    data-target="#detailHargaModal"
                                                    style="cursor: pointer;">

                                                    <td><?= $no++; ?></td>
                                                    <td><?= $data["nama"]; ?></td>
                                                    <td><?= $data["norumah"]; ?></td>
                                                    <td><?= "Rp " . number_format($data["harga_jual"]); ?></td>
                                                    <td><?= $data["keterangan"]; ?></td>

                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted">Tidak ada data Rumah.</td>
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


        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <div class="modal fade" id="detailHargaModal" tabindex="-1" role="dialog" aria-labelledby="detailHargaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailHargaModalLabel">Detail Harga Unit Rumah <span id="modalNoRumah"></span></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modalContent">
                        <p class="text-center text-muted">Memuat data...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
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

            // Tangkap event saat modal akan ditampilkan
            $('#detailHargaModal').on('show.bs.modal', function(event) {

                // Ambil elemen yang memicu modal (yaitu baris <tr>)
                let button = $(event.relatedTarget);

                // Ambil data dari atribut data-*
                let id_rumah = button.data('id-rumah');
                let id_perum = button.data('id-perum');
                let no_rumah = button.data('no-rumah');

                // Perbarui judul modal
                $('#modalNoRumah').text(no_rumah);

                // Reset konten modal ke status "Memuat..."
                $('#modalContent').html('<p class="text-center text-muted"><i class="fas fa-sync fa-spin"></i> Memuat data list harga...</p>');

                // Panggil AJAX
                if (id_rumah && id_perum) {
                    $.ajax({
                        url: '<?= base_url("admin/Crumah/getListHarga"); ?>/' + id_perum + '/' + id_rumah,
                        type: 'GET',
                        success: function(response) {
                            // KUNCI: Isi modalContent dengan respons dari Controller
                            $('#modalContent').html(response);
                        },
                        error: function(xhr, status, error) {
                            $('#modalContent').html('<div class="alert alert-danger">Gagal memuat data: ' + status + ' - ' + error + '</div>');
                        }
                    });
                } else {
                    $('#modalContent').html('<div class="alert alert-warning">Kesalahan: ID Rumah atau ID Perumahan tidak ditemukan.</div>');
                }
            });

        });
    </script>

</body>

</html>