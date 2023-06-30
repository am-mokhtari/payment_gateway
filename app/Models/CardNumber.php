<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_number',
        'user_id',
    ];

    protected $hidden = [
        'user_id',
        'id',
    ];
}
