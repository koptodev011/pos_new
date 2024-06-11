<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'website',
        'active',
        'gst'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function tenantUnits()
    {
        return $this->hasMany(TenantUnit::class);
    }

    public function getCurrencyAttribute()
    {
        return 'INR';
    }

}
