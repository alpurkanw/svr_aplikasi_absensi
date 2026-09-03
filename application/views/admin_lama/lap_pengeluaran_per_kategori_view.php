<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= htmlspecialchars($judul); ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon_04.png') ?>">
    <link href="<?= base_url('assets/adminsb/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <style>
        .report-title {
            font-size: 1.35rem;
            letter-spacing: .04rem;
        }

        .report-info dt {
            color: #5a5c69;
            font-weight: 600;
        }

        .report-info dd {
            margin-bottom: .35rem;
        }

        @media print {

            .no-print,
            .sidebar,
            .topbar,
            .scroll-to-top {
                display: none !important;
            }

            #wrapper,
            #content-wrapper,
            #content,
            .container-fluid {
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .card {
                border: 0 !important;
                box-shadow: none !important;
            }

            .card-header {
                background: #fff !important;
                border: 0 !important;
                padding: 0 0 1rem !important;
            }

            .table th,
            .table td {
                border-color: #555 !important;
            }
        }
    </style>
</head>

<body id="page-top">
    <?php $rupiah = function ($nominal) {
        return 'Rp ' . number_format((float) $nominal, 0, ',', '.');
    }; ?>
    <div id="wrapper">
        <?php $this->load->view('admin/01_sidebar'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php $this->load->view('admin/02_topbar'); ?>
                <div class="container-fluid p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                        <a href="<?= base_url('admin/Cdashboard/per_perumahan_view?id_perumahan=' . (int) $perum->id); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i>Kembali ke Dashboard</a>
                        <button type="button" class="btn btn-primary btn-sm" onclick="window.print();"><i class="fas fa-print mr-1"></i>PRINT</button>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-header bg-white">
                            <h1 class="report-title font-weight-bold text-center mb-4">LAPORAN PENGELUARAN</h1>
                            <dl class="row report-info mb-0">
                                <dt class="col-sm-3">Nama Perumahan</dt>
                                <dd class="col-sm-9">: <?= htmlspecialchars($perum->nama); ?></dd>
                                <dt class="col-sm-3">Per Tanggal</dt>
                                <dd class="col-sm-9">: <?= htmlspecialchars($report_date); ?></dd>
                            </dl>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 8%;" class="text-center">No.</th>
                                            <th>Kategori Pengeluaran</th>
                                            <th class="text-right">Nominal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pengeluaran_kategori)) : ?>
                                            <?php foreach ($pengeluaran_kategori as $index => $kategori) : ?>
                                                <tr>
                                                    <td class="text-center"><?= $index + 1; ?></td>
                                                    <td><?= htmlspecialchars($kategori->nama_kateg); ?></td>
                                                    <td class="text-right"><?= $rupiah($kategori->nominal); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">Belum ada data pengeluaran.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold table-secondary">
                                            <td colspan="2" class="text-right">TOTAL NOMINAL</td>
                                            <td class="text-right"><?= $rupiah($total_nominal); ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('assets/adminsb/vendor/jquery/jquery.min.js'); ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>
    <script src="<?= base_url('assets/adminsb/js/sb-admin-2.min.js'); ?>"></script>
</body>

</html>