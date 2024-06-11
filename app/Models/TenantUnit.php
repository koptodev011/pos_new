<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantUnit extends Model
{
    use HasFactory;

    protected $casts = [
        'active' => 'boolean',
        'default' => 'boolean'
    ];

    protected $guarded = [];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
        
    }

    public function getOneLineAttribute()
    {

        $collect = collect([$this->line_one, $this->line_two, $this->line_three, $this->landmark, $this->city, $this->postal_code])->filter();
        return $collect->join(", ");

    }

    public function country()
    {
        return $this->belongsTo(Country::class);
        
    }

    public function state()
    {
        return $this->belongsTo(State::class);
        
    }

}
