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
    <?php
    $rupiah = function ($nominal) {
        return 'Rp ' . number_format((float) $nominal, 0, ',', '.');
    };
    $formatTanggal = function ($tanggal) {
        return preg_match('/^\d{8}$/', (string) $tanggal) ? substr($tanggal, 6, 2) . '/' . substr($tanggal, 4, 2) . '/' . substr($tanggal, 0, 4) : '-';
    };
    ?>
    <div id="wrapper">
        <?php $this->load->view('admin/01_sidebar'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php $this->load->view('admin/02_topbar'); ?>
                <div class="container-fluid p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                        <a href="<?= base_url('admin/Cdashboard/per_perumahan_view?id_perumahan=' . (int) $id_perum); ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i>Kembali ke Dashboard</a>
                        <button type="button" class="btn btn-primary btn-sm" onclick="window.print();"><i class="fas fa-print mr-1"></i>PRINT</button>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header bg-white">
                            <h1 class="report-title font-weight-bold text-center mb-4">MONITORING SELURUH UNIT RUMAH</h1>
                            <dl class="row report-info mb-0">
                                <dt class="col-sm-3">Nama Perumahan</dt>
                                <dd class="col-sm-9">: <?= htmlspecialchars($perum->nama); ?></dd>
                                <dt class="col-sm-3">Per Tanggal</dt>
                                <dd class="col-sm-9">: <?= htmlspecialchars($report_date); ?></dd>
                            </dl>
                        </div>
                        <div class="card-body pt-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="monitoringTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">No.</th>
                                            <th>Rumah</th>
                                            <th>Status</th>
                                            <th>Pembeli / Tgl Jual</th>
                                            <th class="text-right">Harga Jual</th>
                                            <th class="text-right">Tagihan</th>
                                            <th class="text-right">Terbayar</th>
                                            <th class="text-right">Sisa Piutang</th>
                                            <th class="text-right">Biaya Rumah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($status_rumah)) : ?>
                                            <?php foreach ($status_rumah as $index => $item) : ?>
                                                <?php $sisa = max(0, $item->tagihan - $item->terbayar); ?>
                                                <?php if (!$item->nama_cust) {
                                                    $status = '<span class="badge badge-secondary">Belum Terjual</span>';
                                                } elseif ($sisa > 0) {
                                                    $status = '<span class="badge badge-warning">Terjual - Piutang</span>';
                                                } else {
                                                    $status = '<span class="badge badge-success">Terjual - Lunas</span>';
                                                } ?>
                                                <tr>
                                                    <td class="text-center"><?= $index + 1; ?></td>
                                                    <td><?= htmlspecialchars($item->norumah); ?><small class="d-block text-muted"><?= htmlspecialchars($item->mtd_jual ?: '-'); ?></small></td>
                                                    <td><?= $status; ?></td>
                                                    <td><?= $item->nama_cust ? htmlspecialchars($item->nama_cust) . '<small class="d-block text-muted">' . $formatTanggal($item->tanggal_jual) . '</small>' : '-'; ?></td>
                                                    <td class="text-right"><?= $rupiah($item->harga_jual); ?></td>
                                                    <td class="text-right"><?= $rupiah($item->tagihan); ?></td>
                                                    <td class="text-right"><?= $rupiah($item->terbayar); ?></td>
                                                    <td class="text-right <?= $sisa > 0 ? 'text-danger font-weight-bold' : ''; ?>"><?= $rupiah($sisa); ?></td>
                                                    <td class="text-right"><?= $rupiah($item->biaya_rumah); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-3">Belum ada data rumah.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
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
    <script>
        // Full table rendered without DataTables to display all unit data in one page.
    </script>
</body>

</html>