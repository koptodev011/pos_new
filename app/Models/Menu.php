<?php

namespace App\Models;

use App\Enums\MenuType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends BaseModel implements \Spatie\MediaLibrary\HasMedia
{
    use HasFactory;
    use \Spatie\MediaLibrary\InteractsWithMedia;
    use \Spatie\Tags\HasTags;

    protected $table = 'menus';

    protected $guarded = [];

    protected $casts = [
        'active' => 'boolean',
        'price' => 'double',
        'meta' => 'array',
        'type' => MenuType::class
    ];

    protected $appends = ['applied_price'];

    public function menuPrice()
    {
        return $this->hasOne(MenuPrice::class);
    }

    public function menuOptions()
    {
        return $this->belongsToMany(MenuOption::class);
    }

    public function menuCategories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function menuMealTimes()
    {
        return $this->belongsToMany(MealTime::class);
    }

    public function taxClass()
    {
        return $this->belongsTo(TaxClass::class);
    }

    public function getAppliedPriceAttribute()
    {
        return $this->price;
    }

    public function getTagNamesAttribute()
    {
        return $this->tags->map(function ($tag) {
            return $tag->name;
        });
    }

    public function getImagesAttribute()
    {
        return collect($this->media)->map(fn ($item) => $item->getFullUrl());
    }

    public function getImageAttribute()
    {
        $collect = collect($this->media)->map(fn ($item) => $item->getFullUrl());
        return $collect->count() > 0 ? $collect->first() : null;
    }

    public function favourite()
    {
        return $this->belongsTo(Favourite::class);
    }

}
