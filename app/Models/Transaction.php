<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'ref_num',
        'order_id',
        'payment_amount',
        'card_id',
        'paid_card',
        'completion_status',
        'transaction_id',
        'tracking_code',
    ];

    protected $hidden = [
        'id',
        'ref_num',
        'completion_status',
        'transaction_id',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(CardNumber::class, 'card_id');
    }
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public static function getGatewayMessage(string $status)
    {
        $message = match ($status) {
            '1' => 'موفق',
            '-1' => 'درخواست نامعتبر (خطا در پارامترهای ورودی)',
            '-2' => 'درگاه فعال نیست',
            '-3' => 'توکن تکراری است',
            '-4' => 'مبلغ بیشتر از سقف مجاز درگاه است',
            '-5' => 'شناسه ref_num معتبر نیست',
            '-6' => 'تراکنش قبلا وریفای شده است',
            '-7' => 'پارامترهای ارسال شده نامعتبر است',
            '-8' => 'تراکنش را نمیتوان وریفای کرد',
            '-9' => 'تراکنش وریفای نشد',
            '-98' => 'تراکنش ناموفق',
            '-99' => 'خطای سامانه',
            default => 'خطای نامشخص',
        };
        return $message;
    }
}
