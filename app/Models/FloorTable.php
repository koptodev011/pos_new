<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class FloorTable extends BaseUnitModel
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = ['share_url'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function getShareUrlAttribute()
    {
        $cid = $this->id;
        $cname=$this->name;
        $url = URL::signedRoute('customers.orders.tables', ['floorTable' => $cid]);
        return $url;
    }

}
