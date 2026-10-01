@extends('layouts.admin')

@section('title', 'Báo cáo doanh thu')
@section('topbar-title', 'Báo cáo doanh thu')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản trị</a> / Báo cáo
</div>
<div class="page-header">
    <h1>Báo cáo doanh thu</h1>
</div>

<nav class="nav nav-pills my-3" aria-label="Báo cáo">
    <a class="nav-link active" aria-current="page" href="{{ route('admin.reports.index') }}">Bảng số liệu</a>
    <a class="nav-link" href="{{ route('admin.reports.charts') }}">Biểu đồ</a>
</nav>

<p class="text-muted">Doanh thu tính theo ngày tạo đơn, chỉ gồm đơn đã thanh toán, chưa hoàn tiền và không bị hủy hoặc hoàn hàng.</p>

<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stat-card h-100">
            <div class="stat-label">Tổng số đơn hàng</div>
            <p class="stat-value">{{ number_format($totalOrders) }}</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-card h-100">
            <div class="stat-label">Tổng số khách hàng</div>
            <p class="stat-value">{{ number_format($totalCustomers) }}</p>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="stat-card h-100">
            <div class="stat-label">Tổng doanh thu (gồm phí ship)</div>
            <p class="stat-value" style="color:#059669;">{{ number_format($totalRevenue, 0, ',', '.') }} đ</p>
        </div>
    </div>
</div>

<div class="content-card mb-4">
    <div class="form-section-header">
        <h5>Doanh thu theo danh mục</h5>
        <div class="small text-muted">Tính theo giá sản phẩm khi đặt hàng, không gồm phí vận chuyển.</div>
    </div>
    <table class="table admin-table">
        <thead>
            <tr>
                <th>Danh mục</th>
                <th class="text-right">Số lượng bán</th>
                <th class="text-right">Doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categoryRevenue as $revenue)
                <tr>
                    <td>{{ $revenue->category_name ?? ('Danh mục #'.$revenue->category_id) }}</td>
                    <td class="text-right">{{ number_format($revenue->total_qty) }}</td>
                    <td class="text-right">{{ number_format($revenue->total_revenue, 0, ',', '.') }} đ</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center text-muted py-4">Chưa có doanh thu.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@foreach ([
    ['Doanh thu theo ngày', 'Ngày', 'date', $revenueByDate, 'd/m/Y'],
    ['Doanh thu theo tháng', 'Tháng', 'month', $revenueByMonth, 'm/Y'],
    ['Doanh thu theo năm', 'Năm', 'year', $revenueByYear, null],
] as [$title, $label, $field, $rows, $format])
    <div class="content-card mb-4">
        <div class="form-section-header"><h5>{{ $title }}</h5></div>
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>{{ $label }}</th>
                    <th class="text-right">Số đơn đã thanh toán</th>
                    <th class="text-right">Doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $revenue)
                    <tr>
                        <td>
                            @if ($format)
                                {{ \Carbon\Carbon::parse($revenue->{$field}.($field === 'month' ? '-01' : ''))->format($format) }}
                            @else
                                {{ $revenue->{$field} }}
                            @endif
                        </td>
                        <td class="text-right">{{ number_format($revenue->order_count) }}</td>
                        <td class="text-right">{{ number_format($revenue->total_revenue, 0, ',', '.') }} đ</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">Chưa có doanh thu.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endforeach
@endsection
