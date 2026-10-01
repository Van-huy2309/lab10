<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\GroqChatService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiChatController extends Controller
{
    public function index(): View
    {
        $history = session('ai_chat_history', []);

        return view('user.ai-chat.index', compact('history'));
    }

    public function store(Request $request, GroqChatService $groq)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ], [
            'body.required' => 'Vui lòng nhập câu hỏi.',
        ]);

        $history = session('ai_chat_history', []);
        $reply = $groq->ask($data['body'], $history);

        $history[] = ['role' => 'user', 'content' => $data['body']];
        $history[] = ['role' => 'assistant', 'content' => $reply];
        $history = array_slice($history, -20);

        session(['ai_chat_history' => $history]);

        return redirect()->route('user.ai-chat.index');
    }

    public function clear()
    {
        session()->forget('ai_chat_history');

        return redirect()->route('user.ai-chat.index')->with('success', 'Đã xóa hội thoại AI.');
    }
}
