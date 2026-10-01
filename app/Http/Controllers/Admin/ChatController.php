<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $threads = User::query()
            ->where('role', 'customer')
            ->whereHas('customerMessages')
            ->withCount([
                'customerMessages as unread_count' => function ($query) {
                    $query->where('is_from_admin', false)->whereNull('read_at');
                },
            ])
            ->addSelect([
                'last_message_at' => ChatMessage::select('created_at')
                    ->whereColumn('customer_id', 'users.id')
                    ->latest()
                    ->limit(1),
                'last_message_body' => ChatMessage::select('body')
                    ->whereColumn('customer_id', 'users.id')
                    ->latest()
                    ->limit(1),
            ])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('admin.chat.index', compact('threads'));
    }

    public function show(User $user): View|RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.chat.index')->with('error', 'Chỉ chat với khách hàng.');
        }

        ChatMessage::where('customer_id', $user->id)
            ->where('is_from_admin', false)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = ChatMessage::with('sender')
            ->where('customer_id', $user->id)
            ->orderBy('created_at')
            ->get();

        return view('admin.chat.show', compact('user', 'messages'));
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.chat.index')->with('error', 'Chỉ chat với khách hàng.');
        }

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ], [
            'body.required' => 'Vui lòng nhập nội dung trả lời.',
        ]);

        ChatMessage::create([
            'customer_id' => $user->id,
            'sender_id' => Auth::id(),
            'body' => trim($data['body']),
            'is_from_admin' => true,
        ]);

        return redirect()->route('admin.chat.show', $user)->with('success', 'Đã gửi trả lời.');
    }
}
