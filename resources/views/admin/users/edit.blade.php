@extends('layouts.admin')

@section('title', 'Sửa người dùng')
@section('topbar-title', 'Sửa người dùng')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản trị</a> /
    <a href="{{ route('admin.users.index') }}">Người dùng</a> / Sửa
</div>
<div class="page-header"><h1>Chỉnh sửa người dùng</h1></div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="form-section" style="max-width:560px;">
    <div class="form-section-body">
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Tên</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Mật khẩu mới <span class="text-muted font-weight-normal">(để trống nếu giữ nguyên)</span></label>
                <input type="password" name="password" class="form-control" minlength="6">
            </div>
            <div class="form-group">
                <label>Vai trò</label>
                <select name="role" class="form-control" required>
                    <option value="customer" @selected(old('role', $user->role) === 'customer')>Khách hàng</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Quản trị</option>
                </select>
            </div>
            <button type="submit" class="btn-press btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary ml-2">Hủy</a>
        </form>
    </div>
</div>
@endsection
