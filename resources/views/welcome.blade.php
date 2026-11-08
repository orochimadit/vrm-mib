<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vessel Risk Management — PT Marine Insurance Broker</title>

    <!-- Google Font: Manrope -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet" />

    <!-- Vendor CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Main Public CSS -->
    <link rel="stylesheet" href="{{ asset('assets/themes/public/css/style.css') }}" type="text/css" />

    <style>
        .hero-banner .carousel-item img {
            width: 100%;
            height: 520px;
            object-fit: cover;
        }
        .thumb-features {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            display: block;
        }
        .thumb-features:hover {
            transform: translateY(-5px);
            text-decoration: none;
        }
        .nav-main .nav-login-btn {
            background-color: #16a34a;
            color: #ffffff !important;
            padding: 8px 18px;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .nav-main .nav-login-btn:hover {
            background-color: #15803d;
            color: #ffffff !important;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="main-header">
            <div class="container d-flex align-items-center justify-content-between">
                <div class="d-block">
                    <a class="logo-header" href="{{ url('/') }}">
                        <img src="{{ asset('assets/themes/public/images/logo-vrm.webp') }}" alt="Vessel Risk Management" style="max-height: 48px;" />
                    </a>
                </div>
                <nav>
                    <ul class="nav-main d-flex align-items-center mb-0 gap-3">
                        <li><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="#about-us">About Us</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#contact-us">Contact Us</a></li>
                        <li><a href="{{ url('/admin/login') }}" class="nav-login-btn"><i class="fa-solid fa-right-to-bracket me-1"></i> Login Portal</a></li>
                    </ul>
                </nav>
                <div class="btn-nav-mobile d-lg-none">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </header>
    <!-- /Header -->

    <!-- Hero Banner -->
    <section class="hero-banner">
        <div id="carouselHeroBanner" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselHeroBanner" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselHeroBanner" data-bs-slide-to="1" aria-label="Slide 2"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('assets/themes/public/images/new-banner.webp') }}" class="d-block w-100" alt="Vessel Risk Management Banner">
                    <div class="container carousel-text">
                        <div class="carousel-text-inner">
                            <h1>Vessel Risk Management</h1>
                            <span>Vessel Risk Management is the main service of PT. Marine Insurance Broker related to Risk Management to the vessel by direct visits on board to each vessel, assess the risk data submission as well as reporting the series of risk profile of the vessel. The assessment is carried out by expert Marine Insurance Consultant of PT. Marine Insurance Broker.</span>
                            <div class="mt-3">
                                <a href="{{ url('/admin/login') }}" class="btn btn-success btn-lg px-4 fw-bold">Masuk ke Sistem VRM</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/themes/public/images/banner-kapalku-claim-solution.webp') }}" class="d-block w-100" alt="Kapalku ClaimSolution Banner">
                    <div class="container carousel-text">
                        <div class="carousel-text-inner">
                            <h1>Kapalku ClaimSolution</h1>
                            <span>Each claim case is unique! Marine Insurance Broker will intensively assist our claim case and settle the claim in order.</span>
                            <div class="mt-3">
                                <a href="{{ url('/admin/login') }}" class="btn btn-primary btn-lg px-4 fw-bold">Eksplorasi Fitur</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselHeroBanner" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselHeroBanner" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    <!-- /Hero Banner -->

    <!-- Section About Us -->
    <section class="about-us py-5" id="about-us">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1 class="text-center fw-bold mb-4">About Us</h1>
                    <p class="text-center mb-0 lead" style="font-size: 1.1rem; line-height: 1.8;">Vessel Risk Management is the main service of PT. Marine Insurance Broker related to Risk Management in the form of direct visits on board by Marine Insurance Consultant PT. Marine Insurance Broker. Our entire engineering team has a Marine Engineering background. VRM has the aim of providing recommendations regarding Risk Management and Maintenance Management on ships.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- /Section About Us -->

    <!-- Section Features -->
    <section class="features py-5 bg-light" id="features">
        <div class="container">
            <h1 class="text-center fw-bold mb-5">Features & Portal Access</h1>
            <div class="row justify-content-center">
                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                    <a class="thumb-features text-center p-3 rounded bg-white shadow-sm" href="{{ url('/admin/login?level=1') }}">
                        <i class="fa-solid fa-user-gear fa-4x mb-3 text-primary"></i>
                        <h6 class="fw-semibold mb-0 text-dark">Admin</h6>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                    <a class="thumb-features text-center p-3 rounded bg-white shadow-sm" href="{{ url('/admin/login?level=2') }}">
                        <i class="fa-solid fa-user-shield fa-4x mb-3 text-success"></i>
                        <h6 class="fw-semibold mb-0 text-dark">Insurance</h6>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                    <a class="thumb-features text-center p-3 rounded bg-white shadow-sm" href="{{ url('/admin/login?level=5') }}">
                        <i class="fa-solid fa-users fa-4x mb-3 text-info"></i>
                        <h6 class="fw-semibold mb-0 text-dark">Partners</h6>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                    <a class="thumb-features text-center p-3 rounded bg-white shadow-sm" href="{{ url('/admin/login?level=3') }}">
                        <i class="fa-solid fa-user-secret fa-4x mb-3 text-warning"></i>
                        <h6 class="fw-semibold mb-0 text-dark">Surveyor</h6>
                    </a>
                </div>
                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                    <a class="thumb-features text-center p-3 rounded bg-white shadow-sm" href="{{ url('/admin/login?level=4') }}">
                        <i class="fa-solid fa-user-tie fa-4x mb-3 text-danger"></i>
                        <h6 class="fw-semibold mb-0 text-dark">Ship Owner</h6>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- /Section Features -->

    <!-- Section Benefits -->
    <section class="benefit py-5">
        <div class="container">
            <h1 class="text-center fw-bold mb-5">Benefits</h1>
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="thumb-vrm text-center p-4 border rounded shadow-sm" style="min-height: 200px;">
                        <i class="fa-solid fa-user-tie fa-4x mb-3 text-success"></i>
                        <h2 class="h5 fw-bold">
                            <span class="d-block">Professional and</span>
                            <span class="d-block">Expert Assistance</span>
                        </h2>
                    </div>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="thumb-vrm text-center p-4 border rounded shadow-sm" style="min-height: 200px;">
                        <i class="fa-solid fa-handshake fa-4x mb-3 text-primary"></i>
                        <h2 class="h5 fw-bold">
                            <span class="d-block">Trusted</span>
                            <span class="d-block">Partner</span>
                        </h2>
                    </div>
                </div>
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="thumb-vrm text-center p-4 border rounded shadow-sm" style="min-height: 200px;">
                        <i class="fa-solid fa-wrench fa-4x mb-3 text-warning"></i>
                        <h2 class="h5 fw-bold">
                            <span class="d-block">Technical</span>
                            <span class="d-block">Assistance</span>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Section Benefits -->

    <!-- Footer -->
    <footer class="footer bg-dark text-white pt-5 pb-4">
        <div class="footer-bottom">
            <div class="container mb-4" id="contact-us">
                <div class="row">
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <h3 class="text-white fw-bold mb-3">Vessel Risk Management</h3>
                        <p class="text-white-50">Ruko Sentra Menteng Jl. Samratulangi 81 Blok MN No 21 Kel. Pondok Jaya, Kec. Pondok Aren, Kota Tangerang Selatan, Provinsi Banten, Kode Pos 15220</p>
                    </div>
                    <div class="col-lg-3 offset-lg-1 mb-4 mb-lg-0">
                        <h5 class="text-white fw-bold mb-3">Hubungi Kami</h5>
                        <ul class="footer-list-contact list-unstyled">
                            <li class="mb-2">
                                <a href="https://www.instagram.com/mibinsurancebroker/" target="_blank" class="text-white-50 text-decoration-none">
                                    <i class="fa-brands fa-instagram me-2 text-white"></i>
                                    <span>mibinsurancebroker</span>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="https://web.facebook.com/people/PT-Marine-Insurance-Broker/61591205054200/" target="_blank" class="text-white-50 text-decoration-none">
                                    <i class="fa-brands fa-facebook me-2 text-white"></i>
                                    <span>Marine Insurance Broker</span>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="tel:+622127623991" class="text-white-50 text-decoration-none">
                                    <i class="fa-solid fa-phone me-2 text-white"></i>
                                    <span>+62 21 276 239 91</span>
                                </a>
                            </li>
                            <li class="mb-2">
                                <a href="mailto:group@mibbroker.com" class="text-white-50 text-decoration-none">
                                    <i class="fa-solid fa-envelope me-2 text-white"></i>
                                    <span>group@mibbroker.com</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-3">
                        <h5 class="text-white fw-bold mb-3">Keamanan & Privasi</h5>
                        <a href="#" class="text-white-50 text-decoration-none d-block mb-3">Kebijakan Privasi</a>
                        <ul class="logo-footer list-unstyled">
                            <li><img src="{{ asset('assets/themes/public/images/ssl-logo.png') }}" alt="SSL Secured" style="max-height: 45px;" /></li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="border-secondary container">
            <div class="container text-center text-white-50 small">
                <span>Vessel Risk Management is the digital platform developed by PT. Marine Insurance Broker &copy; {{ date('Y') }}</span>
            </div>
        </div>
    </footer>
    <!-- /Footer -->

    <!-- Vendor JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/themes/public/js/main.js') }}"></script>
</body>
</html>
