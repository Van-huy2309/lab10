@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')
@section('topbar-title', 'Quản lý đơn hàng')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản trị</a> / Đơn hàng
</div>
<div class="page-header">
    <h1>Đơn hàng trang sức</h1>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="mb-3 d-flex flex-wrap align-items-center justify-content-between" style="gap:8px;">
    <div class="d-flex flex-wrap" style="gap:8px;">
        @foreach ($tabs as $key => $tab)
            <a href="{{ route('admin.orders.index', array_merge(request()->except('page'), ['tab' => $key])) }}"
               class="btn btn-sm {{ $activeTab === $key ? 'btn-primary' : 'btn-outline-secondary' }}">
                {{ $tab['label'] }}
                <span class="badge badge-light ml-1">{{ $tab['count'] }}</span>
            </a>
        @endforeach
    </div>
    <div class="d-flex align-items-center" style="gap:10px;">
        <span class="small font-weight-bold text-muted mb-0">Bộ lọc</span>
        <label class="filter-toggle mb-0" title="Bật/tắt bộ lọc tìm kiếm">
            <input type="checkbox" id="filterToggle" checked>
            <span class="filter-slider"></span>
        </label>
    </div>
</div>

<style>
    .filter-toggle { position: relative; display: inline-block; width: 48px; height: 26px; cursor: pointer; }
    .filter-toggle input { opacity: 0; width: 0; height: 0; }
    .filter-slider {
        position: absolute; inset: 0; background: #cbd5e1; border-radius: 999px; transition: .2s;
    }
    .filter-slider:before {
        content: ''; position: absolute; height: 20px; width: 20px; left: 3px; top: 3px;
        background: #fff; border-radius: 50%; transition: .2s; box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }
    .filter-toggle input:checked + .filter-slider { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
    .filter-toggle input:checked + .filter-slider:before { transform: translateX(22px); }
    #ordersFilterCard.is-hidden { display: none !important; }
</style>

<form method="GET" action="{{ route('admin.orders.index') }}" class="content-card mb-4" id="ordersFilterCard">
    <div class="form-section-body">
        <input type="hidden" name="tab" value="{{ $activeTab }}">
        <div class="form-row">
            <div class="col-md-3 mb-2">
                <label>Tìm kiếm</label>
                <input type="text" name="search" class="form-control" value="{{ $filters['search'] ?? '' }}" placeholder="Mã ĐH, SĐT, tên, GHN…">
            </div>
            <div class="col-md-2 mb-2">
                <label>Trạng thái đơn</label>
                <select name="status" class="form-control">
                    <option value="">-- Tất cả --</option>
                    @foreach (['pending','paid','paid_momo','cod_ordered','cod_paid','cancelled'] as $st)
                        <option value="{{ $st }}" @selected(($filters['status'] ?? '') === $st)>{{ $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label>Thanh toán</label>
                <select name="payment_status" class="form-control">
                    <option value="">-- Tất cả --</option>
                    @foreach ($paymentLabels as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['payment_status'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label>Vận chuyển</label>
                <select name="shipping_status" class="form-control">
                    <option value="">-- Tất cả --</option>
                    @foreach ($shippingLabels as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['shipping_status'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label>Cổng TT</label>
                <select name="gateway" class="form-control">
                    <option value="">-- Tất cả --</option>
                    @foreach (['cod' => 'COD', 'momo' => 'MoMo', 'unknown' => 'Không rõ'] as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['gateway'] ?? '') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
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
                <label>Sắp xếp</label>
                <select name="sort" class="form-control">
                    @foreach (['newest' => 'Mới nhất', 'oldest' => 'Cũ nhất', 'amount_desc' => 'Giá cao → thấp', 'amount_asc' => 'Giá thấp → cao'] as $key => $label)
                        <option value="{{ $key }}" @selected(($filters['sort'] ?? 'newest') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2">
                <label>Mỗi trang</label>
                <select name="per_page" class="form-control">
                    @foreach ([25, 50, 100] as $n)
                        <option value="{{ $n }}" @selected((int)($filters['per_page'] ?? 25) === $n)>{{ $n }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 mb-2 d-flex align-items-end">
                <button type="submit" class="btn-press btn btn-primary btn-block">Lọc</button>
            </div>
        </div>
    </div>
</form>

<div class="content-card">
    <table class="table admin-table">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Khách / SĐT</th>
                <th>Sản phẩm</th>
                <th>Tổng tiền</th>
                <th>TT / Ship</th>
                <th>Ngày</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>
                        <strong>#{{ $order->id }}</strong>
                        @if ($order->ghn_order_code)
                            <div class="small text-muted">{{ $order->ghn_order_code }}</div>
                        @endif
                    </td>
                    <td>
                        {{ $order->name }}
                        <div class="small text-muted">{{ $order->phone }}</div>
                    </td>
                    <td>
                        @foreach ($order->items->take(2) as $item)
                            <div class="small">{{ $item->product->name ?? 'SP' }} ×{{ $item->quantity }}</div>
                        @endforeach
                        @if ($order->items->count() > 2)
                            <div class="small text-muted">+{{ $order->items->count() - 2 }} SP khác</div>
                        @endif
                    </td>
                    <td><strong>{{ number_format($order->total_price, 0, ',', '.') }} đ</strong></td>
                    <td>
                        <span class="badge badge-secondary">{{ $order->status }}</span>
                        <div class="small mt-1">{{ $shippingLabels[$order->shipping_status] ?? $order->shipping_status }}</div>
                        <div class="small text-muted">{{ strtoupper($order->gateway ?? '—') }} · {{ $paymentLabels[$order->payment_status] ?? ($order->payment_status ?? '—') }}</div>
                    </td>
                    <td class="small">{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                    <td>
                        <a class="btn-action btn-view" href="{{ route('admin.orders.show', $order->id) }}">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Không có đơn hàng.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $orders->links() }}</div>

<script>
(function () {
    const toggle = document.getElementById('filterToggle');
    const card = document.getElementById('ordersFilterCard');
    if (!toggle || !card) return;
    const key = 'admin_orders_filter_open';
    const saved = localStorage.getItem(key);
    const isOpen = saved === null ? true : saved === '1';
    toggle.checked = isOpen;
    card.classList.toggle('is-hidden', !isOpen);
    toggle.addEventListener('change', function () {
        card.classList.toggle('is-hidden', !toggle.checked);
        localStorage.setItem(key, toggle.checked ? '1' : '0');
    });
})();
</script>
@endsection
