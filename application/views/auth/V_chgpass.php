<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login Sistem Keuangan</title>
    <link href="<?= base_url("assets/adminsb/"); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="<?= base_url("assets/adminsb/"); ?>css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        .full-screen-center {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fc;
            /* Warna background default SB Admin 2 */
        }
    </style>
</head>

<body class="full-screen-center">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Ganti Password
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <?= $this->session->flashdata('pesan'); ?>
                        <form action="<?= base_url('Auth/gantiPass_Proses') ?>" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="old_password">Password Lama:</label>
                                    <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Masukkan password lama">
                                    <?php echo form_error('old_password', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="new_password">Password Baru:</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Masukkan password baru (minimal 8 karakter)">
                                    <?php echo form_error('new_password', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="new_password_confirm">Konfirmasi Password Baru:</label>
                                    <input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" placeholder="Konfirmasi password baru">
                                    <?php echo form_error('new_password_confirm', '<small class="text-danger">', '</small>'); ?>
                                </div>
                                <div class="form-group mt-2">
                                    <a href="<?= base_url() ?>" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">Ganti Password</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /.card-body -->
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