<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Services\ChatFaqService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(ChatFaqService $faq): View
    {
        $user = Auth::user();

        ChatMessage::where('customer_id', $user->id)
            ->where('is_from_admin', true)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = ChatMessage::with('sender')
            ->where('customer_id', $user->id)
            ->orderBy('created_at')
            ->get();

        $faqs = $faq->faqs();

        return view('user.chat.index', compact('messages', 'faqs'));
    }

    public function store(Request $request, ChatFaqService $faq): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'faq' => ['nullable', 'string', 'max:200'],
        ], [
            'body.required' => 'Vui lòng nhập nội dung tin nhắn.',
        ]);

        $user = Auth::user();
        $body = trim($data['body']);

        ChatMessage::create([
            'customer_id' => $user->id,
            'sender_id' => $user->id,
            'body' => $body,
            'is_from_admin' => false,
        ]);

        $answer = null;
        if (! empty($data['faq'])) {
            $answer = $faq->replyForQuickQuestion($data['faq']);
        }
        $answer ??= $faq->replyForQuickQuestion($body);

        if ($answer) {
            $adminId = \App\Models\User::where('role', 'admin')->value('id') ?? $user->id;
            $faq->createAutoReply($user->id, (int) $adminId, $answer);
        }

        return redirect()->route('user.chat.index')->with('success', $answer ? 'Đã gửi và nhận trả lời nhanh.' : 'Đã gửi tin nhắn cho admin.');
    }
}
