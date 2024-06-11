<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuOption extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'price' => 'double'
    ];


    public function getAppliedPriceAttribute()
    {
        return $this->price;
    }


}
