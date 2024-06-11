<?php

namespace App\Policies;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BannerPolicy
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
    public function view(User $user, Banner $banner): bool
    {
        // Check if the user is an owner & has any tenant unit matching the banner's tenant unit ID
        return $user->hasAnyRole([ 'Owner']) && $user->userTenantUnits()->where('tenant_unit_id', $banner->tenant_unit_id)->exists();
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
    public function update(User $user, Banner $banner): bool
    {
        // Check if the user is an owner & has any tenant unit matching the banner's tenant unit ID
        return $user->hasAnyRole([ 'Owner']) && $user->userTenantUnits()->where('tenant_unit_id', $banner->tenant_unit_id)->exists();
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Banner $banner): bool
    {
        // Check if the user is an owner & has any tenant unit matching the banner's tenant unit ID
        return $user->hasAnyRole([ 'Owner']) && $user->userTenantUnits()->where('tenant_unit_id', $banner->tenant_unit_id)->exists();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Banner $banner): bool
    {
        // Check if the user is an owner & has any tenant unit matching the banner's tenant unit ID
        return $user->hasAnyRole([ 'Owner']) && $user->userTenantUnits()->where('tenant_unit_id', $banner->tenant_unit_id)->exists();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Banner $banner): bool
    {
        // Check if the user is an owner & has any tenant unit matching the banner's tenant unit ID
        return $user->hasAnyRole([ 'Owner']) && $user->userTenantUnits()->where('tenant_unit_id', $banner->tenant_unit_id)->exists();
    }
}
