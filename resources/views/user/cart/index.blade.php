@extends('layouts.shop')

@section('title', 'Giỏ hàng')

@section('content')
<h1 style="font-weight:800;margin-bottom:24px;">Giỏ hàng</h1>

@if (empty($cart))
    <div class="empty-state">
        <i class="fas fa-shopping-bag"></i>
        <h3>Giỏ hàng đang trống</h3>
        <p>Hãy chọn trang sức bạn thích rồi thêm vào giỏ.</p>
        <a href="{{ route('products.index') }}" class="btn-shop" style="margin-top:16px;">Xem bộ sưu tập</a>
    </div>
@else
    <div class="checkout-card">
        <table class="checkout-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tạm tính</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $key => $item)
                    <tr>
                        <td>
                            <strong>{{ $item['name'] }}</strong>
                            @if (!empty($item['color']))
                                <div class="text-muted small">Màu: {{ $item['color'] }}</div>
                            @endif
                        </td>
                        <td>{{ number_format($item['price'], 0, ',', '.') }} đ</td>
                        <td>
                            <form action="{{ route('user.cart.update', $key) }}" method="POST" class="qty-form">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" min="1" max="20" value="{{ $item['quantity'] }}" class="form-control" style="width:80px;">
                                <button class="btn-shop" type="submit" style="padding:8px 12px;font-size:.8rem;">Cập nhật</button>
                            </form>
                        </td>
                        <td>{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} đ</td>
                        <td>
                            <form action="{{ route('user.cart.remove', $key) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-nav-outline" style="border-color:#dc2626;color:#dc2626;">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="checkout-summary">
            <div>Tổng tiền hàng: <strong>{{ number_format($totalPrice, 0, ',', '.') }} đ</strong></div>
            <a href="{{ route('user.payment.index') }}" class="btn-shop">Thanh toán</a>
        </div>
    </div>
@endif
@endsection
