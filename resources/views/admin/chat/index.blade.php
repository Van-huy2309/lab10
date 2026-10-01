@extends('layouts.admin')

@section('title', 'Chat khách hàng')
@section('topbar-title', 'Chat với khách hàng')

@section('content')
<div class="breadcrumb-custom">
    <a href="{{ route('admin.dashboard') }}">Quản trị</a> / Chat
</div>
<div class="page-header">
    <h1>Hội thoại khách hàng</h1>
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
                <th>Khách hàng</th>
                <th>Tin gần nhất</th>
                <th>Chưa đọc</th>
                <th>Thời gian</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($threads as $thread)
                <tr>
                    <td>
                        <strong>{{ $thread->name }}</strong>
                        <div class="small text-muted">{{ $thread->email }}</div>
                    </td>
                    <td style="max-width:320px;">
                        <div class="text-truncate" style="max-width:300px;">{{ $thread->last_message_body }}</div>
                    </td>
                    <td>
                        @if ($thread->unread_count > 0)
                            <span class="badge badge-danger">{{ $thread->unread_count }}</span>
                        @else
                            <span class="text-muted">0</span>
                        @endif
                    </td>
                    <td class="small">
                        {{ $thread->last_message_at ? \Carbon\Carbon::parse($thread->last_message_at)->format('d/m/Y H:i') : '—' }}
                    </td>
                    <td>
                        <a href="{{ route('admin.chat.show', $thread) }}" class="btn-action btn-view">
                            <i class="fas fa-comments"></i> Mở chat
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Chưa có khách nào nhắn tin.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $threads->links() }}</div>
@endsection
