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



                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">FORM TAMBAH UNIT RUMAH</h6>
                        </div>
                        <div class="card-body p-4">
                            <?= $this->session->flashdata('pesan'); ?>
                            <form action="<?= base_url('admin/Crumah/tambah_proses'); ?>" method="post">

                                <div class="form-group">
                                    <label for="nama_perumahan">Nama Perumahan</label>
                                    <select class="form-control" id="nama_perumahan" name="id_perumahan" required>
                                        <option value="">Pilih Perumahan</option>
                                        <?php if (!empty($list_perum)) : ?>
                                            <?php foreach ($list_perum as $perum) : ?>
                                                <option value="<?= $perum->id; ?>"><?= $perum->nama; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="no_rumah">No. Rumah</label>
                                    <input type="text" class="form-control" id="no_rumah" name="no_rumah" placeholder="Masukkan Nomor Rumah" required>
                                </div>

                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" placeholder="Contoh: Rumah tipe 36, 2 kamar tidur, car port luas"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="harga_jual">Harga Jual</label>
                                    <input type="text" class="form-control" id="harga_jual" name="harga_jual" placeholder="Masukkan harga jual" required>
                                </div>

                                <!-- <div class="form-group">
                                    <label for="status_penjualan">Status Penjualan</label>
                                    <select class="form-control" id="status_penjualan" name="status">
                                        <option value="1">Belum Terjual</option>
                                        <option value="2">Dalam Proses</option>
                                        <option value="3">Terjual</option>
                                    </select>
                                </div> -->

                                <button type="submit" class="btn btn-primary">Simpan Data</button>
                                <a href="<?= base_url('admin/Crumah'); ?>" class="btn btn-secondary">Batal</a>
                            </form>
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
            $('#harga_jual').on('keyup blur', function() {
                let value = $(this).val();
                // 1. Bersihkan dari karakter non-angka (titik dan koma)
                value = value.replace(/[^0-9]/g, '');

                if (value) {
                    // SOLUSI: Hapus parameter '10'. Cukup gunakan Number(value).
                    // toLocaleString('id-ID') akan otomatis memformat dengan pemisah titik.
                    $(this).val(Number(value).toLocaleString('id-ID'));
                } else {
                    $(this).val('');
                }
            });

            // PENTING: Tambahkan kembali fungsi submit handler untuk menghapus titik
            // agar backend menerima angka murni (misalnya: 1000000000)
            $('form').on('submit', function() {
                let inputField = $('#harga_jual');
                let numericValue = inputField.val().replace(/\./g, '');
                inputField.val(numericValue);
            });
        });
    </script>

</body>

</html>