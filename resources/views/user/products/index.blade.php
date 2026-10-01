@extends('layouts.shop')

@section('title', 'Sản phẩm')

@section('content')
<h1 class="mb-4" style="font-weight:800;">Sản phẩm</h1>

<form method="GET" action="{{ route('products.index') }}" class="mb-4">
    <div class="form-row align-items-end">
        <div class="col-md-4">
            <label class="small font-weight-bold">Danh mục</label>
            <select name="category_id" class="form-control">
                <option value="">Tất cả danh mục</option>
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
                    <h5 style="font-weight:700;font-size:1rem;">{{ $product->name }}</h5>
                    <div class="price-tag mb-3">{{ $product->formattedSalePrice() }}</div>
                    <a href="{{ route('products.show', $product) }}" class="btn-shop btn-block justify-content-center">Xem chi tiết</a>
                </div>
            </div>
        </div>
    @empty
        <div>
            <div class="alert alert-light border">Không tìm thấy sản phẩm.</div>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center">
    {{ $products->links() }}
</div>
@endsection
