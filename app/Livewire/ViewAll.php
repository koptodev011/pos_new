<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Menu as MenuModel;
use App\models\TenantUnit;
use App\Models\FloorTable;

use Livewire\Component;

class ViewAll extends Component


{

    public $selectedMealType = null;
    public $selectedCategory = null;
    public FloorTable $floorTable;




    public function render()
    {
   
        $menus=MenuModel::all();
        $tenant=TenantUnit::find('1');
        $currency=$tenant->country->currency; 
        $menus = MenuModel::when($this->selectedMealType, fn ($query) =>
        $query->where('type', $this->selectedMealType)
        )->when($this->selectedCategory, fn ($query) =>
            $query->whereHas('menuCategories', fn ($query) =>
                $query->where('category_id', $this->selectedCategory)
            )
        )->get();
        
        return view('livewire.view-all',['menus'=>$menus,'currency'=>$currency]);
    }

    public function onMealTypeChanged($name){
        $this->selectedMealType = $name == $this->selectedMealType ? null : $name;
    }

    public function oncategoryChanged($id){
        $this->selectedCategory = $id == $this->selectedCategory ? null : $id;
    }
    
}

