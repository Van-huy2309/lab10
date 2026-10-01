@extends('layouts.shop')

@section('title', $product->name)

@section('content')
<div class="mb-3">
    <a href="{{ route('welcome') }}" class="btn-outline-shop">← Về danh sách</a>
</div>

<div class="row">
    <div class="col-md-5 mb-4">
        <div class="product-card">
            @if ($product->imageUrl())
                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="height:360px;">
            @else
                <div class="product-thumb d-flex align-items-center justify-content-center text-muted" style="height:360px;">
                    Chưa có ảnh
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-7">
        <span class="badge-category" style="background:#ede9fe;color:#7c3aed;padding:4px 10px;border-radius:20px;font-size:.8rem;">
            {{ $product->category->name }}
        </span>
        <h1 class="mt-2" style="font-weight:800;">{{ $product->name }}</h1>
        <p class="text-muted">{{ $product->description }}</p>
        <p class="price-tag" style="font-size:1.5rem;">{{ $product->formattedSalePrice() }}</p>

        <form action="{{ route('user.cart.add', $product) }}" method="POST" class="add-cart-form">
            @csrf
            @if ($product->variants->count())
                <h6 class="font-weight-bold mt-4">Chọn màu sắc</h6>
                <div class="variant-pick">
                    @foreach ($product->variants as $variant)
                        <label class="variant-option">
                            <input type="radio" name="variant_id" value="{{ $variant->id }}" {{ $loop->first ? 'checked' : '' }} required>
                            <span>
                                <span class="color-dot" style="background:{{ $variant->color_hex }}"></span>
                                {{ $variant->color }}
                                @if ($variant->price)
                                    · {{ number_format($variant->price, 0, ',', '.') }} đ
                                @endif
                            </span>
                        </label>
                    @endforeach
                </div>
            @endif
            <div class="field-group" style="max-width:160px;margin-top:16px;">
                <label class="field-label">Số lượng</label>
                <input type="number" name="quantity" value="1" min="1" max="20" class="field-input">
            </div>
            <button type="submit" class="btn-shop" style="margin-top:8px;">Thêm vào giỏ hàng</button>
        </form>
    </div>
</div>
@endsection
