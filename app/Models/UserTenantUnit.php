<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTenantUnit extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tenantUnit()
    {
        return $this->belongsTo(TenantUnit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
