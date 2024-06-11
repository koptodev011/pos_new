<?php

namespace App\Livewire\Customer\Components;

use App\Enums\MenuType;
use Livewire\Component;
use App\Models\Menu;
use Illuminate\Support\Facades\Log;

class CompMenuType extends Component
{
    public $selectedTypes = [];

    public $menuTypes = [];

    public function mount()
    {
        $this->menuTypes = MenuType::filterCases();
    }

    public function toggleSelected($name)
    {
        if(in_array($name, $this->selectedTypes)) {
            $pos = array_search($name, $this->selectedTypes);
            unset($this->selectedTypes[$pos]);
        } else {
            $this->selectedTypes[] = $name;
        }
        // Dispatch the event with the selected menu type
        $this->dispatch('menu-type-changed', list: $this->selectedTypes);
    }

    public function isSelected($name)
    {
        return in_array($name, $this->selectedTypes);
    }

    public function render()
    {
        return view('livewire.customer.componant.menu-type');
    }
}
