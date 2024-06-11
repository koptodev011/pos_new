<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'active',
        'profile_photo_path',
        'tz'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean'
        ];
    }

    protected $appends = [
        'profile_photo_url'
    ];

    public function getRoleAttribute()
    {
        return $this->getRoleNames();
    }

    public function userTenantUnits()
    {
        return $this->hasMany(UserTenantUnit::class);
    }

    public function userTenantUnitList()
    {
        return $this->hasMany(UserTenantUnit::class)->pluck('tenant_unit_id')->all();
    }

    // public function tenantUnit()
    // {
    //     return $this->belongsTo(TenantUnit::class);
    // }

    public function tenant()
    {
        return $this->tenantUnits()->first()->tenant();
    }

    public function tenantUnits(): HasManyThrough
    {
        return $this->hasManyThrough(TenantUnit::class, UserTenantUnit::class, 'user_id', 'id', 'id', 'id');
    }

    public function tenantUsers()
    {
        $tenant_unit_ids = $this->tenantUnits()->pluck('tenant_unit_id')->all();
        return User::with(['tenantUnits'])->whereHas('tenantUnits', function ($query) use ($tenant_unit_ids) {
            $query->where('user_id', '<>', $this->id)
            ->where('tenant_unit_id', $tenant_unit_ids);
        })->get();
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path != null ? url(Storage::url($this->profile_photo_path)) : null;

    }

}
