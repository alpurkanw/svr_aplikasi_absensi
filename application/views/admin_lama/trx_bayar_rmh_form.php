<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $judul; ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon_04.png') ?>">

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


                    <div class="row">
                        <div class="col">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h5 class="m-0 font-weight-bold text-primary">CEK PEMBAYARAN</h5>
                                </div>
                                <div class="card-body p-4">
                                    <?= $this->session->flashdata('pesan'); ?>
                                    <form id="formDetailHarga" action="<?= base_url('admin/Cbayar'); ?>" method="post">

                                        <div class="form-group">
                                            <label for="id_perumahan">Nama Perumahan</label>
                                            <select class="form-control" id="id_perumahan" name="id_perumahan" required>
                                                <option value="">Pilih Perumahan</option>
                                                <?php if (!empty($list_perum)) : ?>
                                                    <?php foreach ($list_perum as $perum) : ?>
                                                        <option value="<?= $perum->id; ?>"><?= $perum->nama; ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="norumah">No Rumah</label>
                                            <select class="form-control" id="norumah" name="norumah" required>
                                                <option value="">-- Pilih Perumahan Dulu --</option>
                                            </select>
                                        </div>


                                        <div class="form-group">
                                            <label for="jns_harga">Jenis DP</label>
                                            <select class="form-control" id="jns_harga" name="jns_harga" required>
                                                <option value="">-- Mau Bayar Apa? --</option>
                                            </select>
                                        </div>



                                        <button type="button" class="btn btn-primary btn_cek_data_pemb">Check Data Pembayaran</button>
                                        <a href="#" class="btn btn-secondary">Batal</a>
                                    </form>
                                </div>
                            </div>

                        </div>
                        <div class="col form_bayar" hidden>




                            <?= $this->session->flashdata('pesan'); ?>
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h5 class="m-0 font-weight-bold text-primary">FORM PEMBAYARAN RUMAH</h5>
                                </div>
                                <div class="card-body p-4">
                                    <h5 class="mb-4 font-weight-bold text-primary">Detail Pemilik</h5>

                                    <table class="table">

                                        <tbody>
                                            <tr>
                                                <td>Nama Perumahan </td>
                                                <td class="id_perum" hidden></td>
                                                <td class="nm_perum"></td>
                                            </tr>
                                            <tr>
                                                <td>No Rumah </td>
                                                <td class="id_rumah" hidden></td>
                                                <td class="no_rumah"></td>
                                            </tr>

                                            <tr>
                                                <td>Jenis DP(Pendapatan) </td>
                                                <td class="id_jns_harga" hidden></td>
                                                <td class="jenis_harga"></td>
                                            </tr>
                                            <tr>
                                                <td>Nominal (A)</td>
                                                <td class="nominal"></td>
                                            </tr>

                                            <tr>
                                                <td>Nama Pemilik </td>
                                                <td class="id_pemilik" hidden></td>
                                                <td class="nama_cust"></td>
                                            </tr>

                                            <tr>
                                                <td>No Telp </td>
                                                <td class="notelp"></td>
                                            </tr>

                                            <tr>
                                                <td> Alamat </td>
                                                <td class="alamat"></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                    <hr><br>
                                    <h5 class="mb-4 font-weight-bold text-primary">Detail Pembayaran</h5>

                                    <div class="show_detail_bayar">

                                    </div>



                                    <h5 class="my-4 font-weight-bold text-primary">Form Pembayaran</h5>


                                    <form id="formDetailHarga" action="<?= base_url('admin/Cbayar/trx_bayar_rmh_proses'); ?>" method="post">


                                        <div class="form-group">
                                            <label for="nominal">Masukan Nominal Pembayaran </label>
                                            <input type="text" class="form-control" id="nominal" name="nominal" placeholder="Masukkan Nominal" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="Keterangan">Keterangan</label>
                                            <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Contoh: dibayarkan secara Transfer ke rekening 12345678" required></textarea>
                                        </div>

                                        <button type="button" class="btn btn-primary btn_submit_pembayaran" onclick="return confirm('Anda Akan Melakukan Pembayaran?')">Simpan Data</button>
                                        <a href="#" class="btn btn-secondary">Batal</a>
                                    </form>



                                </div>
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

            // JQuery 1: FUNGSI AJAX UNTUK MENGISI DROPDOWN NO RUMAH
            $('#id_perumahan').on('change', function() {
                let id_perumahan = $(this).val();

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
                                    // item.id adalah ID unit rumah di tm_rumah
                                    // item.no_rumah adalah nomor/nama rumah (Contoh: A1, B5)
                                    html += `<option value="${item.id_rumah}">${item.norumah}</option>`;
                                });
                            } else {
                                html = '<option value="">Belum Ada Unit Yang Terjual Di Perumahan ini </option>';
                            }

                            $('#norumah').html(html);
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

            $('#norumah').on('change', function() {
                let id_rumah = $(this).val();
                let id_perumahan = $('#id_perumahan').val(); // Ambil juga ID Perumahan untuk validasi di backend

                // Kosongkan dan beri status memuat
                $('#jns_harga').html('<option value="">Memuat...</option>');
                // alert(id_rumah + id_perumahan);
                // Pastikan kedua value sudah terpilih
                if (id_rumah && id_perumahan) {
                    $.ajax({
                        // Panggil Controller baru untuk mengambil list jenis harga/pembayaran
                        url: '<?= base_url("admin/Cbayar/get_harga_perrumah_terjual"); ?>',
                        type: 'POST',
                        data: {
                            id_rumah: id_rumah,
                            id_perum: id_perumahan // Disertakan meskipun mungkin hanya id_rumah yang dipakai
                        },
                        success: function(data) {

                            // console.log(data)
                            // return;
                            let html = '<option value="">-- Mau Bayar Apa? --</option>';
                            data_resp = JSON.parse(data);
                            // console.log(data_resp)
                            // return;


                            if (data_resp.length > 0) {


                                // Loop melalui data yang dikembalikan oleh Controller (asumsi data berisi {id, nama_harga})
                                $.each(data_resp, function(index, item) {
                                    html += `<option value="${item.id_jns}">${item.nama_harga}</option>`;
                                });
                                $('#jns_harga').html(html);

                            } else {
                                // Munculkan ALERT jika tidak ada data
                                alert("Input jenis-jenis harga terlebih dahulu!");
                                $('#jns_harga').html('<option value="">Tidak ada jenis pembayaran terdaftar</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: " + status + " - " + error);
                            $('#jns_harga').html('<option value="">Gagal memuat jenis pembayaran</option>');
                        }
                    });
                } else {
                    $('#jns_harga').html('<option value="">-- Mau Bayar Apa? --</option>');
                }
            });




            // JQuery 2: FUNGSI FORMAT ANGKA (seperti yang kita bahas sebelumnya)
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
            function bersihnom() {
                let inputField = $('#nominal');
                // Hapus semua titik (.) dari nilai input
                let numericValue = inputField.val().replace(/\./g, '');
                // Set nilai input ke angka murni
                inputField.val(numericValue);
                // Form akan melanjutkan proses submit setelah ini
            };

            $('.btn_cek_data_pemb').click(function() {
                let id_perumahan = $('#id_perumahan').val(); // Ambil juga ID Perumahan untuk validasi di backend
                let norumah = $('#norumah').val();
                let jns_harga = $('#jns_harga').val(); // Ambil juga ID Perumahan untuk validasi di backend

                // Pastikan kedua value sudah terpilih
                if (norumah && id_perumahan && jns_harga) {
                    $.ajax({
                        // Panggil Controller baru untuk mengambil list jenis harga/pembayaran
                        url: '<?= base_url("admin/Cbayar/trx_bayar_inquery"); ?>',
                        type: 'POST',
                        data: {
                            id_rumah: norumah,
                            id_perum: id_perumahan, // Disertakan meskipun mungkin hanya id_rumah yang dipakai
                            id_jenis: jns_harga
                        },
                        // dataType: 'json',/
                        success: function(data) {

                            // console.log(data)

                            // let html = 'Data Kosong';

                            dt_resp = JSON.parse(data);
                            // console.log(id_perumahan + "-" + norumah + "-" + jns_harga);
                            // // echo $id_perum . '-' . $id_rumah . '-' . $id_jenis;
                            // // console.log(dt_resp.harga_rumah[0]);
                            // // console.log(dt_resp.terbayar[0]);
                            // // console.log(dt_resp.harga_rumah[0].nama_perum);
                            // return;

                            // alert(dt_resp.harga_rumah.length);
                            // return;

                            if (dt_resp.harga_rumah.length > 0) {

                                $('.nm_perum').text(dt_resp.harga_rumah[0].nama_perum)
                                $('.id_perum').text(dt_resp.harga_rumah[0].id_perum + '|' + dt_resp.harga_rumah[0].nama_perum)
                                $('.no_rumah').text(dt_resp.harga_rumah[0].norumah)
                                $('.id_rumah').text(dt_resp.harga_rumah[0].id_rumah + '|' + dt_resp.harga_rumah[0].norumah)
                                $('.jenis_harga').text(dt_resp.harga_rumah[0].nama_harga)
                                $('.id_jns_harga').text(dt_resp.harga_rumah[0].id_jns + '|' + dt_resp.harga_rumah[0].nama_harga)
                                $('.nominal').text(dt_resp.harga_rumah[0].nominal.replace(/\B(?=(\d{3})+(?!\d))/g, ','))

                                $('.nama_cust').text(dt_resp.harga_rumah[0].nama_cust)
                                $('.notelp').text(dt_resp.harga_rumah[0].notelp)
                                $('.alamat').text(dt_resp.harga_rumah[0].alamat)

                                if (dt_resp.terbayar.length > 0) {
                                    // Loop melalui data yang dikembalikan oleh Controller (asumsi data berisi {id, nama_harga})

                                    console.log(dt_resp.terbayar);
                                    // $('.show_detail_bayar').text(JSON.stringify(dt_resp.terbayar));


                                    const dataDetailPembayaran = dt_resp.terbayar;

                                    let htmlTable = `
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Tgl Bayar</th>
                                                                <th>Keterangan</th>
                                                                <th class="text-right">Nominal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>`;

                                    // Loop data
                                    let total_terbayar = 0;
                                    $.each(dataDetailPembayaran, function(index, item) {

                                        htmlTable +=
                                            `<tr>
                                                    <td>${index + 1}</td>
                                                    <td>${item.tanggal}</td>          <td>${item.keterangan || '-'}</td>
                                                    <td class="text-right">${item.nominal.replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>  
                                                    </tr>`;
                                        total_terbayar += parseFloat(item.nominal);
                                    });

                                    htmlTable += `<tr>

                                                    <td colspan="3" class="text-right">Total (B)</td>  
                                                    <td  class="text-right">${total_terbayar.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>  
                                                    </tr>

                                                    <tr class="font-weight-bold">

                                                    <td colspan="3" class="text-right">Sisa (A-B)</td>  
                                                    <td  class="text-right nominal_sisa">${(dt_resp.harga_rumah[0].nominal - total_terbayar).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</td>  
                                                    </tr>

                                                    `;
                                    htmlTable += `
                                                    </tbody>
                                                </table>
                                            </div>`;
                                    // Masukkan tabel ke dalam elemen target
                                    $('.show_detail_bayar').html(htmlTable);


                                } else {
                                    $('.show_detail_bayar').text("Belum ada Transaksi ");
                                }

                                // Hapus atribut 'hidden'
                                const $formBayar = $('.col.form_bayar');
                                $formBayar.removeAttr('hidden');




                            } else {
                                alert("Data Tidak Ditemukan")
                            }

                            // $(".form_bayar").removeAttr('hidden');

                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: " + status + " - " + error);
                            $('.show_detail_bayar').html('Cek koneksi bos, ini masalah koneksi');
                        }
                    });
                } else {
                    alert('Silahkan Pilih mau check apa?')
                }
            });



            $('.btn_submit_pembayaran').click(function() {
                bersihnom();

                const pemisah = "|";
                let perum = $('.id_perum').text().split(pemisah);
                let rumah = $('.id_rumah').text().split(pemisah); // Ambil juga ID Perumahan untuk textidasi di backend
                let jns_harga = $('.id_jns_harga').text().split(pemisah); // Ambil juga ID Perumahan untuk validasi di backend
                let nominal = $('#nominal').val(); // Ambil juga ID Perumahan untuk validasi di backend
                let keterangan = $('#keterangan').val(); // Ambil juga ID Perumahan untuk validasi di backend

                let sisa = (isNaN(parseFloat($(".nominal_sisa").text().replace(/,/g, ''))) ? dt_resp.harga_rumah[0].nominal : parseFloat($(".nominal_sisa").text().replace(/,/g, '')));


                if (Number(nominal) > sisa) {
                    alert("Pembayaran Lebih besar dari sisa, Cek lagi nominal pembayaran")
                    $('#nominal').focus(function(e) {
                        e.preventDefault();
                    });
                    $('#nominal').val("");
                    return;
                }


                // Pastikan kedua value sudah terpilih
                if (perum[0] && rumah[0] && jns_harga[0]) {
                    $.ajax({
                        // Panggil Controller baru untuk mengambil list jenis harga/pembayaran
                        url: '<?= base_url("admin/Cbayar/trx_bayar_rmh_proses"); ?>',
                        type: 'POST',
                        data: {
                            id_perum: perum[0], // Disertakan meskipun mungkin hanya id_rumah yang dipakai
                            nama_perum: perum[1], // Disertakan meskipun mungkin hanya id_rumah yang dipakai
                            id_rumah: rumah[0],
                            norumah: rumah[1],
                            id_kateg: jns_harga[0],
                            nama_kateg: jns_harga[1],
                            nominal: nominal,
                            keterangan: keterangan
                        },
                        // dataType: 'json',/
                        success: function(data) {

                            // console.log(data);
                            alert("Transaksi Sukses!!");
                            location.reload();


                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: " + status + " - " + error);
                            $('.show_detail_bayar').html('Cek koneksi bos, ini masalah koneksi');
                        }
                    });
                } else {
                    alert('Silahkan Pilih mau check apa?')
                }
            });


        });
    </script>

</body>

</html>