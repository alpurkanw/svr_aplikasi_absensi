<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= htmlspecialchars($judul); ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon_04.png') ?>">
    <link href="<?= base_url('assets/adminsb/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/vendor/datatables/dataTables.bootstrap4.min.css'); ?>" rel="stylesheet">
    <style>
        .metric-value {
            font-size: 1.1rem
        }

        .chart-area {
            height: 19rem
        }

        .table td,
        .table th {
            vertical-align: middle
        }

        .progress {
            height: 1.1rem
        }

        .dashboard-summary-card .card-header {
            padding: .85rem 1rem;
        }

        .dashboard-summary-card .card-body {
            padding: .75rem 1rem;
        }

        .dashboard-summary-card .table {
            margin-bottom: 0;
        }

        .dashboard-summary-card .table th,
        .dashboard-summary-card .table td {
            padding: .65rem .5rem;
        }

        .dashboard-summary-card .table th:first-child,
        .dashboard-summary-card .table td:first-child {
            padding-left: 0;
        }

        .dashboard-summary-card .table th:last-child,
        .dashboard-summary-card .table td:last-child {
            padding-right: 0;
            white-space: nowrap;
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
        <?php $this->load->view('owner/01_sidebar'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php $this->load->view('owner/02_topbar'); ?>
                <div class="container-fluid p-3">
                    <div class="d-sm-flex align-items-center justify-content-between mb-3">
                        <div>
                            <h1 class="h3 mb-1 text-gray-800">Dashboard Perumahan</h1>
                            <div class="text-muted"><?= htmlspecialchars($perum->nama); ?></div>
                        </div><a href="<?= base_url('owner/Cdashboard/per_perumahan'); ?>" class="btn btn-sm btn-primary mt-2 mt-sm-0"><i class="fas fa-building mr-1"></i>Ganti Perumahan</a>
                    </div>
                    <div class="alert alert-light border py-2 mb-4"><strong>Alamat/Keterangan:</strong> <?= htmlspecialchars($perum->desk ?: '-'); ?><span class="float-sm-right">Diperbarui: <?= htmlspecialchars($report_date); ?></span></div>

                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Unit</div>
                                    <div class="metric-value font-weight-bold text-gray-800"><?= number_format($total_rumah, 0, ',', '.'); ?> Unit</div>
                                    <div class="small text-muted">Terjual <?= number_format($total_terjual, 0, ',', '.'); ?> unit</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Unit Terjual</div>
                                    <div class="metric-value font-weight-bold text-gray-800"><?= number_format($persen_terjual, 1, ',', '.'); ?>%</div>
                                    <div class="progress mt-2">
                                        <div class="progress-bar bg-success" style="width: <?= min(100, $persen_terjual); ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Belum Terjual</div>
                                    <div class="metric-value font-weight-bold text-gray-800"><?= number_format($total_belum_terjual, 0, ',', '.'); ?> Unit</div>
                                    <div class="small text-muted">Potensi <?= $rupiah($potensi_belum_terjual); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Nilai Unit Terjual</div>
                                    <div class="metric-value font-weight-bold text-gray-800"><?= $rupiah($nilai_harga_jual_terjual); ?></div>
                                    <div class="small text-muted">Berdasarkan harga jual master</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tagihan Penjualan</div>
                                    <div class="metric-value font-weight-bold text-gray-800"><?= $rupiah($total_tagihan); ?></div>
                                    <div class="small text-muted">Seluruh histori</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pembayaran Diterima</div>
                                    <div class="metric-value font-weight-bold text-gray-800"><?= $rupiah($total_pembayaran); ?></div>
                                    <div class="small text-muted">Seluruh histori</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Sisa Piutang</div>
                                    <div class="metric-value font-weight-bold text-gray-800"><?= $rupiah($total_piutang); ?></div>
                                    <div class="small text-muted">Tagihan dikurangi pembayaran</div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Penjualan per Bulan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area"><canvas id="salesChart"></canvas></div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow h-100 dashboard-summary-card">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Penjualan Terbaru</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Rumah</th>
                                                    <th>Pembeli</th>
                                                    <th>Tanggal</th>
                                                    <th class="text-right">Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody><?php if ($penjualan_terbaru) : foreach ($penjualan_terbaru as $item) : ?><tr>
                                                            <td><?= htmlspecialchars($item->norumah); ?></td>
                                                            <td><?= htmlspecialchars($item->nama_cust); ?><small class="d-block text-muted"><?= htmlspecialchars($item->mtd_jual ?: '-'); ?></small></td>
                                                            <td><?= $formatTanggal($item->tanggal); ?></td>
                                                            <td class="text-right"><?= $rupiah($item->harga_jual); ?></td>
                                                        </tr><?php endforeach;
                                                        else : ?><tr>
                                                        <td colspan="4" class="text-center text-muted py-3">Belum ada data penjualan.</td>
                                                    </tr><?php endif; ?></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow h-100 dashboard-summary-card">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-danger">Piutang Terbesar</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Rumah</th>
                                                    <th>Pembeli</th>
                                                    <th class="text-right">Sisa Piutang</th>
                                                </tr>
                                            </thead>
                                            <tbody><?php if ($piutang_terbesar) : foreach ($piutang_terbesar as $item) : ?><tr>
                                                            <td><?= htmlspecialchars($item->norumah); ?></td>
                                                            <td><?= htmlspecialchars($item->nama_cust); ?></td>
                                                            <td class="text-right text-danger font-weight-bold"><?= $rupiah($item->tagihan - $item->terbayar); ?></td>
                                                        </tr><?php endforeach;
                                                        else : ?><tr>
                                                        <td colspan="3" class="text-center text-muted py-3">Tidak ada piutang pelanggan.</td>
                                                    </tr><?php endif; ?></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-lg-5 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">10 Besar Pengeluaran per Kategori</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area"><canvas id="expenseCategoryChart"></canvas></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7 mb-4">
                            <div class="card shadow h-100">
                                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">List 10 Besar Pengeluaran per Kategori</h6>
                                    <a href="<?= base_url('admin/Cdashboard/laporan_pengeluaran/' . $id_perum); ?>" class="btn btn-link btn-sm p-0 text-primary">Lihat semua</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="width: 8%;">No.</th>
                                                    <th>Kategori</th>
                                                    <th class="text-right">Total Pengeluaran</th>
                                                    <th class="text-right">Porsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($pengeluaran_kategori)) : ?>
                                                    <?php foreach ($pengeluaran_kategori as $index => $kategori) : ?>
                                                        <?php $porsi = $total_pengeluaran > 0 ? ($kategori->nominal / $total_pengeluaran) * 100 : 0; ?>
                                                        <tr>
                                                            <td><?= $index + 1; ?></td>
                                                            <td><?= htmlspecialchars($kategori->nama_kateg); ?></td>
                                                            <td class="text-right font-weight-bold"><?= $rupiah($kategori->nominal); ?></td>
                                                            <td class="text-right"><?= number_format($porsi, 1, ',', '.'); ?>%</td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted py-3">Belum ada data pengeluaran.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary d-flex align-items-center justify-content-between">
                                <span>Monitoring Seluruh Unit Rumah</span>
                                <a href="<?= base_url('admin/Cdashboard/laporan_monitoring_unit/' . $id_perum); ?>" class="btn btn-link btn-sm p-0 text-primary">Lihat semua</a>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="unitTable" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
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
                                    <tbody><?php foreach ($status_rumah as $item) : $sisa = max(0, $item->tagihan - $item->terbayar);
                                                if (!$item->nama_cust) {
                                                    $status = '<span class="badge badge-secondary">Belum Terjual</span>';
                                                } elseif ($sisa > 0) {
                                                    $status = '<span class="badge badge-warning">Terjual - Piutang</span>';
                                                } else {
                                                    $status = '<span class="badge badge-success">Terjual - Lunas</span>';
                                                } ?><tr>
                                                <td></td>
                                                <td><?= htmlspecialchars($item->norumah); ?><small class="d-block text-muted"><?= htmlspecialchars($item->mtd_jual ?: '-'); ?></small></td>
                                                <td><?= $status; ?></td>
                                                <td><?= $item->nama_cust ? htmlspecialchars($item->nama_cust) . '<small class="d-block text-muted">' . $formatTanggal($item->tanggal_jual) . '</small>' : '-'; ?></td>
                                                <td class="text-right"><?= $rupiah($item->harga_jual); ?></td>
                                                <td class="text-right"><?= $rupiah($item->tagihan); ?></td>
                                                <td class="text-right"><?= $rupiah($item->terbayar); ?></td>
                                                <td class="text-right <?= $sisa > 0 ? 'text-danger font-weight-bold' : ''; ?>"><?= $rupiah($sisa); ?></td>
                                                <td class="text-right"><?= $rupiah($item->biaya_rumah); ?></td>
                                            </tr><?php endforeach; ?></tbody>
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
    <script src="<?= base_url('assets/adminsb/vendor/chart.js/Chart.min.js'); ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/datatables/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?= base_url('assets/adminsb/vendor/datatables/dataTables.bootstrap4.min.js'); ?>"></script>
    <script>
        var labelsSales = <?= json_encode(array_map(function ($row) {
                                return substr($row->bulan, 4, 2) . '/' . substr($row->bulan, 0, 4);
                            }, $penjualan_bulanan)); ?>;
        var dataSales = <?= json_encode(array_map(function ($row) {
                            return (int) $row->jumlah;
                        }, $penjualan_bulanan)); ?>;
        var labelsExpense = <?= json_encode(array_map(function ($row) {
                                return substr($row->bulan, 4, 2) . '/' . substr($row->bulan, 0, 4);
                            }, $pengeluaran_bulanan)); ?>;
        var dataExpense = <?= json_encode(array_map(function ($row) {
                                return (float) $row->nominal;
                            }, $pengeluaran_bulanan)); ?>;
        var labelsCategory = <?= json_encode(array_map(function ($row) {
                                    return $row->nama_kateg;
                                }, $pengeluaran_kategori)); ?>;
        var dataCategory = <?= json_encode(array_map(function ($row) {
                                return (float) $row->nominal;
                            }, $pengeluaran_kategori)); ?>;
        var salesCanvas = document.getElementById('salesChart');
        if (salesCanvas) {
            new Chart(salesCanvas, {
                type: 'bar',
                data: {
                    labels: labelsSales,
                    datasets: [{
                        label: 'Unit terjual',
                        data: dataSales,
                        backgroundColor: '#4e73df'
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0
                            }
                        }]
                    }
                }
            });
        }

        // expenseChart bersifat opsional karena layout dashboard dapat berubah.
        var expenseCanvas = document.getElementById('expenseChart');
        if (expenseCanvas) {
            new Chart(expenseCanvas, {
                type: 'line',
                data: {
                    labels: labelsExpense,
                    datasets: [{
                        label: 'Pengeluaran',
                        data: dataExpense,
                        borderColor: '#e74a3b',
                        backgroundColor: 'rgba(231,74,59,.12)',
                        fill: true
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(v) {
                                    return 'Rp ' + Number(v).toLocaleString('id-ID');
                                }
                            }
                        }]
                    }
                }
            });
        }

        var expenseCategoryCanvas = document.getElementById('expenseCategoryChart');
        if (expenseCategoryCanvas) {
            new Chart(expenseCategoryCanvas, {
                type: 'doughnut',
                data: {
                    labels: labelsCategory,
                    datasets: [{
                        data: dataCategory,
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796', '#5a5c69', '#6f42c1', '#fd7e14', '#20c997']
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom'
                    }
                }
            });
        }
        $(function() {
            var unitTable = $('#unitTable').DataTable({
                order: [
                    [1, 'asc']
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, -1],
                    [10, 25, 'Semua']
                ],
                columnDefs: [{
                    targets: 0,
                    searchable: false,
                    orderable: false
                }],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ unit',
                    paginate: {
                        previous: 'Sebelumnya',
                        next: 'Berikutnya'
                    },
                    zeroRecords: 'Data tidak ditemukan'
                }
            });

            unitTable.on('order.dt search.dt', function() {
                unitTable.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });
    </script>
</body>

</html>