@extends('layouts.app')

@section('title', 'Danh sách sản phẩm')
@section('topbar-title', 'Danh Sách Sản Phẩm')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('products.index') }}">Quản lý</a> / Sản phẩm
</div>

<div class="page-header">
    <h1>Sản Phẩm</h1>
    <a class="btn-add" href="{{ route('products.create') }}">
        <i class="fas fa-plus mr-1"></i> Thêm sản phẩm
    </a>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">{{ $message }}</div>
@endif

<form method="GET" action="{{ route('products.index') }}" class="mb-3">
    <div class="form-row align-items-end">
        <div class="col-md-4">
            <label class="small font-weight-bold">Lọc theo danh mục</label>
            <select name="category_id" class="form-control">
                <option value="">-- Tất cả danh mục --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-block">Lọc</button>
        </div>
        @if (request('category_id'))
            <div class="col-md-2">
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-block">Xóa lọc</a>
            </div>
        @endif
    </div>
</form>

<div class="content-card">
    <div class="card-body">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Biến thể</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>#{{ $product->id }}</td>
                        <td>
                            @if ($product->imageUrl())
                                <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">
                            @else
                                <div style="width:48px;height:48px;background:#f0f2f5;border-radius:6px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-image text-muted" style="font-size:0.8rem;"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td><span class="badge-category">{{ $product->category->name }}</span></td>
                        <td>
                            @if ($product->hasVoucher())
                                <div class="price-original" style="font-size:0.78rem;">{{ $product->formattedOriginalPrice() }}</div>
                                <div class="price-sale" style="font-size:0.85rem;">{{ $product->formattedSalePrice() }}</div>
                            @else
                                <span style="font-weight:600;color:#374151;">{{ $product->formattedOriginalPrice() }}</span>
                            @endif
                        </td>
                        <td>
                            @forelse ($product->variants as $variant)
                                <span class="variant-tag">
                                    <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $variant->color_hex }};"></span>
                                    {{ $variant->color }}
                                </span>
                            @empty
                                <span class="text-muted small">—</span>
                            @endforelse
                        </td>
                        <td>
                            <a class="btn-action btn-view" href="{{ route('products.show', $product) }}">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            <a class="btn-action btn-edit" href="{{ route('products.edit', $product) }}">
                                <i class="fas fa-pen"></i> Sửa
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" onclick="return confirm('Xóa sản phẩm này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Chưa có sản phẩm nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
