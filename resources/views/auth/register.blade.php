@extends('layouts.shop')

@section('title', 'Đăng ký')

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <h2>Tạo tài khoản</h2>
        <p class="sub">Đăng ký xong hãy xác thực email trước khi dùng cửa hàng</p>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ url('register') }}" method="POST">
            @csrf
            <div class="field-group">
                <label class="field-label" for="name">Họ tên</label>
                <input type="text" name="name" id="name" class="field-input" value="{{ old('name') }}" required>
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="email">Email</label>
                <input type="email" name="email" id="email" class="field-input" value="{{ old('email') }}" required>
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="password">Mật khẩu</label>
                <input type="password" name="password" id="password" class="field-input" required>
                @error('password')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="field-input" required>
            </div>
            <button type="submit" class="btn-auth">Đăng ký</button>
        </form>
        <p class="auth-link">Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
    </div>
</div>
@endsection
