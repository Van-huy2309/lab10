@extends('layouts.shop')

@section('title', 'Chat với Admin')

@section('content')
<style>
    .chat-wrap { max-width: 780px; margin: 0 auto; }
    .chat-box {
        background: #fff; border: 1px solid var(--border); border-radius: var(--radius);
        box-shadow: var(--shadow-sm); overflow: hidden; display: flex; flex-direction: column;
        min-height: 520px;
    }
    .chat-head {
        padding: 16px 20px; border-bottom: 1px solid var(--border);
        background: linear-gradient(135deg, rgba(201,168,76,.12), rgba(194,121,138,.08));
    }
    .chat-head h1 { font-family: 'Playfair Display', serif; font-size: 1.4rem; margin: 0; }
    .chat-head p { margin: 4px 0 0; color: var(--muted); font-size: .85rem; }
    .faq-row { display: flex; flex-wrap: wrap; gap: 8px; padding: 12px 16px; border-bottom: 1px solid var(--border); background: #fffdf8; }
    .faq-btn {
        border: 1px solid var(--border); background: #fff; color: var(--dark);
        border-radius: 999px; padding: 6px 12px; font-size: .78rem; font-weight: 600; cursor: pointer;
    }
    .faq-btn:hover { border-color: var(--gold); color: var(--gold-dark); background: rgba(201,168,76,.08); }
    .chat-messages {
        flex: 1; padding: 20px; overflow-y: auto; max-height: 380px;
        display: flex; flex-direction: column; gap: 12px; background: #faf7f1;
    }
    .bubble {
        max-width: 78%; padding: 12px 14px; border-radius: 14px; font-size: .92rem;
        line-height: 1.45; white-space: pre-wrap; word-break: break-word;
    }
    .bubble-me {
        align-self: flex-end; background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #fff; border-bottom-right-radius: 4px;
    }
    .bubble-admin {
        align-self: flex-start; background: #fff; border: 1px solid var(--border);
        color: var(--dark); border-bottom-left-radius: 4px;
    }
    .bubble-meta { display: block; margin-top: 6px; font-size: .72rem; opacity: .75; }
    .chat-form {
        display: flex; align-items: flex-end; gap: 10px;
        padding: 14px 16px; border-top: 1px solid var(--border); background: #fff;
    }
    .chat-form textarea {
        flex: 1 1 auto; width: 100%; min-width: 0; min-height: 52px; max-height: 140px;
        resize: vertical; border: 1px solid var(--border); border-radius: 10px;
        padding: 12px 14px; font-family: inherit; font-size: .9rem; line-height: 1.4;
        background: #fff;
    }
    .chat-form textarea:focus { outline: none; border-color: var(--gold); box-shadow: 0 0 0 3px rgba(201,168,76,.2); }
    .chat-send-btn {
        flex: 0 0 auto; width: auto !important; min-width: 96px;
        background: linear-gradient(135deg, var(--gold), var(--gold-dark));
        color: #fff; border: none; border-radius: 10px; padding: 12px 20px;
        font-size: .95rem; font-weight: 700; cursor: pointer; white-space: nowrap;
        box-shadow: 0 4px 12px rgba(201,168,76,.35);
    }
    .chat-send-btn:hover { opacity: .92; }
    .chat-empty { text-align: center; color: var(--muted); padding: 48px 16px; }
    .ai-link { display: inline-block; margin-top: 12px; font-size: .9rem; color: #6366f1; font-weight: 600; }
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
            <h1>Chat hỗ trợ Admin</h1>
            <p>Câu hỏi nhanh bên dưới sẽ được trả lời tự động. Câu khác Admin sẽ trả lời.</p>
            <a class="ai-link" href="{{ route('user.ai-chat.index') }}">→ Mở Chat AI</a>
        </div>

        <div class="faq-row">
            @foreach ($faqs as $question => $answer)
                <form action="{{ route('user.chat.store') }}" method="POST" class="mb-0">
                    @csrf
                    <input type="hidden" name="body" value="{{ $question }}">
                    <input type="hidden" name="faq" value="{{ $question }}">
                    <button type="submit" class="faq-btn">{{ $question }}</button>
                </form>
            @endforeach
        </div>

        <div class="chat-messages" id="chatMessages">
            @forelse ($messages as $message)
                <div class="bubble {{ $message->is_from_admin ? 'bubble-admin' : 'bubble-me' }}">
                    {{ $message->body }}
                    <span class="bubble-meta">
                        {{ $message->is_from_admin ? 'Admin' : 'Bạn' }} · {{ $message->created_at->format('d/m/Y H:i') }}
                    </span>
                </div>
            @empty
                <div class="chat-empty">Chưa có tin nhắn. Chọn câu hỏi nhanh hoặc gửi tin cho Admin.</div>
            @endforelse
        </div>

        <form action="{{ route('user.chat.store') }}" method="POST" class="chat-form">
            @csrf
            <textarea name="body" placeholder="Nhập tin nhắn..." required maxlength="2000">{{ old('body') }}</textarea>
            <button type="submit" class="chat-send-btn">Gửi</button>
        </form>
    </div>
</div>

<script>
    const box = document.getElementById('chatMessages');
    if (box) box.scrollTop = box.scrollHeight;
</script>
@endsection
