<?php

namespace App\Policies;

use App\Models\FloorTable;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FloorTablePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Owner', 'Manager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FloorTable $floorTable): bool
    {
        return $user->userTenantUnits()->where('tenant_unit_id', $floorTable->tenant_unit_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {

        return $user->hasRole(['Owner', 'Manager']);



    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FloorTable $floorTable): bool
    {

        return $user->userTenantUnits()->where('tenant_unit_id', $floorTable->tenant_unit_id)->exists();

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FloorTable $floorTable): bool
    {

        return $user->userTenantUnits()->where('tenant_unit_id', $floorTable->tenant_unit_id)->exists();

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FloorTable $floorTable): bool
    {

        return $user->userTenantUnits()->where('tenant_unit_id', $floorTable->tenant_unit_id)->exists();

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FloorTable $floorTable): bool
    {

        return $user->userTenantUnits()->where('tenant_unit_id', $floorTable->tenant_unit_id)->exists();

    }
}
