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

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 240px;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            color: #fff;
            flex-shrink: 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-brand h4 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
        }

        .sidebar-brand small {
            color: rgba(255,255,255,0.5);
            font-size: 0.75rem;
        }

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
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav a:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
        }

        .sidebar-nav a.active {
            color: #fff;
            background: rgba(99, 102, 241, 0.25);
            border-left-color: #6366f1;
        }

        .sidebar-nav a i {
            width: 18px;
            text-align: center;
            font-size: 0.85rem;
        }

        /* Main content */
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

        .topbar-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1a1a2e;
            margin: 0;
        }

        .topbar-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .page-content {
            padding: 28px;
            flex: 1;
        }

        /* Breadcrumb */
        .breadcrumb-custom {
            font-size: 0.82rem;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .breadcrumb-custom a {
            color: #6366f1;
            text-decoration: none;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0;
        }

        /* Cards & Tables */
        .content-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            overflow: hidden;
        }

        .content-card .card-body {
            padding: 0;
        }

        .admin-table {
            margin: 0;
        }

        .admin-table thead th {
            background: #f9fafb;
            border: none;
            border-bottom: 1px solid #e8ecf0;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #6b7280;
            padding: 14px 20px;
        }

        .admin-table tbody td {
            padding: 16px 20px;
            border-top: 1px solid #f0f2f5;
            vertical-align: middle;
            color: #374151;
            font-size: 0.9rem;
        }

        .admin-table tbody tr:hover {
            background: #fafbfc;
        }

        /* Buttons */
        .btn-add {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .btn-add:hover {
            opacity: 0.9;
            color: #fff;
            text-decoration: none;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .btn-action:hover { opacity: 0.85; text-decoration: none; }
        .btn-view { background: #dbeafe; color: #2563eb; }
        .btn-edit { background: #ede9fe; color: #7c3aed; }
        .btn-delete { background: #fee2e2; color: #dc2626; }

        /* Form sections */
        .form-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .form-section-header {
            padding: 16px 24px;
            border-bottom: 1px solid #e8ecf0;
            background: #f9fafb;
        }

        .form-section-header h5 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a2e;
        }

        .form-section-header p {
            margin: 4px 0 0;
            font-size: 0.8rem;
            color: #6b7280;
        }

        .form-section-body {
            padding: 24px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 10px 14px;
            font-size: 0.875rem;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }

        label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #374151;
        }

        .variant-row {
            background: #f9fafb;
            border: 1px solid #e8ecf0;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 12px;
        }

        .btn-remove-variant {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 0.8rem;
            cursor: pointer;
        }

        .btn-add-variant {
            background: #ede9fe;
            color: #7c3aed;
            border: none;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
        }

        .price-original {
            text-decoration: line-through;
            color: #999;
            font-size: 0.9rem;
        }

        .price-sale {
            color: #c0392b;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .voucher-badge {
            background: #e74c3c;
            color: white;
            font-size: 0.75rem;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .alert {
            border-radius: 8px;
        }

        .badge-category {
            background: #ede9fe;
            color: #7c3aed;
            font-size: 0.75rem;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .variant-tag {
            display: inline-block;
            background: #f0f2f5;
            color: #374151;
            font-size: 0.78rem;
            padding: 3px 10px;
            border-radius: 20px;
            margin: 2px;
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
                <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="fas fa-th-large"></i> Danh mục
                </a>
                <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Sản phẩm
                </a>
            </nav>
        </aside>

        <div class="main-content">
            <div class="topbar">
                <p class="topbar-title">@yield('topbar-title', 'Admin Panel')</p>
                <div class="topbar-avatar">A</div>
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
