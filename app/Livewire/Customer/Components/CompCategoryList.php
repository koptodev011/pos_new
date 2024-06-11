<?php

namespace App\Livewire\Customer\Components;

use App\Models\Category;

use Livewire\Component;

class CompCategoryList extends Component
{
    public $selectedStates = [];

    public function render()
    {
        $categories = Category::all();
        return view('livewire.customer.componant.category-list', ['categories' => $categories]);
    }

    public function onChanged($id)
    {
        if (in_array($id, $this->selectedStates)) {
            $pos = array_search($id, $this->selectedStates);
            unset($this->selectedStates[$pos]);
            $id = null;
        } else {
            $this->selectedStates[] = $id;
        }
        $this->dispatch('category-changed', idList: $this->selectedStates);
    }

    public function isSelected($id)
    {
        return in_array($id, $this->selectedStates);
    }
}
