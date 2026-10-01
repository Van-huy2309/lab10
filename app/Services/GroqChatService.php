<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqChatService
{
    public function ask(string $userMessage, array $history = []): string
    {
        $apiKey = (string) config('services.groq.api_key');
        if ($apiKey === '') {
            return 'Chat AI chưa được cấu hình API key.';
        }

        $messages = [
            [
                'role' => 'system',
                'content' => 'Bạn là trợ lý AI của cửa hàng trang sức "Trang Sức Của Huy". '
                    .'Trả lời ngắn gọn, thân thiện bằng tiếng Việt. '
                    .'Hỗ trợ về sản phẩm trang sức, đơn hàng, COD/MoMo, vận chuyển GHN. '
                    .'Không bịa thông tin thanh toán thật; môi trường đang là test.',
            ],
        ];

        foreach ($history as $row) {
            $messages[] = [
                'role' => $row['role'],
                'content' => $row['content'],
            ];
        }

        $messages[] = ['role' => 'user', 'content' => $userMessage];

        try {
            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->acceptJson()
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => config('services.groq.model', 'llama-3.3-70b-versatile'),
                    'messages' => $messages,
                    'temperature' => 0.5,
                    'max_tokens' => 600,
                ]);

            if (! $response->successful()) {
                Log::warning('Groq API failed', ['status' => $response->status(), 'body' => $response->json()]);

                return 'Xin lỗi, AI tạm thời không phản hồi được. Bạn hãy thử lại hoặc chat với Admin.';
            }

            return trim((string) data_get($response->json(), 'choices.0.message.content', 'Không nhận được câu trả lời.'));
        } catch (\Throwable $e) {
            Log::error('Groq API exception', ['error' => $e->getMessage()]);

            return 'Không kết nối được tới AI. Vui lòng thử lại sau.';
        }
    }
}
