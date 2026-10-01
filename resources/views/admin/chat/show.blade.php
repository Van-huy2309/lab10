@extends('layouts.admin')

@section('title', 'Chat với ' . $user->name)
@section('topbar-title', 'Chat khách hàng')

@section('content')
<style>
    .admin-chat-box {
        background: #fff; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.06);
        display: flex; flex-direction: column; min-height: 560px; overflow: hidden;
    }
    .admin-chat-head {
        padding: 16px 20px; border-bottom: 1px solid #e8ecf0; background: #f9fafb;
        display: flex; justify-content: space-between; align-items: center;
    }
    .admin-chat-messages {
        flex: 1; padding: 20px; overflow-y: auto; max-height: 440px;
        background: #f8fafc; display: flex; flex-direction: column; gap: 12px;
    }
    .admin-bubble {
        max-width: 75%; padding: 12px 14px; border-radius: 12px; font-size: .9rem;
        white-space: pre-wrap; word-break: break-word;
    }
    .admin-bubble-customer { align-self: flex-start; background: #fff; border: 1px solid #e5e7eb; }
    .admin-bubble-me {
        align-self: flex-end; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;
    }
    .admin-bubble-meta { display: block; margin-top: 6px; font-size: .72rem; opacity: .7; }
    .admin-chat-form {
        display: flex; gap: 10px; padding: 14px 16px; border-top: 1px solid #e8ecf0;
    }
    .admin-chat-form textarea {
        flex: 1; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px 12px; min-height: 52px;
    }
</style>

<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản trị</a> /
    <a href="{{ route('admin.chat.index') }}">Chat</a> / {{ $user->name }}
</div>
<div class="page-header">
    <h1>Chat với {{ $user->name }}</h1>
    <a href="{{ route('admin.chat.index') }}" class="btn btn-secondary btn-sm">← Danh sách</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="admin-chat-box">
    <div class="admin-chat-head">
        <div>
            <strong>{{ $user->name }}</strong>
            <div class="small text-muted">{{ $user->email }}</div>
        </div>
    </div>

    <div class="admin-chat-messages" id="adminChatMessages">
        @forelse ($messages as $message)
            <div class="admin-bubble {{ $message->is_from_admin ? 'admin-bubble-me' : 'admin-bubble-customer' }}">
                {{ $message->body }}
                <span class="admin-bubble-meta">
                    {{ $message->is_from_admin ? 'Admin' : $user->name }} · {{ $message->created_at->format('d/m/Y H:i') }}
                </span>
            </div>
        @empty
            <div class="text-center text-muted py-5">Chưa có tin nhắn trong hội thoại này.</div>
        @endforelse
    </div>

    <form action="{{ route('admin.chat.store', $user) }}" method="POST" class="admin-chat-form">
        @csrf
        <textarea name="body" placeholder="Nhập nội dung trả lời..." required maxlength="2000">{{ old('body') }}</textarea>
        <button type="submit" class="btn-press btn btn-primary" style="align-self:flex-end;">Gửi</button>
    </form>
</div>

<script>
    const box = document.getElementById('adminChatMessages');
    if (box) box.scrollTop = box.scrollHeight;
</script>
@endsection
