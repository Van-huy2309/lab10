@extends('layouts.shop')

@section('title', 'Lịch sử đơn hàng')

@section('content')
<h1 style="font-weight:800;margin-bottom:24px;">Lịch sử đơn hàng</h1>

@if (session('warning'))
    <div class="alert-danger" style="background:#fff7ed;border-color:#fdba74;color:#9a3412;">{{ session('warning') }}</div>
@endif

@forelse ($orders as $order)
    <div class="checkout-card" style="margin-bottom:16px;">
        <div class="order-line">
            <div>
                <strong>Đơn #{{ $order->id }}</strong>
                <div class="small text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div>
                <span class="status-pill">{{ $order->status }}</span>
                <span class="status-pill">{{ $order->shipping_status }}</span>
            </div>
        </div>
        <div class="order-line">
            <span>Tổng tiền</span>
            <strong>{{ number_format($order->total_price, 0, ',', '.') }} đ</strong>
        </div>
        @if ($order->ghn_order_code)
            <div class="order-line"><span>Mã GHN</span><strong>{{ $order->ghn_order_code }}</strong></div>
        @endif
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:12px;">
            <a class="btn-shop" href="{{ route('user.orders.show', $order) }}">Chi tiết</a>
            @if ($order->canPayAgain())
                <a class="btn-shop" href="{{ route('user.orders.momo.pay', $order) }}">Thanh toán lại MoMo</a>
            @endif
            @if (in_array($order->shipping_status, ['pending', 'ready_to_pick', 'not_shipped']))
                <form action="{{ route('user.orders.cancel', $order) }}" method="POST">
                    @csrf
                    <button class="btn-nav-outline" type="submit">Hủy đơn</button>
                </form>
            @endif
        </div>
    </div>
@empty
    <div class="empty-state">
        <h3>Chưa có đơn hàng</h3>
        <a href="{{ route('products.index') }}" class="btn-shop" style="margin-top:16px;">Mua sắm ngay</a>
    </div>
@endforelse

<div class="d-flex justify-content-center">{{ $orders->links() }}</div>
@endsection
