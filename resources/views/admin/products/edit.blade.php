@extends('layouts.admin')

@section('title', 'Sửa sản phẩm')
@section('topbar-title', 'Bảng Quản Trị')

@section('content')
<div class="product-form-page">
    <div class="product-form-header">
        <h1>Sửa Sản Phẩm</h1>
        <a href="{{ route('admin.products.index') }}" class="back-link">← Quay lại</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.products._form', ['product' => $product])
    </form>
</div>
@endsection

@include('admin.products._form-assets', ['colorIndex' => max(count(old('variants', [])), $product->variants->count(), 1)])
