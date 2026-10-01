@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')
@section('topbar-title', 'Chi tiết người dùng')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản trị</a> /
    <a href="{{ route('admin.users.index') }}">Người dùng</a> / #{{ $user->id }}
</div>
<div class="page-header"><h1>Thông tin người dùng</h1></div>

<div class="form-section" style="max-width:560px;">
    <div class="form-section-body">
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Tên:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Vai trò:</strong> {{ $user->role === 'admin' ? 'Quản trị' : 'Khách hàng' }}</p>
        <p><strong>Số đơn hàng:</strong> {{ $user->orders_count ?? $user->orders()->count() }}</p>
        <p class="mb-0"><strong>Ngày tạo:</strong> {{ $user->created_at?->format('d/m/Y H:i') }}</p>
    </div>
</div>

<a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-2">← Quay lại</a>
<a href="{{ route('admin.users.edit', $user) }}" class="btn-action btn-edit mt-2 ml-2">Sửa</a>
@endsection
