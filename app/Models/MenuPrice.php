<?php

namespace App\Models;

use App\Enums\MenuPriceType;
use App\Enums\MenuPriceValidity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuPrice extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'value' => 'double',
        'active' => 'boolean',
        'days' => 'array',
        'type' => MenuPriceType::class,
        'validity' => MenuPriceValidity::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    protected static function booted(): void
    {
        static::creating(function ($menuPrice) {
            if($menuPrice->days == null) {
                $menuPrice->days = [];
            }
        });
    }

}
