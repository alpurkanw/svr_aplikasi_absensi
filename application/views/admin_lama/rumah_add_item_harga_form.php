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
    <!-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"rel="stylesheet"> -->

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


                    <div class="row">
                        <div class="col">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h5 class="m-0 font-weight-bold text-primary">TAMBAH ITEM HARGA RUMAH</h5>
                                </div>
                                <div class="card-body p-4">
                                    <?= $this->session->flashdata('pesan'); ?>
                                    <form id="formDetailHarga" action="<?= base_url('admin/Crumah/rumah_add_item_harga_proses'); ?>" method="post">

                                        <div class="form-group">
                                            <label for="id_perumahan">Pilih Nama Peruhaman</label>
                                            <select class="form-control" id="id_perumahan" name="id_perumahan" required>
                                                <option value="">Pilih Perumahan</option>
                                                <?php if (!empty($list_perum)) : ?>
                                                    <?php foreach ($list_perum as $perum) :
                                                        $perumahan = $perum->id . "|" . $perum->nama;
                                                    ?>
                                                        ?>
                                                        <option data-id="<?= $perum->id; ?>" value="<?= $perumahan; ?>"><?= $perum->nama; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="id_jual">Pilihlah No rumah yang benar</label>
                                            <select class="form-control" id="id_jual" name="id_jual" required>
                                                <option value="">-- Pilih Rumah --</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label for="id_jenis">Pilih Jenis Harga</label>
                                            <select class="form-control" id="id_jenis" name="id_jenis" required>
                                                <option value="">Pilih Jenis Harga</option>
                                                <?php if (!empty($list_harga)) : ?>
                                                    <?php foreach ($list_harga as $harga) :
                                                        $itemHarga = $harga->id . "|" . $harga->jenis;
                                                    ?>
                                                        ?>
                                                        <option data-id="<?= $harga->id; ?>" value="<?= $itemHarga; ?>"><?= $harga->jenis; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label for="nominal">Nominal</label>
                                            <input type="text" class="form-control" id="nominal" name="nominal" placeholder="Masukan Nama Pembeli di sini" required>
                                        </div>



                                        <button type="submit" class="btn btn-primary btn_cek_data_pemb">Tambahkan Item Harga</button>
                                        <a href="#" class="btn btn-secondary">Batal</a>
                                    </form>
                                </div>
                            </div>

                        </div>
                        <div class="col form_bayar" hidden>

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

            // JQuery 1: FUNGSI AJAX UNTUK MENGISI DROPDOWN NO RUMAH
            $('#id_perumahan').on('change', function() {
                // let id_perumahan = $(this).val();

                let selectedOption = $(this).find('option:selected');

                // 2. Ambil data-id dari <option> tersebut
                let id_perumahan = selectedOption.data('id');

                // Bersihkan dropdown Nomor Rumah
                $('#norumah').html('<option value="">Memuat...</option>');

                if (id_perumahan) {
                    $.ajax({
                        // Panggil Controller baru untuk mengambil list rumah
                        url: '<?= base_url("admin/Crumah/get_rumah_terjual_by_perum"); ?>',
                        type: 'POST',
                        data: {
                            id_perum: id_perumahan
                        },
                        dataType: 'json',
                        success: function(data) {
                            let html = '<option value="">Pilih Nomor Rumah</option>';

                            if (data.length > 0) {
                                // Loop melalui data yang dikembalikan oleh Controller
                                $.each(data, function(index, item) {

                                    html += `<option value="${item.id}">${item.norumah}</option>`;

                                });
                            } else {
                                html = '<option value="">Tidak ada unit rumah yang bisa dijual</option>';
                            }

                            $('#id_jual').html(html);
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: " + status + " - " + error);
                            $('#norumah').html('<option value="">Gagal memuat data rumah</option>');
                        }
                    });
                } else {
                    $('#norumah').html('<option value="">-- Pilih Perumahan Dulu --</option>');
                }
            });



            $('#nominal').on('keyup blur', function() {
                let value = $(this).val();
                value = value.replace(/[^0-9]/g, '');

                if (value) {
                    $(this).val(Number(value).toLocaleString('id-ID'));
                } else {
                    $(this).val('');
                }
            });

            // JQuery 3: FUNGSI SUBMIT HANDLER (Untuk membersihkan nominal sebelum dikirim)
            $('#formDetailHarga').on('submit', function() {
                let inputField = $('#nominal');
                // Hapus semua titik (.) dari nilai input
                let numericValue = inputField.val().replace(/\./g, '');
                // Set nilai input ke angka murni
                inputField.val(numericValue);
                // Form akan melanjutkan proses submit setelah ini
            });


        });
    </script>

</body>

</html>