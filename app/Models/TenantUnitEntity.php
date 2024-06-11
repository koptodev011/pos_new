<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantUnitEntity extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tenantable()
    {
        return $this->morphTo('tenantable');
    }

    public function tenantUnit()
    {
        return $this->belongsTo(TenantUnit::class);
    }
    public function promocode()
    {
        return $this->hasMany(PromoCode::class);
    }
}
