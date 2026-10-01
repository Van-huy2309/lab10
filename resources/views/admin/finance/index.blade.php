@extends('layouts.admin')

@section('title', 'Tài chính')
@section('topbar-title', 'Tài chính')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản trị</a> / Tài chính
</div>
<div class="page-header">
    <h1>Thống kê tài chính</h1>
    <a href="{{ route('admin.finance.export', request()->query()) }}" class="btn-add">
        <i class="fas fa-download mr-1"></i> Xuất CSV
    </a>
</div>

<nav class="nav nav-pills my-3" aria-label="Finance">
    <a class="nav-link active" aria-current="page" href="{{ route('admin.finance.index', request()->query()) }}">Thống kê</a>
    <a class="nav-link" href="{{ route('admin.finance.transactions', request()->query()) }}">Giao dịch</a>
</nav>

<p class="text-muted">Mỗi đơn chỉ tính một lần (ưu tiên giao dịch đã thu / chờ hoàn / đã hoàn). Lọc theo ngày tạo đơn.</p>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="GET" action="{{ route('admin.finance.index') }}" class="content-card mb-4">
    <div class="form-section-body">
        <div class="form-row">
            <div class="col-md-3 mb-2">
                <label>Tìm kiếm</label>
                <input type="text" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="Mã ĐH, tên, SĐT…">
            </div>
            <div class="col-md-2 mb-2">
                <label>Từ ngày</label>
                <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] ?? '' }}">
            </div>
            <div class="col-md-2 mb-2">
                <label>Đến ngày</label>
                <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] ?? '' }}">
            </div>
            <div class="col-md-2 mb-2">
                <label>Số tiền từ</label>
                <input type="number" step="0.01" min="0" name="min_amount" class="form-control" value="{{ $filters['min_amount'] ?? '' }}">
            </div>
            <div class="col-md-2 mb-2">
                <label>Số tiền đến</label>
                <input type="number" step="0.01" min="0" name="max_amount" class="form-control" value="{{ $filters['max_amount'] ?? '' }}">
            </div>
            <div class="col-md-2 mb-2">
                <label>Phương thức</label>
                <select name="gateway" class="form-control">
                    <option value="">-- Tất cả --</option>
                    @foreach ($methods as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['gateway'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label>TT thanh toán</label>
                <select name="payment_status" class="form-control">
                    <option value="">-- Tất cả --</option>
                    @foreach ($statuses as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['payment_status'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-block btn-press">Lọc</button>
            </div>
            <div class="col-md-2 mb-2 d-flex align-items-end">
                <a href="{{ route('admin.finance.index') }}" class="btn btn-secondary btn-block">Xóa lọc</a>
            </div>
        </div>
    </div>
</form>

<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="stat-card h-100">
            <div class="stat-label">Số đơn (theo bộ lọc)</div>
            <p class="stat-value">{{ number_format($summary->order_count ?? 0) }}</p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="stat-card h-100">
            <div class="stat-label">Tổng giá trị đơn</div>
            <p class="stat-value" style="color:#059669;">{{ number_format($summary->total_amount ?? 0, 0, ',', '.') }} đ</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="content-card">
            <div class="form-section-header"><h5>Theo trạng thái thanh toán</h5></div>
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Trạng thái</th>
                        <th class="text-right">Số đơn</th>
                        <th class="text-right">Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($statuses as $key => $label)
                        @php $row = $statusTotals->get($key); @endphp
                        @if ($row)
                            <tr>
                                <td>{{ $label }}</td>
                                <td class="text-right">{{ number_format($row->order_count) }}</td>
                                <td class="text-right">{{ number_format($row->total_amount, 0, ',', '.') }} đ</td>
                            </tr>
                        @endif
                    @empty
                    @endforelse
                    @if ($statusTotals->isEmpty())
                        <tr><td colspan="3" class="text-center text-muted py-4">Chưa có dữ liệu.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="content-card">
            <div class="form-section-header"><h5>Theo phương thức thanh toán</h5></div>
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th>Phương thức</th>
                        <th class="text-right">Số đơn</th>
                        <th class="text-right">Tổng giá trị</th>
                        <th class="text-right">Đã thu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($methods as $key => $label)
                        @php $row = $methodTotals->get($key); @endphp
                        @if ($row)
                            <tr>
                                <td>{{ $label }}</td>
                                <td class="text-right">{{ number_format($row->order_count) }}</td>
                                <td class="text-right">{{ number_format($row->total_amount, 0, ',', '.') }} đ</td>
                                <td class="text-right">{{ number_format($row->paid_amount ?? 0, 0, ',', '.') }} đ</td>
                            </tr>
                        @endif
                    @empty
                    @endforelse
                    @if ($methodTotals->isEmpty())
                        <tr><td colspan="4" class="text-center text-muted py-4">Chưa có dữ liệu.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
