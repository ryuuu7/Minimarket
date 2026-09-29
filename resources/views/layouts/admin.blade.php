<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trim($__env->yieldContent('title', 'Dashboard')) }} · {{ config('app.name', 'Minimarket') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 258px; --navy: #14243a; --navy-soft: #1d3451; --canvas: #f4f7fb; }
        body { min-height: 100vh; background: var(--canvas); color: #253247; }
        .admin-sidebar { position: fixed; inset: 0 auto 0 0; z-index: 1030; width: var(--sidebar-width); padding: 1.5rem 1rem; color: #d5deea; background: var(--navy); }
        .brand-mark { display: grid; width: 42px; height: 42px; place-items: center; border-radius: 13px; color: #fff; background: #4569d4; font-size: 1.25rem; }
        .sidebar-label { margin: 2rem .75rem .65rem; color: #8393a9; font-size: .7rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .sidebar-link { display: flex; align-items: center; gap: .85rem; margin: .25rem 0; padding: .8rem .9rem; border-radius: 10px; color: #b8c5d6; text-decoration: none; transition: .18s ease; }
        .sidebar-link:hover { color: #fff; background: var(--navy-soft); }
        .sidebar-link.active { color: #fff; background: #304b70; box-shadow: inset 3px 0 #86a7ff; }
        .sidebar-link i { width: 20px; font-size: 1.05rem; }
        .sidebar-bottom { position: absolute; right: 1rem; bottom: 1rem; left: 1rem; }
        .admin-main { min-height: 100vh; margin-left: var(--sidebar-width); }
        .topbar { position: sticky; top: 0; z-index: 1020; display: flex; min-height: 76px; align-items: center; justify-content: space-between; padding: 0 2rem; border-bottom: 1px solid #e8edf4; background: rgba(255,255,255,.94); backdrop-filter: blur(12px); }
        .page-content { max-width: 1500px; margin: auto; padding: 2rem; }
        .user-avatar { display: grid; width: 38px; height: 38px; place-items: center; border-radius: 50%; color: #3457b5; background: #e8eeff; font-weight: 700; }
        @media (max-width: 767.98px) {
            .admin-sidebar { position: static; width: 100%; padding: .75rem 1rem; }
            .sidebar-brand { margin-bottom: .65rem; }
            .sidebar-label { display: none; }
            .sidebar-bottom { position: static; display: flex; justify-content: flex-end; margin-top: .5rem; }
            .sidebar-bottom > div { display: none; }
            .sidebar-menu { display: flex; gap: .35rem; overflow-x: auto; }
            .sidebar-link { flex: 0 0 auto; margin: 0; padding: .65rem .8rem; }
            .admin-main { margin-left: 0; }
            .topbar { min-height: 64px; padding: 0 1rem; }
            .page-content { padding: 1.25rem 1rem; }
        }
    </style>
</head>
<body>
    <aside class="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand d-flex align-items-center gap-3 px-2 text-decoration-none text-white">
            <span class="brand-mark"><i class="bi bi-shop"></i></span>
            <span><strong class="d-block">Minimarket</strong><small class="text-white-50">Admin Panel</small></span>
        </a>

        <div class="sidebar-label">Menu utama</div>
        <nav class="sidebar-menu" aria-label="Navigasi admin">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i><span>Produk</span>
            </a>
        </nav>

        <div class="sidebar-bottom">
            <div class="mb-3 px-2 small text-white-50">Masuk sebagai<br><strong class="text-white">{{ auth()->user()->name }}</strong></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-100 border-0 text-start" style="background: transparent">
                    <i class="bi bi-box-arrow-left"></i><span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="admin-main">
        <header class="topbar">
            <div>
                <div class="fw-semibold">@yield('page-heading', 'Dashboard')</div>
                <div class="small text-secondary">Kelola operasional minimarket</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <span class="d-none d-sm-inline small fw-semibold">{{ auth()->user()->name }}</span>
            </div>
        </header>

        <main class="page-content">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
