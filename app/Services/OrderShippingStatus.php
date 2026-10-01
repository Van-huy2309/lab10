<?php

namespace App\Services;

/**
 * Chuẩn hóa trạng thái vận chuyển theo nghiệp vụ shop trang sức.
 */
class OrderShippingStatus
{
    /** Chờ xử lý: thanh toán thất bại / chưa tạo vận đơn COD */
    public const PENDING = 'pending';

    /** Chờ lấy hàng: MoMo đã thanh toán thành công */
    public const READY = 'ready_to_pick';

    /** Đang lấy hàng: COD đã có mã GHN */
    public const PICKING = 'picking';

    /** Đang giao: GHN đã lấy hàng / đang vận chuyển */
    public const DELIVERING = 'delivering';

    public const DELIVERED = 'delivered';

    public const CANCELLED = 'cancelled';

    public const RETURN = 'return';

    public const RETURNING = 'returning';

    public const RETURNED = 'returned';

    /** Có thể xóa/hủy */
    public const DELETABLE = [
        self::PENDING,
        'not_shipped',
        'processing',
        self::READY,
        self::PICKING,
    ];

    /** Không hủy: đang giao trở đi */
    public const LOCKED = [
        self::DELIVERING,
        'picked',
        'storing',
        'transporting',
        'sorting',
        self::DELIVERED,
        self::RETURN,
        self::RETURNING,
        self::RETURNED,
        'return_transporting',
        'return_sorting',
        self::CANCELLED,
    ];

    /**
     * Map trạng thái webhook GHN → trạng thái nội bộ.
     */
    public static function fromGhn(string $ghnStatus): string
    {
        $status = strtolower(trim($ghnStatus));

        return match (true) {
            in_array($status, ['delivered'], true) => self::DELIVERED,
            in_array($status, [
                'delivery_fail', 'undeliverable', 'exception', 'cancel', 'cancelled',
                'lost', 'damage', 'destroy',
            ], true) => self::CANCELLED,
            in_array($status, [
                'return', 'returning', 'returned', 'return_transporting', 'return_sorting', 'waiting_to_return',
            ], true) => match ($status) {
                'returning', 'return_transporting', 'return_sorting' => self::RETURNING,
                'returned' => self::RETURNED,
                default => self::RETURN,
            },
            in_array($status, [
                'picked', 'storing', 'transporting', 'sorting', 'delivering', 'money_collect_picking', 'money_collect_delivering',
            ], true) => self::DELIVERING,
            in_array($status, ['picking', 'ready_to_pick'], true) => $status === 'picking' ? self::PICKING : self::READY,
            default => $status,
        };
    }

    public static function canDelete(?string $shippingStatus): bool
    {
        return in_array($shippingStatus, self::DELETABLE, true);
    }

    public static function canCancel(?string $shippingStatus): bool
    {
        return self::canDelete($shippingStatus);
    }
}
