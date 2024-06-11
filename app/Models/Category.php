<?php

namespace App\Models;

use App\Models\Scopes\TenantFilterScope;
use App\Models\Scopes\TenantUnitEntityFilterScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends BaseModel
{
    use HasFactory;
    use NodeTrait;
    use SoftDeletes;

    protected $fillable = [
        'name',
        '_slug',
        'image',
        'description',
        'parent_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

}
