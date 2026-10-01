<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Quản lý cửa hàng</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f9;
            min-height: 100vh;
        }
        .admin-wrapper { display: flex; min-height: 100vh; }
        .sidebar {
            width: 240px;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            color: #fff;
            flex-shrink: 0;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-brand h4 { margin: 0; font-size: 1.1rem; font-weight: 700; color: #fff; }
        .sidebar-brand small { color: rgba(255,255,255,0.5); font-size: 0.75rem; }
        .sidebar-menu-label {
            padding: 20px 20px 8px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s, background 0.2s, transform 0.15s, border-color 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar-nav a:hover {
            color: #fff;
            background: rgba(255,255,255,0.08);
            transform: translateX(4px);
        }
        .sidebar-nav a:active { transform: translateX(2px) scale(0.98); }
        .sidebar-nav a.active {
            color: #fff;
            background: rgba(99, 102, 241, 0.25);
            border-left-color: #6366f1;
        }
        .sidebar-nav a i { width: 18px; text-align: center; font-size: 0.85rem; }
        .main-content {
            margin-left: 240px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            background: #fff;
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e8ecf0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .topbar-title { font-size: 1rem; font-weight: 600; color: #1a1a2e; margin: 0; }
        .topbar-user { display: flex; align-items: center; gap: 12px; }
        .topbar-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.85rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .topbar-avatar:hover { transform: scale(1.08); box-shadow: 0 4px 12px rgba(99,102,241,0.4); }
        .btn-ghost {
            background: transparent; border: none; color: #6b7280;
            font-size: 0.85rem; font-weight: 600; cursor: pointer;
            padding: 6px 10px; border-radius: 8px;
            transition: background 0.2s, color 0.2s, transform 0.15s;
        }
        .btn-ghost:hover { background: #f3f4f6; color: #111827; }
        .btn-ghost:active { transform: scale(0.96); }
        .page-content { padding: 28px; flex: 1; }
        .breadcrumb-custom { font-size: 0.82rem; color: #6b7280; margin-bottom: 6px; }
        .breadcrumb-custom a { color: #6366f1; text-decoration: none; transition: color 0.15s; }
        .breadcrumb-custom a:hover { color: #4f46e5; }
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .page-header h1 { font-size: 1.6rem; font-weight: 700; color: #1a1a2e; margin: 0; }
        .content-card {
            background: #fff; border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            overflow: hidden;
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .content-card .card-body { padding: 0; }
        .admin-table { margin: 0; }
        .admin-table thead th {
            background: #f9fafb; border: none; border-bottom: 1px solid #e8ecf0;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.5px;
            text-transform: uppercase; color: #6b7280; padding: 14px 20px;
        }
        .admin-table tbody td {
            padding: 16px 20px; border-top: 1px solid #f0f2f5;
            vertical-align: middle; color: #374151; font-size: 0.9rem;
        }
        .admin-table tbody tr { transition: background 0.15s, transform 0.15s; }
        .admin-table tbody tr:hover { background: #f5f3ff; }
        .btn-add, .btn-press {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff; border: none; border-radius: 8px;
            padding: 10px 20px; font-size: 0.875rem; font-weight: 600;
            display: inline-flex; align-items: center;
            transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
            box-shadow: 0 4px 10px rgba(99,102,241,0.25);
        }
        .btn-add:hover, .btn-press:hover {
            color: #fff; text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(99,102,241,0.35);
        }
        .btn-add:active, .btn-press:active { transform: translateY(0) scale(0.97); }
        .btn-action {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 6px 12px; border-radius: 6px; font-size: 0.78rem;
            font-weight: 600; border: none; cursor: pointer; text-decoration: none;
            transition: transform 0.15s, box-shadow 0.2s, filter 0.15s;
        }
        .btn-action:hover { text-decoration: none; transform: translateY(-1px); filter: brightness(0.97); }
        .btn-action:active { transform: scale(0.96); }
        .btn-view { background: #dbeafe; color: #2563eb; }
        .btn-edit { background: #ede9fe; color: #7c3aed; }
        .btn-delete { background: #fee2e2; color: #dc2626; }
        .form-section {
            background: #fff; border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            margin-bottom: 24px; overflow: hidden;
            transition: box-shadow 0.2s;
        }
        .form-section:hover { box-shadow: 0 8px 24px rgba(15,23,42,0.08); }
        .form-section-header { padding: 16px 24px; border-bottom: 1px solid #e8ecf0; background: #f9fafb; }
        .form-section-header h5 { margin: 0; font-size: 0.95rem; font-weight: 700; color: #1a1a2e; }
        .form-section-body { padding: 24px; }
        .form-control { border-radius: 8px; border: 1px solid #d1d5db; padding: 10px 14px; font-size: 0.875rem; transition: border-color 0.15s, box-shadow 0.15s; }
        .form-control:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.15); }
        label { font-size: 0.85rem; font-weight: 600; color: #374151; }
        .price-original { text-decoration: line-through; color: #999; font-size: 0.9rem; }
        .price-sale { color: #c0392b; font-weight: bold; font-size: 1.1rem; }
        .alert { border-radius: 8px; }
        .badge-category { background: #ede9fe; color: #7c3aed; font-size: 0.75rem; padding: 4px 10px; border-radius: 20px; }
        .variant-tag { display: inline-block; background: #f0f2f5; color: #374151; font-size: 0.78rem; padding: 3px 10px; border-radius: 20px; margin: 2px; }
        .stat-card {
            background: #fff; border-radius: 14px; padding: 22px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: default;
        }
        .stat-card:hover { transform: translateY(-6px); box-shadow: 0 12px 28px rgba(99,102,241,0.16); }
        .stat-card:active { transform: translateY(-2px) scale(0.99); }
        .stat-value { font-size: 1.8rem; font-weight: 800; color: #1a1a2e; margin: 8px 0 0; }
        .stat-label { font-size: 0.8rem; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; }
        .stat-icon {
            width: 42px; height: 42px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center; color: #fff;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="admin-wrapper">
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h4><i class="fas fa-store mr-2"></i>Admin Panel</h4>
                <small>Quản lý cửa hàng</small>
            </div>
            <div class="sidebar-menu-label">Menu chính</div>
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Danh mục
                </a>
                <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Sản phẩm
                </a>
                <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag"></i> Đơn hàng
                </a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Người dùng
                </a>
                <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Báo cáo
                </a>
                <a href="{{ route('admin.finance.index') }}" class="{{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
                    <i class="fas fa-wallet"></i> Tài chính
                </a>
                <a href="{{ route('admin.chat.index') }}" class="{{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i> Chat
                </a>
                <a href="{{ route('welcome') }}">
                    <i class="fas fa-home"></i> Về cửa hàng
                </a>
            </nav>
        </aside>

        <div class="main-content">
            <div class="topbar">
                <p class="topbar-title">@yield('topbar-title', 'Admin Panel')</p>
                <div class="topbar-user">
                    <div class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
                    <span class="small font-weight-bold">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="mb-0">
                        @csrf
                        <button type="submit" class="btn-ghost">Đăng xuất</button>
                    </form>
                </div>
            </div>
            <div class="page-content">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    @stack('scripts')
</body>
</html>
