@extends('layouts.shop')

@section('title', 'Đăng nhập')

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <h2>Đăng nhập</h2>
        <p class="sub">Chào mừng trở lại Trang Sức Của Huy</p>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ url('login') }}" method="POST">
            @csrf
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
            <button type="submit" class="btn-auth">Đăng nhập</button>
        </form>
        <p class="auth-link">Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a></p>
    </div>
</div>
@endsection
