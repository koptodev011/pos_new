<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'description',
        'url',
        'start_date',
        'end_date',
        'tenant_unit_id',
    ];


    // public function bannerable()
    // {
    //     return $this->morphTo();
    // }
}
