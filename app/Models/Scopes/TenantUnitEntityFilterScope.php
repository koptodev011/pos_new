<?php

namespace App\Models\Scopes;

use App\Models\TenantUnitEntity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantUnitEntityFilterScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {

        if(Auth::hasUser()) {
            $user = Auth::user();
            if(!$user->hasRole(['Customer'])) {
                $units = $user->userTenantUnits;
                $values = $units->pluck('tenant_unit_id')->all();
                // $builder->whereIn('tenant_unit_id', $units->pluck('tenant_unit_id')->all());
                $builder->with(['tenantUnits'])->whereHas('tenantUnits', function (Builder $query) use ($values) {
                    return $query->whereIn('tenant_unit_id', $values);
                });
            }

        }

    }
}
