@extends('layouts.shop')

@section('title', 'Trang Sức Của Huy')

@section('content')
<div class="shop-hero">
    <h1 class="mb-2" style="font-weight:800;">Chào mừng đến cửa hàng</h1>
    <p class="mb-0" style="opacity:.9;">Khám phá sản phẩm theo danh mục, màu sắc và giá bán.</p>
</div>

<form method="GET" action="{{ route('welcome') }}" class="mb-4">
    <div class="form-row align-items-end">
        <div class="col-md-4">
            <label class="small font-weight-bold">Danh mục</label>
            <select name="category_id" class="form-control">
                <option value="">Tất cả sản phẩm</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn-shop" type="submit">Lọc</button>
        </div>
    </div>
</form>

<div class="product-grid">
    @forelse ($products as $product)
        <div>
            <div class="product-card card">
                @if ($product->imageUrl())
                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}">
                @else
                    <div class="product-thumb d-flex align-items-center justify-content-center text-muted">
                        <i class="fas fa-image fa-2x"></i>
                    </div>
                @endif
                <div class="card-body">
                    <span class="badge-category" style="background:#ede9fe;color:#7c3aed;font-size:.72rem;padding:3px 8px;border-radius:20px;">
                        {{ $product->category->name ?? '—' }}
                    </span>
                    <h5 class="mt-2 mb-1" style="font-weight:700;font-size:1rem;">{{ $product->name }}</h5>
                    <p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($product->description, 70) }}</p>
                    <div class="price-tag mb-3">{{ $product->formattedSalePrice() }}</div>
                    <a href="{{ route('products.show', $product) }}" class="btn-shop btn-block justify-content-center">Xem chi tiết</a>
                </div>
            </div>
        </div>
    @empty
        <div>
            <div class="alert alert-light border">Chưa có sản phẩm đang bán.</div>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $products->links() }}
</div>
@endsection
