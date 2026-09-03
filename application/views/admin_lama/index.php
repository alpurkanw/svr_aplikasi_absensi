<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bintang Lacita Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html {
            scroll-behavior: smooth;
        }

        .parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
        }

        .section {
            padding: 100px 0;
            color: white;
            position: relative;
            z-index: 1;
        }

        .section-light {
            padding: 100px 0;
            background-color: #f8f9fa;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: white;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">Bintang Lacita Group</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sejarah">Sejarah</a></li>
                    <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="parallax" style="background-image: url('https://images.unsplash.com/photo-1503387762-592deb58ef4e'); height: 100vh;">
        <div class="overlay"></div>
        <div class="section d-flex justify-content-center align-items-center flex-column text-center">
            <h1 class="display-4">Selamat Datang di Bintang Lacita Group</h1>
            <p class="lead">Mitra Anda di Bidang Properti, Pertambangan, dan Retail Bangunan</p>
        </div>
    </div>

    <!-- Tentang -->
    <section id="tentang" class="section-light text-center">
        <div class="container">
            <h2>Tentang Kami</h2>
            <p>Bintang Lacita Group adalah perusahaan induk yang menaungi berbagai unit bisnis di sektor properti, tambang pasir, dan toko bangunan. Kami berkomitmen menghadirkan solusi terbaik untuk pembangunan Indonesia.</p>
        </div>
    </section>

    <!-- Sejarah -->
    <section id="sejarah" class="parallax" style="background-image: url('https://images.unsplash.com/photo-1581093588401-6c76aa3f7f33');">
        <div class="overlay"></div>
        <div class="section text-center">
            <div class="container">
                <h2>Sejarah Perusahaan</h2>
                <p>Didirikan sejak tahun 2005, Bintang Lacita Group bermula dari sektor perdagangan bahan bangunan dan berkembang ke properti serta pertambangan. Kami terus bertumbuh sebagai grup usaha yang solid dan terpercaya.</p>
            </div>
        </div>
    </section>

    <!-- Layanan -->
    <section id="layanan" class="section-light text-center">
        <div class="container">
            <h2>Layanan Kami</h2>
            <div class="row">
                <div class="col-md-4">
                    <h4>Properti</h4>
                    <p>Pembangunan kawasan perumahan dan komersial.</p>
                </div>
                <div class="col-md-4">
                    <h4>Tambang Pasir</h4>
                    <p>Distribusi material pasir untuk kebutuhan konstruksi besar.</p>
                </div>
                <div class="col-md-4">
                    <h4>Toko Bangunan</h4>
                    <p>Retail bahan bangunan dan perlengkapan teknik.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tambahan Parallax Tambang atau Retail -->
    <section class="parallax" style="background-image: url('https://images.unsplash.com/photo-1581091012184-cb8c0a24b56d');">
        <div class="overlay"></div>
        <div class="section text-center">
            <div class="container">
                <h2>Operasional Tambang & Retail</h2>
                <p>Kami mengelola tambang pasir berizin serta jaringan toko bangunan yang tersebar di beberapa kota besar.</p>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="section-light text-center">
        <div class="container">
            <h2>Kontak Kami</h2>
            <p>Email: info@bintanglacita.co.id<br>Telepon: (021) 123-4567</p>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <small>&copy; 2025 Bintang Lacita Group. All Rights Reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>