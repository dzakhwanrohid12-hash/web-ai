<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Sistem Parkir</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4F46E5;
            --primary-hover: #4338CA;
            --sidebar-bg: #1F2937;
            --sidebar-hover: #374151;
            --bg-color: #F9FAFB;
            --card-bg: #FFFFFF;
            --text-main: #111827;
            --text-muted: #6B7280;
            --border: #E5E7EB;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: white;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            transition: all 0.3s;
        }

        .sidebar-header {
            padding: 25px 20px;
            font-size: 1.2rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            letter-spacing: 1px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            padding: 20px 0;
            flex: 1;
        }

        .menu-item {
            padding: 15px 25px;
            color: #D1D5DB;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }

        .menu-item:hover {
            background-color: var(--sidebar-hover);
            color: white;
        }

        .menu-item.active {
            background-color: var(--sidebar-hover);
            color: white;
            border-left: 4px solid var(--primary);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .main-content {
            flex: 1;
            padding: 30px 40px;
            overflow-y: auto;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }
        .modal-content { background-color: #fff; margin: 10% auto; padding: 30px; border-radius: 12px; width: 90%; max-width: 400px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); position: relative; animation: slideDown 0.3s ease-out; }
        @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .close-modal { position: absolute; right: 20px; top: 15px; font-size: 24px; cursor: pointer; color: var(--text-muted); }
    </style>
    @stack('styles')
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            Sistem Rekomendasi Parkir
        </div>
        
        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="menu-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                📊 Dashboard
            </a>
            <a href="{{ route('admin.dataset') }}" class="menu-item {{ Request::routeIs('admin.dataset') ? 'active' : '' }}">
                📁 Import Data
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="{{ route('parking.index') }}" class="menu-item" style="background: var(--primary); border-radius: 6px; justify-content: center; color:white;">
                View Web Publik
            </a>
        </div>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1>@yield('page_title', 'Halaman Admin')</h1>
        </div>

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>