<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= htmlspecialchars($judul); ?></title>
    <link href="<?= base_url('assets/adminsb/'); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/'); ?>css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php $this->load->view('admin/01_sidebar'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php $this->load->view('admin/02_topbar'); ?>
                <div class="container-fluid p-2">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">LAPORAN PENGELUARAN RUMAH PER PERUMAHAN</h6>
                        </div>
                        <div class="card-body p-3">
                            <form id="formPerumDetail" action="<?= base_url('admin/Claporan/lap_out_rumah_per_perumahan_detail'); ?>" method="post">
                                <input type="hidden" name="id_perum" id="id_perum_hidden" value="">
                            </form>
                            <div class="mb-4"><strong>Tanggal Penarikan Data:</strong> <?= htmlspecialchars($report_date); ?></div>
                            <span class="text-muted">* Klik baris untuk melihat detail pengeluaran rumah perumahan.</span>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="width: 5%;">No.</th>
                                            <th>Nama Perumahan</th>
                                            <th class="text-right">Nominal Pengeluaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($list_perumahan)) : ?>
                                            <?php $no = 1;
                                            foreach ($list_perumahan as $row) : ?>
                                                <tr class="clickable-row" data-id-perum="<?= (int) $row->id; ?>" style="cursor: pointer;">
                                                    <td><?= $no++; ?></td>
                                                    <td><?= htmlspecialchars($row->nama_perum); ?></td>
                                                    <td class="text-right">Rp <?= number_format((float) $row->total_pengeluaran); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-secondary font-weight-bold">
                                                <td colspan="2" class="text-right">TOTAL</td>
                                                <td class="text-right">Rp <?= number_format($grand_total); ?></td>
                                            </tr>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">Tidak ada data pengeluaran rumah.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto"><span>Copyright &copy; Your Website 2020</span></div>
                </div>
            </footer>
        </div>
    </div>
    <script src="<?= base_url('assets/adminsb/'); ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/adminsb/'); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/adminsb/'); ?>js/sb-admin-2.min.js"></script>
    <script>
        $(function() {
            $('.clickable-row').on('click', function() {
                $('#id_perum_hidden').val($(this).data('id-perum'));
                $('#formPerumDetail').submit();
            });
        });
    </script>
</body>

</html>