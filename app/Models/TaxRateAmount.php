<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRateAmount extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function taxRate()
    {
        return $this->belongsTo(TaxRate::class);
    }

    public function taxClass()
    {
        return $this->belongsTo(TaxClass::class);
    }

}
