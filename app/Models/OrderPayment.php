<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;
    protected $guarded  = [];

    protected $casts = [
        'payment_provider_response' => 'array',
        'paid' => 'boolean',
        'meta' => 'array',
        'amount' => 'double'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
