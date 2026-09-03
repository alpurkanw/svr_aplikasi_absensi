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
        body {
            min-height: 100vh;
            margin: 0;
            background-color: #f8f9fc;
        }

        .login-split {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        .login-illustration {
            flex: 0 0 70%;
            min-height: 100vh;
            background-image: url('<?= base_url("assets/images/gambar_depan.webp") ?>');
            background-size: cover;
            background-position: center;
            position: relative;
            color: #fff;
        }

        .login-illustration::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
        }

        .illustration-text {
            position: absolute;
            left: 48px;
            bottom: 48px;
            right: 48px;
            z-index: 1;
            max-width: 520px;
        }

        .login-form-panel {
            flex: 0 0 30%;
            min-width: 320px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #f8f9fc;
        }

        .login-card {
            width: 100%;
            max-width: 380px;
        }

        .login-card .p-5 {
            padding: 2rem !important;
        }

        @media (max-width: 992px) {
            .login-split {
                flex-direction: column;
            }

            .login-illustration,
            .login-form-panel {
                flex: none;
                width: 100%;
            }

            .login-illustration {
                min-height: 320px;
            }

            .login-form-panel {
                padding: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="login-split">
        <div class="login-illustration">
            <div class="illustration-text">
            </div>
        </div>

        <div class="login-form-panel">
            <div class="card o-hidden border-0 shadow-lg login-card">
                <div class="card-body p-0">
                    <div class="p-3">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4 fw-bold">Selamat Datang Kembali!</h1>
                        </div>

                        <?php if ($this->session->flashdata('error')) : ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?= $this->session->flashdata('error') ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <form class="user" method="POST" action="<?= site_url('Auth/login_process') ?>">

                            <div class="form-group">
                                <input type="text" name="username" id="username" class="form-control form-control-user"
                                    placeholder="Masukkan Username Anda..." required autofocus>
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" id="password" class="form-control form-control-user"
                                    placeholder="Password" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-user btn-block mt-4">
                                Masuk
                            </button>
                        </form>
                        <hr>

                        <div class="text-center mt-4">
                            <a class="small" href="#">Lupa Password?</a>
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