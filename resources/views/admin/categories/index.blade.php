@extends('layouts.admin')

@section('title', 'Danh sách danh mục')
@section('topbar-title', 'Danh Sách Danh Mục')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản lý</a> / Danh mục
</div>

<div class="page-header">
    <h1>Danh Mục</h1>
    <a class="btn-add" href="{{ route('admin.categories.create') }}">
        <i class="fas fa-plus mr-1"></i> Thêm danh mục
    </a>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">{{ $message }}</div>
@endif

<div class="content-card">
    <div class="card-body">
        <table class="table admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th>Số sản phẩm</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>#{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td>
                            <a class="btn-action btn-view" href="{{ route('admin.categories.show', $category) }}">
                                <i class="fas fa-eye"></i> Xem
                            </a>
                            <a class="btn-action btn-edit" href="{{ route('admin.categories.edit', $category) }}">
                                <i class="fas fa-pen"></i> Sửa
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" onclick="return confirm('Xóa danh mục này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Chưa có danh mục nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
