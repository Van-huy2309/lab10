@extends('layouts.admin')

@section('title', 'Dashboard')
@section('topbar-title', 'Bảng điều khiển')

@section('content')
<div class="breadcrumb-custom">Quản trị / Dashboard</div>
<div class="page-header">
    <h1>Tổng quan</h1>
    <a class="btn-add" href="{{ route('admin.products.create') }}"><i class="fas fa-plus mr-1"></i> Thêm sản phẩm</a>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Sản phẩm</div>
                    <p class="stat-value">{{ $stats['products'] }}</p>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);"><i class="fas fa-box"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Đang bán</div>
                    <p class="stat-value">{{ $stats['active_products'] }}</p>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#10b981,#34d399);"><i class="fas fa-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Danh mục</div>
                    <p class="stat-value">{{ $stats['categories'] }}</p>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#f97316);"><i class="fas fa-th-large"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Khách hàng</div>
                    <p class="stat-value">{{ $stats['users'] }}</p>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#ec4899,#f43f5e);"><i class="fas fa-users"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Đơn hàng</div>
                        <p class="stat-value">{{ $stats['orders'] }}</p>
                    </div>
                    <div class="stat-icon" style="background:linear-gradient(135deg,#0ea5e9,#38bdf8);"><i class="fas fa-shopping-bag"></i></div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 mb-4">
        <a href="{{ route('admin.orders.index', ['tab' => 'pending']) }}" class="text-decoration-none">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label">Chờ xử lý</div>
                        <p class="stat-value">{{ $stats['pending_orders'] }}</p>
                    </div>
                    <div class="stat-icon" style="background:linear-gradient(135deg,#f97316,#fb923c);"><i class="fas fa-clock"></i></div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row">
<div class="col-lg-6 mb-4">
<div class="content-card">
    <div class="form-section-header"><h5>Sản phẩm mới nhất</h5></div>
    <table class="table admin-table">
        <thead>
            <tr>
                <th>Tên</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($latestProducts as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td><span class="badge-category">{{ $product->category->name ?? '—' }}</span></td>
                    <td>{{ $product->formattedOriginalPrice() }}</td>
                    <td>
                        <a class="btn-action btn-view" href="{{ route('admin.products.show', $product) }}">Xem</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Chưa có sản phẩm.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
<div class="col-lg-6 mb-4">
<div class="content-card">
    <div class="form-section-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Đơn hàng mới</h5>
        <a href="{{ route('admin.orders.index') }}" class="small">Xem tất cả</a>
    </div>
    <table class="table admin-table">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Khách</th>
                <th>Tổng</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($latestOrders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->name }}</td>
                    <td>{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                    <td>
                        <a class="btn-action btn-view" href="{{ route('admin.orders.show', $order) }}">Xem</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Chưa có đơn hàng.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
</div>
@endsection
