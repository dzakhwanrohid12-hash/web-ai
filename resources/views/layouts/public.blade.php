<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Prediksi Parkir Kampus')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=1.1">
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    @stack('styles')
</head>
<body>

    <nav class="navbar">
    <div class="nav-brand">
        <img src="{{ asset('images/logo-app.png') }}" alt="Logo PCR" class="brand-logo">
        <span class="brand-text">Sistem Prediksi Parkir</span>
    </div>

    <div class="nav-menu">
        <a href="{{ route('parking.index') }}" class="nav-link">
            <i class="fas fa-home"></i> Beranda
        </a>
        <button onclick="openMapModal()" class="nav-link nav-link-btn" style="background: none; border: none; cursor: pointer;">
            <i class="fas fa-map-marked-alt"></i> Denah Kampus
        </button>

        @auth
            <a href="{{ route('user.history') }}" class="nav-link">
                <i class="fas fa-history"></i> Riwayat Saya
            </a>
        @endauth

        @if(Auth::check())
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i> Admin
                </a>
            @endif
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        @endif
    </div>

    <!-- Hamburger untuk mobile (opsional) -->
    <div class="nav-toggle" id="navToggle">
        <i class="fas fa-bars"></i>
    </div>
</nav>

    @yield('content')

    @stack('scripts')
</body>
</html>
