@extends('layouts.app')

@section('title', 'Thêm danh mục')
@section('topbar-title', 'Thêm Danh Mục')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('categories.index') }}">Quản lý</a> / <a href="{{ route('categories.index') }}">Danh mục</a> / Thêm mới
</div>

<div class="page-header">
    <h1>Thêm Danh Mục</h1>
    <a class="btn btn-secondary btn-sm" href="{{ route('categories.index') }}">Quay lại</a>
</div>

<div class="form-section">
    <div class="form-section-header">
        <h5>Thông tin danh mục</h5>
    </div>
    <div class="form-section-body">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Tên danh mục <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" placeholder="Nhập tên danh mục" value="{{ old('name') }}" required>
            </div>
            <button type="submit" class="btn-add">Lưu danh mục</button>
        </form>
    </div>
</div>
@endsection
