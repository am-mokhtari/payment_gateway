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
}
