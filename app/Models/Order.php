<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseUnitModel
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded  = [];

    protected $casts = [
        'summary' => 'array',
        'customer' => 'array',
        'address' => 'array',
        'shipping' => 'array',
        'meta' => 'array',
        'status' => OrderStatus::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function floorTable()
    {
        return $this->belongsTo(FloorTable::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderPayments()
    {
        return $this->hasMany(OrderPayment::class);
    }

    public function orderHistories()
    {
        return $this->hasMany(OrderHistory::class);
    }
    
}
