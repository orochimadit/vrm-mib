<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Vessel Risk Management' }} — PT MIB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <link rel="stylesheet" href="{{ asset('assets/themes/jeramin/vendors/bootstrap-4/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/themes/jeramin/vendors/font-awesome/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/themes/jeramin/vendors/animate/animate.css') }}" type="text/css">

    <!-- Webfont -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- Main Jeramin CSS -->
    <link rel="stylesheet" href="{{ asset('assets/themes/jeramin/css/style.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/themes/jeramin/css/config.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/themes/jeramin/css/theme.white.css') }}" type="text/css">

    <style>
        .sidebar-nav ul li.active > a {
            background-color: #f3f4f6 !important;
            border-left: 4px solid #16a34a !important;
            font-weight: 600 !important;
            color: #16a34a !important;
        }
        .table-yellow-row {
            background-color: #ffff00 !important;
        }
        .btn-action-group .btn {
            margin-right: 2px;
        }
        @media print {
            .header, .sidebar, .breadcrumb-outer, .btn-action, .d-print-none {
                display: none !important;
            }
            .wrapper {
                margin-left: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-light dark-sidebar light-header">

    <!-- Header -->
    <header class="header shadow-sm d-print-none">
        <nav class="navbar navbar-expand navbar-light nav-header px-2">
            <!-- Logo Header -->
            <div class="logo-wrapper">
                <a class="navbar-brand" href="{{ url('/admin') }}">
                    <h4 class="mb-0 font-weight-bold" style="font-size: 1.1rem; letter-spacing: 0.5px;">VESSEL RISK MANAGEMENT</h4>
                </a>
                <div class="sidebar-toggle-sm">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <!-- Search & User -->
            <div class="collapse navbar-collapse">
                <div class="search-header">
                    <form onsubmit="return false;">
                        <input type="text" placeholder="search">
                        <button type="button"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center" href="#" data-toggle="dropdown">
                            <figure class="img-shape img-shape-sm bg-light mb-0 mr-2" style="width: 32px; height: 32px; border-radius: 50%; overflow: hidden;">
                                <img src="{{ asset('images/legacy/face-1.jpg') }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                            </figure>
                            <span class="d-none d-md-inline text-dark small font-weight-bold">{{ Auth::user()->name ?? 'Administrator' }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right border-0 shadow">
                            <a class="dropdown-item" href="{{ url('/admin/users') }}"><i class="fa fa-user mr-2 text-muted"></i> Profil</a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('filament.admin.auth.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fa fa-power-off mr-2"></i> Logout</button>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar scroll-custom d-print-none">
        <div class="sidebar-header">
            <span class="sidebar-title">Navigation</span>
            <div class="sidebar-toggle-lg">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <div class="sidebar-inner">
            <nav class="sidebar-nav">
                <ul>
                    <li class="{{ request()->is('admin') ? 'active' : '' }}">
                        <a href="{{ url('/admin') }}">
                            <i class="fa fa-home"></i>
                            <span class="text-nav">Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/services*') ? 'active' : '' }}">
                        <a href="{{ url('/admin/services') }}">
                            <i class="fa fa-cogs"></i>
                            <span class="text-nav">Services</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/companies*') ? 'active' : '' }}">
                        <a href="{{ url('/admin/companies') }}">
                            <i class="fa fa-ship"></i>
                            <span class="text-nav">Vessel &amp; Owner Company</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/insurance-products*') ? 'active' : '' }}">
                        <a href="{{ url('/admin/insurance-products') }}">
                            <i class="fa fa-th-list"></i>
                            <span class="text-nav">Product Insurance</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/surveyors*') ? 'active' : '' }}">
                        <a href="{{ url('/admin/surveyors') }}">
                            <i class="fa fa-users"></i>
                            <span class="text-nav">Surveyor Company</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/surveys*') ? 'active' : '' }}">
                        <a href="{{ url('/admin/surveys') }}">
                            <i class="fa fa-bars"></i>
                            <span class="text-nav font-weight-bold">Vessel Risk Management</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/claims*') ? 'active' : '' }}">
                        <a href="{{ url('/admin/claims') }}">
                            <i class="fa fa-list-alt"></i>
                            <span class="text-nav font-weight-bold">Claim Solution</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/insurance-companies*') || request()->is('admin/claim-adjusters*') ? 'active' : '' }}">
                        <a href="#insuranceSubmenu" data-toggle="collapse" class="dropdown-toggle">
                            <i class="fa fa-shield"></i>
                            <span class="text-nav">Insurance</span>
                        </a>
                        <ul class="collapse list-unstyled pl-3 {{ request()->is('admin/insurance-companies*') || request()->is('admin/claim-adjusters*') ? 'show' : '' }}" id="insuranceSubmenu">
                            <li><a href="{{ url('/admin/insurance-companies') }}"><span class="text-nav">Companies</span></a></li>
                            <li><a href="{{ url('/admin/claim-adjusters') }}"><span class="text-nav">Claim Adjusters</span></a></li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('admin/brokers*') ? 'active' : '' }}">
                        <a href="{{ url('/admin/brokers') }}">
                            <i class="fa fa-handshake-o"></i>
                            <span class="text-nav">Broker/Partners</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/vessel-types*') ? 'active' : '' }}">
                        <a href="{{ url('/admin/vessel-types') }}">
                            <i class="fa fa-anchor"></i>
                            <span class="text-nav">Vessel Type</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/users*') ? 'active' : '' }}">
                        <a href="{{ url('/admin/users') }}">
                            <i class="fa fa-user-circle"></i>
                            <span class="text-nav">Management Administrator</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <section class="wrapper p-4">
        @yield('content')
    </section>

    <!-- Vendors JS -->
    <script src="{{ asset('assets/themes/jeramin/vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/themes/jeramin/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('assets/themes/jeramin/vendors/bootstrap-4/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/themes/jeramin/js/main.js') }}"></script>
    @stack('scripts')

</body>
</html>
