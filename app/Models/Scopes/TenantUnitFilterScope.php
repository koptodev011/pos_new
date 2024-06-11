<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantUnitFilterScope implements Scope
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
                $builder->whereIn('tenant_unit_id', $units->pluck('tenant_unit_id')->all())->orWhereNull('tenant_unit_id');
            }
        }

    }
}
