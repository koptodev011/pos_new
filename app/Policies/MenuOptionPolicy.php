<?php

namespace App\Policies;

use App\Models\MenuOption;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MenuOptionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([ 'Owner']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MenuOption $menuOption): bool
    {
        $values = $menuOption->tenantUnits->pluck('tenant_unit_id')->all();
        return $user->hasAnyRole([ 'Owner']) && $user->userTenantUnits()->whereIn('tenant_unit_id', $values)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([ 'Owner']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MenuOption $menuOption): bool
    {
        $values = $menuOption->tenantUnits->pluck('tenant_unit_id')->all();
        return $user->hasAnyRole([ 'Owner']) && $user->userTenantUnits()->whereIn('tenant_unit_id', $values)->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MenuOption $menuOption): bool
    {
        $values = $menuOption->tenantUnits->pluck('tenant_unit_id')->all();
        return $user->hasAnyRole([ 'Owner']) && $user->userTenantUnits()->whereIn('tenant_unit_id', $values)->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MenuOption $menuOption): bool
    {
        $values = $menuOption->tenantUnits->pluck('tenant_unit_id')->all();
        return $user->hasAnyRole([ 'Owner']) && $user->userTenantUnits()->whereIn('tenant_unit_id', $values)->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MenuOption $menuOption): bool
    {
        $values = $menuOption->tenantUnits->pluck('tenant_unit_id')->all();
        return $user->hasAnyRole([ 'Owner']) && $user->userTenantUnits()->whereIn('tenant_unit_id', $values)->exists();
    }
}
