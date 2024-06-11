<?php

namespace App\Livewire\Customer\Components;

use App\Models\Menu;
use Livewire\Component;

use Livewire\Attributes\On;

use Illuminate\Database\Eloquent\Builder;

class CompMenuListFilter extends Component
{
    public $selectedMealTypes = [];

    public $selectedCategory = null;

    public $currency = null;

    public function render()
    {
        $selectedMealTypes = $this->selectedMealTypes;
        $category = $this->selectedCategory;
        $menus = Menu::when(collect($this->selectedMealTypes)->isNotEmpty(), function (Builder $query) use ($selectedMealTypes) {
            $query->whereIn('type', $selectedMealTypes);
        })->when($category != null, function (Builder $query) use ($category) {
            $query->with(['menuCategories'])->whereHas('menuCategories', function (Builder $subquery) use ($category) {
                $subquery->whereIn('categories.id', $category);
            });
        })->whereActive(true)->get();
        return view('livewire.customer.componant.menu-list-filter', ['menus' => $menus]);
    }

    #[On('menu-type-changed')]
    public function onMenuTypeChanged($list)
    {
        $this->selectedMealTypes = $list;
    }

    #[On('category-changed')]
    public function onCategoryChanged($idList)
    {
        $this->selectedCategory = $idList;
    }

}
