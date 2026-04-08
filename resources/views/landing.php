<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interaktif WebGIS</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        body {
            background: linear-gradient(to right, #ff6e7f, #bfe9ff, #ffeaa7, #96ceb4, #9b59b6, #2ecc71, #3498db);
            background-size: 1400% 1400%;
            animation: gradientAnimation 30s ease infinite;
        }

        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .jumbotron {
            background-color: rgba(255, 255, 255, 0.8);
            color: #333;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .jumbotron::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, transparent 30%, rgba(255, 255, 255, 0.2) 60%, transparent 70%);
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .card-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .parallax {
            background-image: linear-gradient(to right, #ffeaa7, #96ceb4);
            height: 500px;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .btn-primary {
            background-color: #ff6e7f;
            border-color: #ff6e7f;
        }

        .btn-primary:hover {
            background-color: #ff4d68;
            border-color: #ff4d68;
        }

        .btn-outline-dark {
            color: #333;
            border-color: #333;
        }

        .btn-outline-dark:hover {
            background-color: #333;
            color: white;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.8);
            color: #333;
            border: none;
        }
    </style>
</head>
<body>
<div class="jumbotron jumbotron-fluid animate__animated animate__fadeIn position-relative">
    <div class="container text-center d-flex flex-column align-items-center position-relative">
        <div class="logo-container top-50 start-0 translate-middle-x">
            <img src="storage/assets/ugm.png" width="100px" height="30px" alt="Logo 1" class="img-fluid mr-3">
            <img src="storage/assets/sig.png" width="100px" height="30px" alt="Logo 2" class="img-fluid ml-3">
        </div>
        <div class="mt-5">
            <h1 class="display-4 mb-5 animate__animated animate__bounceInDown">Selamat Datang di WebGIS JJ</h1>
            <p class="lead mb-5 animate__animated animate__bounceInUp">"Jogja Journey: Petualangan Wisata Seru di Kota Yogyakarta dengan Satu Klik!"</p>
            <a href="/" class="btn btn-primary btn-lg mr-3 animate__animated animate__bounceInLeft">Mulai Eksplorasi</a>
            <a href="#features" class="btn btn-outline-dark btn-lg animate__animated animate__bounceInRight">Lihat Fitur</a>
        </div>
    </div>
</div>
</body>
</html>

    <div class="container my-5" id="explore">
        <div class="row">
            <div class="col-md-6 animate__animated animate__fadeInLeft">
                <h2 class="mb-4">Eksplorasi Peta Interaktif</h2>
                <p>Nikmati pengalaman menjelajah dunia dengan peta interaktif kami yang menakjubkan. Zoom masuk dan keluar, geser peta, dan temukan lokasi baru dengan mudah.</p>
                <a href="#" class="btn btn-primary">Mulai Eksplorasi</a>
            </div>
            <div class="col-md-6 animate__animated animate__fadeInRight">
                <div class="embed-responsive embed-responsive-16by9">
                <iframe width="560" height="315" src="https://www.youtube.com//embed/Zkt_4obhNkQ?si=n5Oze8nIRIoSEefe" title="YouTube video player" frameborder="0" allow="accelerometer;
                    autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    <div class="parallax d-flex justify-content-center align-items-center">
    <div class="container my-5" id="explore">
        <div class="row">
            <div class="col-md-6 animate__animated animate__fadeInRight">
                <div class="embed-responsive embed-responsive-16by9">
                <iframe width="560" height="315" src="https://www.youtube.com//embed/jguEPAtg-BQ?si=wx03mxvyK5TybRkm" title="YouTube video player" frameborder="0" allow="accelerometer;
                    autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div class="col-md-6 animate__animated animate__fadeInLeft">
                <h2 class="mb-4">Eksplorasi Peta Interaktif</h2>
                <p>Nikmati pengalaman walkerselajah dunia dengan peta interaktif kami yang menakjubkan. Zoom masuk dan keluar, geser peta, dan temukan lokasi baru dengan mudah.</p>
                <a href="#" class="btn btn-primary">Mulai Eksplorasi</a>
            </div>
        </div>
    </div>
    </div>

    <div class="container my-5 bg-light py-5" id="features">
        <h2 class="text-center mb-5 animate__animated animate__bounceInDown">Fitur Utama</h2>
        <div class="row">
            <div class="col-md-4 mb-4 animate__animated animate__fadeInLeft">
                <div class="card card-hover text-center">
                    <div class="card-body">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <h5 class="card-title">Pencarian Lokasi</h5>
                        <p class="card-text">Temukan lokasi dengan cepat dan mudah menggunakan fitur pencarian kami yang canggih.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 animate__animated animate__fadeInUp">
                <div class="card card-hover text-center">
                    <div class="card-body">
                        <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                        <h5 class="card-title">Informasi Detail Lokasi</h5>
                        <p class="card-text">Dapatkan informasi detail tentang lokasi yang Anda inginkan, termasuk deskripsi, foto, dan data lainnya.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 animate__animated animate__fadeInRight">
                <div class="card card-hover text-center">
                    <div class="card-body">
                        <i class="fas fa-sync fa-3x mb-3"></i>
                        <h5 class="card-title">Rute Perjalanan</h5>
                        <p class="card-text">Jelajahi lebih mudah dengan Fitur Rute perjalanan ke Destinasi Wisata di Pusat Kota Yogyakarta.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="parallax d-flex justify-content-center align-items-center">
    <div class="map-container">
        <div class="map-overlay"></div>
        <!-- Replace the src with your actual map or use an iframe from your WebGIS service -->
        <iframe class="map" src="/" frameborder="0" width='700' height='480'></iframe>
    </div>
    </div>

    <div class="container my-5 py-5" id="testimonials">
        <h2 class="text-center mb-5 animate__animated animate__bounceInDown">Testimonial</h2>
        <div class="row">
            <div class="col-md-4 mb-4 animate__animated animate__fadeInLeft">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Amirul Fahmi</h5>
                        <p class="card-text">"WebGIS interaktif ini benar-benar membantu saya dalam merencanakan perjalanan. Fitur-fiturnya sangat lengkap dan mudah digunakan."</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 animate__animated animate__fadeInUp">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tita Amalia</h5>
                        <p class="card-text">"Saya sangat terkesan dengan tampilan peta yang detail dan kemampuan zoom yang luar biasa. Sebuah pengalaman yang menakjubkan!"</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4 animate__animated animate__fadeInRight">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Farah Nabila</h5>
                        <p class="card-text">"Fitur pencarian lokasi sangat membantu saya dalam menemukan tempat-tempat baru yang ingin saya kunjungi. Terima kasih WebGIS interaktif!"</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambahkan kode ini sebelum penutup </body> -->
<footer class="bg-dark text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5>Tentang Kami</h5>
                <p>D4 Sistem Informasi Geografis UGM
                <br> <br> Alamat: Gedung SV UGM, Sekip Unit 1, Jl. Persatuan, Blimbing Sari, Caturtunggal, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281
                <br>Telp: (027)4541020</p>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Tautan Berguna</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-white">Beranda</a></li>
                    <li><a href="#explore" class="text-white">Eksplorasi</a></li>
                    <li><a href="#features" class="text-white">Fitur</a></li>
                    <li><a href="#testimonials" class="text-white">Testimoni</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Ikuti Kami</h5>
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-2x"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-2x"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-2x"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <p>&copy; 2024 Jogja Journey WebGIS. Make by Muhammad Nashan Fauzian.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Script untuk efek hover pada ikon media sosial -->
<script>
    $(document).ready(function() {
        $(".list-inline-item a").hover(
            function() {
                $(this).addClass("animate__animated animate__bounce");
            },
            function() {
                $(this).removeClass("animate__animated animate__bounce");
            }
        );
    });
</script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
