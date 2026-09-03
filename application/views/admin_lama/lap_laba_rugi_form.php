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
                            <h6 class="m-0 font-weight-bold text-primary">LAPORAN LABA RUGI PER PERUMAHAN</h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="<?= base_url('admin/Claporan/lap_laba_rugi_view'); ?>" method="post">
                                <div class="form-group">
                                    <label for="id_perumahan">Pilih Perumahan</label>
                                    <select class="form-control" id="id_perumahan" name="id_perumahan" required>
                                        <option value="">Pilih Perumahan</option>
                                        <?php if (!empty($list_perum)) : ?>
                                            <?php foreach ($list_perum as $perum) : ?>
                                                <option value="<?= $perum->id; ?>"><?= htmlspecialchars($perum->nama); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">Tampilkan Data</button>
                                <a href="<?= base_url('admin/Home'); ?>" class="btn btn-secondary">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url("assets/adminsb/"); ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url("assets/adminsb/"); ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url("assets/adminsb/"); ?>vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="<?= base_url("assets/adminsb/"); ?>js/sb-admin-2.min.js"></script>
</body>

</html>