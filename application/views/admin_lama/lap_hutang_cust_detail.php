<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Detail Hutang Customer</title>

    <!-- Custom fonts for this template -->
    <link href="<?= base_url("assets/adminsb/"); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

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

                <?php $this->load->view('admin/02_topbar'); ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid p-2">
                    <?php
                    $harga_detail = isset($harga_detail) ? $harga_detail : null;
                    $judul = isset($judul) ? $judul : 'Detail Hutang Customer';
                    $pembeli_info = isset($pembeli_info) ? $pembeli_info : (object)[
                        'nama_perum' => '-',
                        'norumah' => '-',
                        'nama_cust' => '-',
                        'notelp' => '-',
                        'alamat' => '-'
                    ];
                    $rumah_info = isset($rumah_info) ? $rumah_info : null;
                    $list_harga = isset($list_harga) ? $list_harga : [];
                    $list_transaksi = isset($list_transaksi) ? $list_transaksi : [];
                    $total_nominal = isset($total_nominal) ? $total_nominal : 0;
                    $total_terbayar = isset($total_terbayar) ? $total_terbayar : 0;
                    $total_sisa = isset($total_sisa) ? $total_sisa : 0;
                    $total_pembayaran = isset($total_pembayaran) ? $total_pembayaran : 0;
                    ?>

                    <!-- Action Buttons -->
                    <div class="row mb-3">
                        <div class="col d-flex gap-2">
                            <a href="<?= base_url(isset($harga_detail->id_perum) ? 'admin/claporan/lap_hutang_cust_view?id_perumahan=' . $harga_detail->id_perum : 'admin/claporan/lap_hutang_cust_form'); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="button" id="btnPrint" class="btn btn-primary">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="row mb-3">
                        <div class="col">
                            <h4><?= htmlspecialchars(isset($judul) ? $judul : 'Detail Hutang Customer'); ?></h4>
                            <hr>
                        </div>
                    </div>

                    <!-- Detail Rumah & Pembeli -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card shadow">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="m-0">Informasi Rumah</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Perumahan</strong></td>
                                            <td><?= htmlspecialchars(isset($pembeli_info->nama_perum) ? $pembeli_info->nama_perum : '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>No. Rumah</strong></td>
                                            <td><?= htmlspecialchars(isset($pembeli_info->norumah) ? $pembeli_info->norumah : '-'); ?></td>
                                        </tr>
                                        <?php if ($rumah_info): ?>
                                            <tr>
                                                <td><strong>Luas Tanah</strong></td>
                                                <td><?= isset($rumah_info->luas_tanah) ? htmlspecialchars($rumah_info->luas_tanah) : '-'; ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Luas Bangunan</strong></td>
                                                <td><?= isset($rumah_info->luas_bangunan) ? htmlspecialchars($rumah_info->luas_bangunan) : '-'; ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card shadow">
                                <div class="card-header bg-info text-white">
                                    <h6 class="m-0">Informasi Pembeli</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>Nama</strong></td>
                                            <td><?= htmlspecialchars(isset($pembeli_info->nama_cust) ? $pembeli_info->nama_cust : '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>No. Telepon</strong></td>
                                            <td><?= htmlspecialchars(isset($pembeli_info->notelp) ? $pembeli_info->notelp : '-'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Alamat</strong></td>
                                            <td><?= htmlspecialchars(isset($pembeli_info->alamat) ? $pembeli_info->alamat : '-'); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Harga & Summary -->
                    <div class="row mb-4">
                        <div class="col">
                            <div class="card shadow">
                                <div class="card-header bg-success text-white">
                                    <h6 class="m-0">Detail Semua Harga Customer</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered table-striped">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Jenis Harga</th>
                                                    <th class="text-right">Nominal</th>
                                                    <th class="text-right">Terbayar</th>
                                                    <th class="text-right">Sisa</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($list_harga)): ?>
                                                    <?php $no_harga = 1; ?>
                                                    <?php foreach ($list_harga as $harga): ?>
                                                        <?php
                                                        $harga_nominal = isset($harga->nominal) ? $harga->nominal : 0;
                                                        $harga_nom_terbayar = isset($harga->nom_terbayar) ? $harga->nom_terbayar : 0;
                                                        $sisa_harga = $harga_nominal - $harga_nom_terbayar;
                                                        ?>
                                                        <tr>
                                                            <td><?= $no_harga++; ?></td>
                                                            <td><?= htmlspecialchars($harga->nama_harga); ?></td>
                                                            <td class="text-right">Rp <?= number_format($harga_nominal); ?></td>
                                                            <td class="text-right">Rp <?= number_format($harga_nom_terbayar); ?></td>
                                                            <td class="text-right">Rp <?= number_format($sisa_harga); ?></td>
                                                            <td>
                                                                <?php if ($sisa_harga == 0): ?>
                                                                    <span class="badge badge-success">LUNAS</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-danger">BELUM LUNAS</span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">Tidak ada data harga untuk customer ini.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <?php if (!empty($list_harga)): ?>
                                                <tfoot class="table-secondary font-weight-bold">
                                                    <tr>
                                                        <td colspan="2" class="text-right">TOTAL</td>
                                                        <td class="text-right">Rp <?= number_format(isset($total_nominal) ? $total_nominal : 0); ?></td>
                                                        <td class="text-right">Rp <?= number_format(isset($total_terbayar) ? $total_terbayar : 0); ?></td>
                                                        <td class="text-right">Rp <?= number_format(isset($total_sisa) ? $total_sisa : 0); ?></td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Transaksi Pembayaran -->
                    <div class="row">
                        <div class="col">
                            <div class="card shadow">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="m-0">Riwayat Pembayaran per Harga</h6>
                                </div>
                                <div class="card-body p-2">
                                    <?php if (!empty($list_harga)): ?>
                                        <?php foreach ($list_harga as $harga): ?>
                                            <?php
                                            $kategori = isset($harga->nama_harga) ? $harga->nama_harga : 'Tidak Diketahui';
                                            $kategoriId = isset($harga->id_jns) ? $harga->id_jns : 0;
                                            $nominal = isset($harga->nominal) ? $harga->nominal : 0;
                                            $terbayar = isset($harga->nom_terbayar) ? $harga->nom_terbayar : 0;
                                            $sisa = $nominal - $terbayar;
                                            $transaksi_kategori = isset($transaksi_by_kategori[$kategoriId]) ? $transaksi_by_kategori[$kategoriId] : [];
                                            ?>
                                            <div class="mb-4">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div>
                                                        <h6 class="mb-1"><?= htmlspecialchars($kategori); ?></h6>
                                                        <small class="text-muted">Nominal: Rp <?= number_format($nominal); ?>, Terbayar: Rp <?= number_format($terbayar); ?>, Sisa: Rp <?= number_format($sisa); ?></small>
                                                    </div>
                                                    <span class="badge badge-<?= $sisa == 0 ? 'success' : 'danger'; ?>"><?= $sisa == 0 ? 'LUNAS' : 'BELUM LUNAS'; ?></span>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-bordered table-striped">
                                                        <thead class="table-secondary">
                                                            <tr>
                                                                <th style="width: 5%;">No.</th>
                                                                <th style="width: 20%;">Tanggal</th>
                                                                <th>Keterangan</th>
                                                                <th class="text-right">Nominal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (!empty($transaksi_kategori)): ?>
                                                                <?php $no_trx = 1; ?>
                                                                <?php $total_kategori = 0; ?>
                                                                <?php foreach ($transaksi_kategori as $trx): ?>
                                                                    <?php $total_kategori += $trx->nominal; ?>
                                                                    <tr>
                                                                        <td><?= $no_trx++; ?></td>
                                                                        <td><?= date('d/m/Y', strtotime($trx->tanggal)); ?></td>
                                                                        <td><?= htmlspecialchars($trx->keterangan); ?></td>
                                                                        <td class="text-right">Rp <?= number_format($trx->nominal); ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            <?php else: ?>
                                                                <tr>
                                                                    <td colspan="4" class="text-center text-muted">Belum ada pembayaran untuk kategori ini.</td>
                                                                </tr>
                                                                <?php $total_kategori = 0; ?>
                                                            <?php endif; ?>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="font-weight-bold bg-light">
                                                                <td colspan="3" class="text-right">Total per kategori</td>
                                                                <td class="text-right">Rp <?= number_format($total_kategori); ?></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-center text-muted">Tidak ada data harga untuk customer ini.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid p-2 -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white mt-4">
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

    <script>
        $(document).ready(function() {
            // Initialize DataTable untuk transaksi
            $('#tabelTransaksi').DataTable({
                "pageLength": 10,
                "ordering": false,
                "info": false
            });

            $('#btnPrint').on('click', function() {
                window.print();
            });
        });
    </script>

</body>

</html>