@extends('layouts.shop')

@section('title', 'Chi tiết đơn #' . $order->id)

@section('content')
<a href="{{ route('user.orders.index') }}" class="btn-back">← Lịch sử đơn</a>
<h1 style="font-weight:800;margin-bottom:24px;">Đơn hàng #{{ $order->id }}</h1>

<div class="checkout-grid">
    <div class="checkout-card">
        <h3>Người nhận</h3>
        <p>{{ $order->name }}<br>{{ $order->phone }}<br>{{ $order->address }}</p>
        <p>Trạng thái: <strong>{{ $order->status }}</strong></p>
        <p>Vận chuyển: <strong>{{ $order->shipping_status }}</strong></p>
        @if ($order->ghn_order_code)
            <p>Mã GHN: <strong>{{ $order->ghn_order_code }}</strong></p>
        @endif
        <p>Phí ship: <strong>{{ number_format($order->ghn_total_fee, 0, ',', '.') }} đ</strong></p>
        <p>Tổng: <strong>{{ number_format($order->total_price, 0, ',', '.') }} đ</strong></p>
        @if ($order->canPayAgain())
            <a class="btn-shop" href="{{ route('user.orders.momo.pay', $order) }}">Thanh toán lại MoMo</a>
        @endif
    </div>
    <div class="checkout-card">
        <h3>Sản phẩm</h3>
        @foreach ($order->items as $item)
            <div class="order-line">
                <div>
                    {{ $item->product->name ?? 'Sản phẩm' }} x{{ $item->quantity }}
                    @if ($item->color)<div class="small text-muted">{{ $item->color }}</div>@endif
                </div>
                <strong>{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</strong>
            </div>
        @endforeach
        <h3 style="margin-top:20px;">Giao dịch</h3>
        @forelse ($order->paymentTransactions as $tx)
            <div class="order-line">
                <span>{{ strtoupper($tx->gateway) }} - {{ $tx->status }}</span>
                <span>{{ number_format($tx->amount, 0, ',', '.') }} đ</span>
            </div>
        @empty
            <p class="text-muted">Chưa có giao dịch.</p>
        @endforelse
    </div>
</div>
@endsection
