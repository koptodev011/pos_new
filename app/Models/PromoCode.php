<?php

namespace App\Models;

use App\Enums\PromoCodeType;
use App\Enums\PromoCodeValueType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class PromoCode extends BaseModel
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'type' => PromoCodeType::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'value_type' => PromoCodeValueType::class,
        'value' => 'double',
        'min_value' => 'double',
        'max_value' => 'double',
        'active' => 'boolean',
        'meta' => 'json'
    ];

}
