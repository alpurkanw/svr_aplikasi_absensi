<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bintang Lacita Group</title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon_04.png') ?>">

    <link href="<?= base_url("assets/adminsb/"); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?= base_url("assets/adminsb/"); ?>css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .laporan-box {
            border: 2px solid #000;
            background: #fff;
            font-size: 15px;
        }

        .laporan-box table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .laporan-box th,
        .laporan-box td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
            text-align: left;
        }

        .laporan-box .header-title {
            text-align: center;
            font-weight: 700;
            font-size: 22px;
            padding: 8px 0;
            letter-spacing: 1px;
        }

        .laporan-box .section-title {
            font-weight: 700;
            background: #f3f3f3;
            text-align: center;
        }

        .laporan-box .text-right {
            text-align: right;
        }

        .laporan-box .grand-total {
            font-weight: 700;
        }

        .laporan-box .saldo-row {
            font-weight: 700;
            background: #f7f7f7;
        }

        .print-report {
            display: none;
        }

        @media print {
            body {
                background: #fff !important;
                color: #000 !important;
            }

            #wrapper,
            .no-print {
                display: none !important;
            }

            .print-report {
                display: block !important;
            }

            .laporan-box {
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php $this->load->view('admin/01_sidebar'); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php $this->load->view('admin/02_topbar'); ?>

                <div class="container-fluid p-2">
                    <div class="row">
                        <div class="col">
                            <h5>LAPORAN LABA RUGI</h5>
                        </div>
                    </div>
                    <hr>

                    <div class="d-flex justify-content-end mb-3 no-print">
                        <a href="<?= base_url('admin/Claporan/lap_laba_rugi_form'); ?>" class="btn btn-secondary btn-sm mr-2">Kembali</a>
                        <button onclick="window.print();" class="btn btn-primary btn-sm">Print</button>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Perumahan</div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($nama_perumahan); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Per Tanggal</div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($report_date); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Rumah</div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800"><?= number_format($total_rumah, 0, ',', '.'); ?> Unit</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pendapatan</div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pengeluaran</div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_biaya, 0, ',', '.'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Laba/Rugi</div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($saldo, 0, ',', '.'); ?> <?= $saldo >= 0 ? '(Untung)' : '(Rugi)'; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 50%;" class="text-center">DESKRIPSI</th>
                                            <th style="width: 25%;" class="text-center">PENDAPATAN</th>
                                            <th style="width: 25%;" class="text-center">PENGELUARAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $show_income_header = true;
                                        $show_expense_header = false;
                                        ?>
                                        <?php if (!empty($rows)) : ?>
                                            <?php foreach ($rows as $row) : ?>
                                                <?php if ($row['pendapatan'] > 0 && $show_income_header) : ?>
                                                    <tr>
                                                        <td colspan="3" class="font-weight-bold text-center bg-light">PENDAPATAN</td>
                                                    </tr>
                                                    <?php $show_income_header = false; ?>
                                                <?php endif; ?>

                                                <?php if ($row['biaya'] > 0 && !$show_expense_header) : ?>
                                                    <tr>
                                                        <td colspan="3" class="font-weight-bold text-center bg-light">PENGELUARAN</td>
                                                    </tr>
                                                    <?php $show_expense_header = true; ?>
                                                <?php endif; ?>

                                                <tr>
                                                    <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                                                    <td class="text-right"><?= $row['pendapatan'] > 0 ? 'Rp ' . number_format($row['pendapatan'], 0, ',', '.') : ''; ?></td>
                                                    <td class="text-right"><?= $row['biaya'] > 0 ? 'Rp ' . number_format($row['biaya'], 0, ',', '.') : ''; ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>

                                        <tr class="table-secondary font-weight-bold">
                                            <td class="text-right">TOTAL</td>
                                            <td class="text-right">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></td>
                                            <td class="text-right">Rp <?= number_format($total_biaya, 0, ',', '.'); ?></td>
                                        </tr>
                                        <tr class="table-light font-weight-bold">
                                            <td colspan="2" class="text-right">SALDO</td>
                                            <td class="text-right">Rp <?= number_format($saldo, 0, ',', '.'); ?> <?= $saldo >= 0 ? '(Untung)' : '(Rugi)'; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="print-report">
        <div class="laporan-box">
            <div class="header-title">LABA / RUGI</div>

            <table>
                <tr>
                    <td style="width: 50%;"><strong>Perumahan:</strong> <?= htmlspecialchars($nama_perumahan); ?></td>
                    <td style="width: 25%;"><strong>Per tanggal:</strong> <?= htmlspecialchars($report_date); ?></td>
                    <td style="width: 25%;"><strong>Total Rumah:</strong> <?= number_format($total_rumah, 0, ',', '.'); ?> Unit</td>
                </tr>
            </table>

            <table>
                <thead>
                    <tr>
                        <th style="width: 50%;" class="section-title">DESKRIPSI</th>
                        <th style="width: 25%;" class="section-title">PENDAPATAN</th>
                        <th style="width: 25%;" class="section-title">PENGELUARAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $show_income_header_print = true;
                    $show_expense_header_print = false;
                    ?>
                    <?php if (!empty($rows)) : ?>
                        <?php foreach ($rows as $row) : ?>
                            <?php if ($row['pendapatan'] > 0 && $show_income_header_print) : ?>
                                <tr>
                                    <td colspan="3" class="section-title">PENDAPATAN</td>
                                </tr>
                                <?php $show_income_header_print = false; ?>
                            <?php endif; ?>

                            <?php if ($row['biaya'] > 0 && !$show_expense_header_print) : ?>
                                <tr>
                                    <td colspan="3" class="section-title">PENGELUARAN</td>
                                </tr>
                                <?php $show_expense_header_print = true; ?>
                            <?php endif; ?>

                            <tr>
                                <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                                <td class="text-right"><?= $row['pendapatan'] > 0 ? 'Rp ' . number_format($row['pendapatan'], 0, ',', '.') : ''; ?></td>
                                <td class="text-right"><?= $row['biaya'] > 0 ? 'Rp ' . number_format($row['biaya'], 0, ',', '.') : ''; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <tr class="grand-total">
                        <td class="text-right">TOTAL</td>
                        <td class="text-right">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></td>
                        <td class="text-right">Rp <?= number_format($total_biaya, 0, ',', '.'); ?></td>
                    </tr>
                    <tr class="saldo-row">
                        <td colspan="2" class="text-right">SALDO:</td>
                        <td class="text-right">Rp <?= number_format($saldo, 0, ',', '.'); ?> <?= $saldo >= 0 ? '(Untung)' : '(Rugi)'; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>