@extends('layouts.admin')

@section('title', $product->name)
@section('topbar-title', 'Chi Tiết Sản Phẩm')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản lý</a> /
    <a href="{{ route('admin.products.index') }}">Sản phẩm</a> / {{ $product->name }}
</div>

<div class="page-header">
    <div>
        <span class="badge-category">{{ $product->category->name }}</span>
        @if (!$product->is_active)
            <span class="badge badge-secondary ml-1">Ẩn</span>
        @endif
        <h1>{{ $product->name }}</h1>
    </div>
    <div>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.products.index') }}">Quay lại</a>
        <a class="btn-action btn-edit" href="{{ route('admin.products.edit', $product) }}">
            <i class="fas fa-pen"></i> Sửa
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-5 mb-4">
        <div class="form-section">
            <div class="form-section-header"><h5>Ảnh sản phẩm</h5></div>
            <div class="form-section-body text-center">
                @if ($product->imageUrl())
                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height:320px;">
                @else
                    <div class="text-muted py-5">Chưa có ảnh sản phẩm</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="form-section">
            <div class="form-section-header"><h5>Thông tin cơ bản</h5></div>
            <div class="form-section-body">
                @if ($product->description)
                    <p class="text-muted">{{ $product->description }}</p>
                    <hr>
                @endif
                <table class="table table-bordered">
                    <tr><th style="width:40%">Giá gốc</th><td>{{ $product->formattedOriginalPrice() }}</td></tr>
                    <tr><th>Trạng thái</th><td>{{ $product->is_active ? 'Đang bán' : 'Ẩn' }}</td></tr>
                </table>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-header"><h5>Phân màu sản phẩm</h5></div>
            <div class="form-section-body">
                @if ($product->variants->count())
                    <table class="table admin-table">
                        <thead>
                            <tr>
                                <th>Màu</th>
                                <th>Hex</th>
                                <th>Giá riêng</th>
                                <th>Tồn kho</th>
                                <th>Ảnh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product->variants as $variant)
                                <tr>
                                    <td>{{ $variant->color }}</td>
                                    <td>
                                        <span style="display:inline-block;width:16px;height:16px;border-radius:4px;background:{{ $variant->color_hex }};vertical-align:middle;"></span>
                                        {{ $variant->color_hex }}
                                    </td>
                                    <td>{{ $variant->price ? number_format($variant->price, 0, ',', '.') . ' đ' : '—' }}</td>
                                    <td>{{ $variant->stock }}</td>
                                    <td>
                                        @if ($variant->imageUrl())
                                            <img src="{{ $variant->imageUrl() }}" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                                        @else — @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted mb-0">Chưa có màu nào.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
