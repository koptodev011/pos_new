<?php

namespace App\Models;

use App\Models\Scopes\TenantUnitEntityFilterScope;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected static function booted(): void
    {
        parent::boot();
        static::addGlobalScope(new TenantUnitEntityFilterScope());
    }

    public function tenantUnits(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(TenantUnitEntity::class, 'tenantable');
    }

}
