<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Manajemen Kos' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(145deg, #f5f7fb, #eef2ff);
            min-height: 100vh;
            opacity: 0;
            transform: translateY(8px);
            transition: opacity .35s ease, transform .35s ease;
        }
        body.page-ready {
            opacity: 1;
            transform: translateY(0);
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: .3px;
        }
        .navbar {
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .card-soft {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, .08);
            transition: transform .25s ease, box-shadow .25s ease;
        }
        .card-soft:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 35px rgba(15, 23, 42, .12);
        }
        .stats-card {
            color: #fff;
            border-radius: 16px;
            transition: transform .25s ease, filter .25s ease;
        }
        .stats-card:hover {
            transform: translateY(-3px);
            filter: brightness(1.03);
        }
        .stats-card h3 {
            margin: 0;
            font-weight: 700;
        }
        .stats-card .icon {
            font-size: 1.8rem;
            opacity: .9;
        }
        .table thead th {
            background: #f8fafc;
        }
        .btn {
            transition: transform .18s ease, box-shadow .2s ease, background-color .2s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
        }
        .form-control, .form-select {
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }
        .form-control:focus, .form-select:focus {
            transform: translateY(-1px);
        }
        [data-animate] {
            opacity: 0;
            transform: translateY(14px);
            transition: opacity .4s ease, transform .4s ease;
        }
        [data-animate].in-view {
            opacity: 1;
            transform: translateY(0);
        }
        .table tbody tr {
            transition: background-color .2s ease, transform .18s ease;
        }
        .table tbody tr:hover {
            background-color: #f8fafc;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
@auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Kos Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('penghuni.*') ? 'active' : '' }}" href="{{ route('penghuni.index') }}">Penghuni</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kamar.*') ? 'active' : '' }}" href="{{ route('kamar.index') }}">Kamar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}" href="{{ route('pembayaran.index') }}">Pembayaran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">Laporan</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3 text-white">
                    <span><i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-light" type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
@endauth

<main class="container py-4">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.body.classList.add('page-ready');

        const targets = document.querySelectorAll('.card-soft, .alert, .table-responsive, h2, h3, h4, form');
        targets.forEach((el) => el.setAttribute('data-animate', ''));

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('[data-animate]').forEach((el, idx) => {
            el.style.transitionDelay = `${Math.min(idx * 35, 220)}ms`;
            observer.observe(el);
        });
    });
</script>
@stack('scripts')
</body>
</html>
