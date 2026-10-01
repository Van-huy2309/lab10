@extends('layouts.admin')

@section('title', 'Chi tiết đơn #' . $order->id)
@section('topbar-title', 'Chi tiết đơn hàng')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản trị</a> /
    <a href="{{ route('admin.orders.index') }}">Đơn hàng</a> / #{{ $order->id }}
</div>
<div class="page-header">
    <h1>Đơn hàng #{{ $order->id }}</h1>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">← Danh sách</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="form-section">
            <div class="form-section-header"><h5>Thông tin giao hàng</h5></div>
            <div class="form-section-body">
                <p><strong>Khách:</strong> {{ $order->user->name ?? '—' }} ({{ $order->user->email ?? '—' }})</p>
                <p><strong>Người nhận:</strong> {{ $order->name }}</p>
                <p><strong>SĐT:</strong> {{ $order->phone }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                <p><strong>Trạng thái đơn:</strong> <span class="badge badge-info">{{ $order->status }}</span></p>
                <p><strong>Vận chuyển:</strong> {{ $shippingLabels[$order->shipping_status] ?? $order->shipping_status }}</p>
                @if ($order->ghn_order_code)
                    <p><strong>Mã GHN:</strong> {{ $order->ghn_order_code }}</p>
                @endif
                <p><strong>Phí ship:</strong> {{ number_format($order->ghn_total_fee ?? 0, 0, ',', '.') }} đ</p>
                <p><strong>Tổng tiền:</strong> <span class="price-sale">{{ number_format($order->total_price, 0, ',', '.') }} đ</span></p>
                <p class="mb-0 text-muted small">Đặt lúc {{ $order->created_at?->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="form-section">
            <div class="form-section-header"><h5>Xử lý đơn</h5></div>
            <div class="form-section-body">
                @if ($canUpdateStatus)
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PATCH')
                        <div class="form-group">
                            <label>Chuyển trạng thái vận chuyển</label>
                            <select name="shipping_status" class="form-control" required>
                                @foreach (['pending','not_shipped','processing','ready_to_pick','picking'] as $st)
                                    <option value="{{ $st }}" @selected($order->shipping_status === $st)>
                                        {{ $shippingLabels[$st] ?? $st }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-press btn btn-primary btn-sm">Cập nhật trạng thái</button>
                    </form>
                @else
                    <p class="text-muted">Đơn đang giao / đã giao / đã hủy — không đổi trạng thái thủ công.</p>
                @endif

                @if ($canCancel)
                    <form action="{{ route('admin.orders.cancel', $order) }}" method="POST" class="mb-2"
                          onsubmit="return confirm('Hủy đơn #{{ $order->id }}?')">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-times"></i> Hủy đơn hàng
                        </button>
                    </form>
                @else
                    <p class="small text-muted">Không thể hủy khi đơn đang giao hoặc đã giao thành công.</p>
                @endif

                @if ($canDelete)
                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                          onsubmit="return confirm('Xóa vĩnh viễn đơn #{{ $order->id }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash"></i> Xóa đơn hàng
                        </button>
                    </form>
                    <p class="small text-muted mt-2 mb-0">Chỉ xóa được ở: Chờ xử lý / Chờ lấy hàng / Đang lấy hàng.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-section mb-4">
    <div class="form-section-header"><h5>Sản phẩm trong đơn</h5></div>
    <table class="table admin-table">
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Màu</th>
                <th>SL</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</td>
                    <td>{{ $item->color ?? '—' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                    <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="form-section">
    <div class="form-section-header"><h5>Giao dịch thanh toán</h5></div>
    <table class="table admin-table">
        <thead>
            <tr>
                <th>Cổng</th>
                <th>Số tiền</th>
                <th>Trạng thái</th>
                <th>Thời gian</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($order->paymentTransactions as $tx)
                <tr>
                    <td>{{ strtoupper($tx->gateway) }}</td>
                    <td>{{ number_format($tx->amount, 0, ',', '.') }} đ</td>
                    <td>{{ $tx->status }}</td>
                    <td>{{ $tx->created_at?->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-3">Chưa có giao dịch.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
