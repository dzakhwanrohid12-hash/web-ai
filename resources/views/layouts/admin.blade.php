<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Sistem Parkir</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=1.1">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header" style="padding: 30px 20px; text-align: center; border-bottom: 1px solid rgba(255, 255, 255, 0.08); margin-bottom: 16px;">
            <img src="{{ asset('images/logo-pcr.png') }}" alt="Logo PCR" style="width: 90%; max-width: 180px; height: auto; display: block; margin: 0 auto; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));">
            <p style="font-size: 0.75rem; color: #94A3B8; margin-top: 15px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px;">
                Sistem Prediksi Parkir
            </p>
        </div>

        <nav class="sidebar-menu">
           <a href="{{ route('admin.dashboard') }}" class="menu-item {{ Request::is('admin') ? 'active' : '' }}">
    <i class="fas fa-chart-pie"></i><span>Dashboard Analitik</span>
</a>
<a href="{{ route('admin.history') }}" class="menu-item {{ Request::is('admin/riwayat') ? 'active' : '' }}">
    <i class="fas fa-history"></i><span>Riwayat Pencarian</span>
</a>
<a href="{{ route('admin.dataset') }}" class="menu-item {{ Request::is('admin/dataset') ? 'active' : '' }}">
    <i class="fas fa-database"></i><span>Kelola Dataset</span>
</a>
<a href="{{ route('admin.visualisasi') }}" class="menu-item {{ Request::routeIs('admin.visualisasi') ? 'active' : '' }}">
    <i class="fas fa-tree"></i><span>Visualisasi Model</span>
</a>
<a href="{{ route('admin.users') }}" class="menu-item {{ Request::routeIs('admin.users') ? 'active' : '' }}">
    <i class="fas fa-users"></i><span>Manajemen Pengguna</span>
</a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="submit" class="logout-button">
                    <i class="fas fa-sign-out-alt"></i><span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1>@yield('page_title', 'Dashboard Admin')</h1>
            <a href="{{ route('parking.index') }}" class="btn-web-publik" target="_self">
                <i class="fas fa-external-link-alt"></i> Lihat Website Publik
            </a>
        </div>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
