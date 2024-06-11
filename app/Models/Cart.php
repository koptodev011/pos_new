<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends BaseUnitModel
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'meta' => 'array'
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function floorTable()
    {
        return $this->belongsTo(FloorTable::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

}
