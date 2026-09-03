<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= htmlspecialchars($judul); ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon_04.png') ?>">
    <link href="<?= base_url('assets/adminsb/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet">
    <link href="<?= base_url('assets/adminsb/css/sb-admin-2.min.css'); ?>" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <?php $this->load->view('admin/01_sidebar'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php $this->load->view('admin/02_topbar'); ?>
                <div class="container-fluid p-3">
                    <h1 class="h3 mb-4 text-gray-800">Dashboard Per Perumahan</h1>
                    <?= $this->session->flashdata('pesan'); ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Pilih Perumahan</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Dashboard menampilkan seluruh data transaksi sampai saat ini.</p>
                            <form action="<?= base_url('admin/Cdashboard/per_perumahan_view'); ?>" method="post">
                                <div class="form-row">
                                    <div class="form-group col-md-6"><label for="id_perumahan">Perumahan</label><select class="form-control" name="id_perumahan" id="id_perumahan" required>
                                            <option value="">Pilih perumahan</option><?php foreach ($list_perum as $perum) : ?><option value="<?= (int) $perum->id; ?>"><?= htmlspecialchars($perum->nama); ?></option><?php endforeach; ?>
                                        </select></div>
                                </div><button type="submit" class="btn btn-primary"><i class="fas fa-chart-line mr-1"></i>Tampilkan Dashboard</button>
                            </form>
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