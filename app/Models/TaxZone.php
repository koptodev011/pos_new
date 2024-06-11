<?php

namespace App\Models;

use App\Enums\TaxPriceDisplayType;
use App\Enums\TaxZoneType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxZone extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'default' => 'boolean',
        'active' => 'boolean',
        'zone_type' => TaxZoneType::class,
        'price_display' => TaxPriceDisplayType::class
    ];

    public function taxZoneCountries()
    {
        return $this->hasMany(TaxZoneCountry::class);
    }

}
