@extends('layouts.admin')

@section('title', 'Giao dịch thanh toán')
@section('topbar-title', 'Giao dịch thanh toán')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản trị</a> /
    <a href="{{ route('admin.finance.index') }}">Tài chính</a> / Giao dịch
</div>
<div class="page-header">
    <h1>Danh sách giao dịch</h1>
</div>

<nav class="nav nav-pills my-3" aria-label="Finance">
    <a class="nav-link" href="{{ route('admin.finance.index', request()->query()) }}">Thống kê</a>
    <a class="nav-link active" aria-current="page" href="{{ route('admin.finance.transactions', request()->query()) }}">Giao dịch</a>
</nav>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form method="GET" action="{{ route('admin.finance.transactions') }}" class="content-card mb-4">
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
            <div class="col-md-2 mb-2">
                <label>Sắp xếp</label>
                <select name="sort" class="form-control">
                    @foreach (['newest' => 'Mới nhất', 'oldest' => 'Cũ nhất', 'amount_desc' => 'Tiền cao → thấp', 'amount_asc' => 'Tiền thấp → cao'] as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['sort'] ?? 'newest') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary btn-block btn-press">Lọc</button>
            </div>
        </div>
    </div>
</form>

<div class="content-card">
    <table class="table admin-table">
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Người nhận</th>
                <th>Tổng tiền</th>
                <th>Phương thức</th>
                <th>TT thanh toán</th>
                <th>Ngày tạo</th>
                <th>Cập nhật COD</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                @php
                    $isCod = ($order->gateway ?? '') === 'cod';
                    $currentPay = $order->payment_status ?? 'pending';
                    $allowed = $codTransitions[$currentPay] ?? [];
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}"><strong>#{{ $order->id }}</strong></a>
                    </td>
                    <td>
                        {{ $order->name }}
                        <div class="small text-muted">{{ $order->phone }}</div>
                    </td>
                    <td><strong>{{ number_format($order->total_price, 0, ',', '.') }} đ</strong></td>
                    <td>{{ $methods[$order->gateway] ?? strtoupper($order->gateway ?? '—') }}</td>
                    <td>
                        <span class="badge badge-{{ $currentPay === 'paid' ? 'success' : ($currentPay === 'cancelled' ? 'secondary' : 'warning') }}">
                            {{ $statuses[$currentPay] ?? $currentPay }}
                        </span>
                        @if ($order->paid_at)
                            <div class="small text-muted">{{ \Carbon\Carbon::parse($order->paid_at)->format('d/m/Y H:i') }}</div>
                        @endif
                    </td>
                    <td class="small">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        @if ($isCod && count($allowed) > 1)
                            <form action="{{ route('admin.finance.update-status', $order->id) }}" method="POST" class="d-flex align-items-center" style="gap:6px;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="current_payment_status" value="{{ $currentPay }}">
                                <input type="hidden" name="current_order_status" value="{{ $order->status }}">
                                <input type="hidden" name="current_payment_id" value="{{ (int) ($order->payment_id ?? 0) }}">
                                <select name="payment_status" class="form-control form-control-sm" style="min-width:140px;">
                                    @foreach ($allowed as $st)
                                        <option value="{{ $st }}" @selected($st === $currentPay)>{{ $statuses[$st] ?? $st }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                            </form>
                        @elseif ($isCod)
                            <span class="small text-muted">Không đổi được</span>
                        @else
                            <span class="small text-muted">Chỉ COD</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Không có giao dịch.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $orders->links() }}</div>
@endsection
