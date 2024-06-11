<?php

namespace App\Models;

use App\Models\Scopes\TenantUnitFilterScope;
use Illuminate\Database\Eloquent\Model;

class BaseUnitModel extends Model
{
    protected static function booted(): void
    {
        parent::boot();
        static::addGlobalScope(new TenantUnitFilterScope());
    }

    public function tenantUnit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TenantUnit::class);
    }

}
