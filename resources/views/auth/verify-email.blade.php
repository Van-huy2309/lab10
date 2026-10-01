@extends('layouts.shop')

@section('title', 'Xác thực email')

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <h2>Xác thực Email</h2>
        <p class="sub">
            Tài khoản <strong>{{ auth()->user()->email }}</strong> chưa được xác thực.
            Vui lòng mở hộp thư và bấm link trong email. Nếu chưa thấy, kiểm tra mục Spam.
        </p>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if (session('message'))
            <div class="alert-success">{{ session('message') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-auth">Gửi lại email xác thực</button>
        </form>
    </div>
</div>
@endsection
