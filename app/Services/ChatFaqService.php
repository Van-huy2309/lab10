<?php

namespace App\Services;

use App\Models\ChatMessage;

class ChatFaqService
{
    /** @return array<string, string> */
    public function faqs(): array
    {
        return [
            'Giờ mở cửa?' => 'Cửa hàng Trang Sức Của Huy mở cửa 8:00–21:00 mỗi ngày (kể cả cuối tuần).',
            'Phí ship bao nhiêu?' => 'Phí vận chuyển được tính theo địa chỉ GHN khi bạn thanh toán. Đơn MoMo đã bao gồm phí ship trong tổng tiền.',
            'Thanh toán cách nào?' => 'Bạn có thể chọn COD (trả khi nhận hàng) hoặc MoMo (thẻ/ATM test).',
            'Theo dõi đơn hàng?' => 'Vào mục Đơn hàng trên website để xem trạng thái và mã vận đơn GHN.',
            'Đổi trả thế nào?' => 'Liên hệ Admin trong chat này kèm mã đơn. Đơn đang giao/đã giao thành công cần liên hệ để hỗ trợ hoàn hàng.',
            'Liên hệ hotline?' => 'Bạn có thể nhắn trực tiếp tại đây hoặc gọi hotline cửa hàng (cập nhật trên trang chủ).',
        ];
    }

    public function replyForQuickQuestion(string $question): ?string
    {
        $faqs = $this->faqs();
        if (isset($faqs[$question])) {
            return $faqs[$question];
        }

        $normalized = mb_strtolower(trim($question));
        foreach ($faqs as $q => $answer) {
            if ($normalized === mb_strtolower($q) || str_contains($normalized, mb_strtolower(rtrim($q, '?')))) {
                return $answer;
            }
        }

        return null;
    }

    public function createAutoReply(int $customerId, int $senderId, string $answer): ChatMessage
    {
        return ChatMessage::create([
            'customer_id' => $customerId,
            'sender_id' => $senderId,
            'body' => $answer,
            'is_from_admin' => true,
            'read_at' => now(),
        ]);
    }
}
