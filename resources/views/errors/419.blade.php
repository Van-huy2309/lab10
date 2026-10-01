@extends('layouts.shop')

@section('title', 'Phiên hết hạn')

@section('content')
<div class="checkout-card" style="max-width:560px;margin:40px auto;text-align:center;">
    <h1 style="font-weight:800;margin-bottom:12px;">Phiên làm việc hết hạn</h1>
    <p class="text-muted" style="margin-bottom:20px;">
        Form đã cũ hoặc cookie phiên không còn hợp lệ. Hãy tải lại trang rồi thêm sản phẩm vào giỏ hàng.
    </p>
    <a class="btn-shop" href="{{ url()->previous() !== url()->current() ? url()->previous() : route('welcome') }}">Quay lại</a>
</div>
@endsection
