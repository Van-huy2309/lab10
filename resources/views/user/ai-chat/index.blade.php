@extends('layouts.shop')

@section('title', 'Chat AI')

@section('content')
<style>
    .chat-wrap { max-width: 780px; margin: 0 auto; }
    .chat-box {
        background: #fff; border: 1px solid var(--border); border-radius: var(--radius);
        box-shadow: var(--shadow-sm); overflow: hidden; display: flex; flex-direction: column; min-height: 520px;
    }
    .chat-head {
        padding: 16px 20px; border-bottom: 1px solid var(--border);
        background: linear-gradient(135deg, rgba(99,102,241,.12), rgba(201,168,76,.1));
        display: flex; justify-content: space-between; align-items: center; gap: 12px;
    }
    .chat-head h1 { font-family: 'Playfair Display', serif; font-size: 1.35rem; margin: 0; }
    .chat-head p { margin: 4px 0 0; color: var(--muted); font-size: .85rem; }
    .chat-messages {
        flex: 1; padding: 20px; overflow-y: auto; max-height: 420px;
        display: flex; flex-direction: column; gap: 12px; background: #f7f5ff;
    }
    .bubble { max-width: 80%; padding: 12px 14px; border-radius: 14px; font-size: .92rem; white-space: pre-wrap; word-break: break-word; }
    .bubble-me { align-self: flex-end; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; }
    .bubble-ai { align-self: flex-start; background: #fff; border: 1px solid #e5e7eb; }
    .bubble-meta { display: block; margin-top: 6px; font-size: .72rem; opacity: .7; }
    .chat-form { display: flex; align-items: flex-end; gap: 10px; padding: 14px 16px; border-top: 1px solid var(--border); }
    .chat-form textarea {
        flex: 1 1 auto; width: 100%; min-width: 0; min-height: 52px; resize: vertical;
        border: 1px solid var(--border); border-radius: 10px; padding: 12px 14px; font-family: inherit;
    }
    .chat-send-btn {
        flex: 0 0 auto; min-width: 96px; border: none; border-radius: 10px; padding: 12px 20px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; font-weight: 700; cursor: pointer;
    }
    .chat-empty { text-align: center; color: var(--muted); padding: 40px 16px; }
    .link-clear { font-size: .82rem; color: var(--muted); text-decoration: none; }
</style>

<div class="chat-wrap">
    @if (session('success'))
        <div class="alert-success" style="margin-bottom:14px;">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert-danger" style="margin-bottom:14px;">{{ $errors->first() }}</div>
    @endif

    <div class="chat-box">
        <div class="chat-head">
            <div>
                <h1>Chat AI hỗ trợ</h1>
                <p>Hỏi nhanh về sản phẩm, đơn hàng, thanh toán (Groq AI)</p>
            </div>
            <form action="{{ route('user.ai-chat.clear') }}" method="POST">
                @csrf
                <button type="submit" class="link-clear" style="background:none;border:none;cursor:pointer;">Xóa hội thoại</button>
            </form>
        </div>

        <div class="chat-messages" id="aiChatMessages">
            @forelse ($history as $row)
                <div class="bubble {{ ($row['role'] ?? '') === 'user' ? 'bubble-me' : 'bubble-ai' }}">
                    {{ $row['content'] ?? '' }}
                    <span class="bubble-meta">{{ ($row['role'] ?? '') === 'user' ? 'Bạn' : 'AI' }}</span>
                </div>
            @empty
                <div class="chat-empty">Hãy hỏi AI bất kỳ câu nào về cửa hàng trang sức.</div>
            @endforelse
        </div>

        <form action="{{ route('user.ai-chat.store') }}" method="POST" class="chat-form">
            @csrf
            <textarea name="body" placeholder="Ví dụ: Phí ship tính thế nào?" required maxlength="2000">{{ old('body') }}</textarea>
            <button type="submit" class="chat-send-btn">Gửi</button>
        </form>
    </div>
</div>

<script>
    const box = document.getElementById('aiChatMessages');
    if (box) box.scrollTop = box.scrollHeight;
</script>
@endsection
