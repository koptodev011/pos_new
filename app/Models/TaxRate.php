<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function taxZone()
    {
        return $this->belongsTo(TaxZone::class);
    }

    public function taxRateAmounts()
    {
        return $this->hasMany(TaxRateAmount::class);
    }

}
