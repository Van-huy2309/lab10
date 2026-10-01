@extends('layouts.admin')

@section('title', 'Chi tiết danh mục')
@section('topbar-title', 'Chi Tiết Danh Mục')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản lý</a> /
    <a href="{{ route('admin.categories.index') }}">Danh mục</a> / {{ $category->name }}
</div>

<div class="page-header">
    <h1>{{ $category->name }}</h1>
    <div>
        <a class="btn btn-secondary btn-sm" href="{{ route('admin.categories.index') }}">Quay lại</a>
        <a class="btn-action btn-edit" href="{{ route('admin.categories.edit', $category) }}">
            <i class="fas fa-pen"></i> Sửa
        </a>
    </div>
</div>

<div class="form-section">
    <div class="form-section-header">
        <h5>Thông tin danh mục</h5>
    </div>
    <div class="form-section-body">
        <table class="table table-bordered w-auto">
            <tr>
                <th>ID</th>
                <td>#{{ $category->id }}</td>
            </tr>
            <tr>
                <th>Tên danh mục</th>
                <td>{{ $category->name }}</td>
            </tr>
            <tr>
                <th>Số sản phẩm</th>
                <td>{{ $category->products->count() }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
