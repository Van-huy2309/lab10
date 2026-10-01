@extends('layouts.admin')

@section('title', 'Quản lý người dùng')
@section('topbar-title', 'Quản lý người dùng')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản trị</a> / Người dùng
</div>
<div class="page-header">
    <h1>Người dùng</h1>
    <a href="{{ route('admin.users.create') }}" class="btn-add">
        <i class="fas fa-plus mr-1"></i> Thêm người dùng
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="content-card">
    <table class="table admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>#{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->role === 'admin')
                            <span class="badge badge-danger">Quản trị</span>
                        @else
                            <span class="badge-category">Khách hàng</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn-action btn-view"><i class="fas fa-eye"></i> Xem</a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-action btn-edit"><i class="fas fa-pen"></i> Sửa</a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Bạn chắc chắn xóa?')">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Không có người dùng nào.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
