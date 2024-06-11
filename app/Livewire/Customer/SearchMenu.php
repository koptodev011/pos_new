<?php

namespace App\Livewire\Customer;
use App\Models\Menu as MenuModel;

use Livewire\Component;

class SearchMenu extends Component
{
    public $search;
    public  $searchResults = [];
   
    public $rollingPlaceholders = [
        'Search for something...',
        'Try searching by category...',
        'Looking for inspiration?...',
    ];
    public $currentPlaceholder = 0;
    protected $listeners = ['search-menu'=> 'searchMenu'];

    
    public function render()
    {
        $cartHelper = new \App\Helpers\CartHelper();
        $tenantUnit = $cartHelper->tenantUnit();
        $currency = $tenantUnit->country->getCurrency();
       
        return view('livewire.customer.componant.search-menu', [
            'rollingPlaceholders' => $this->rollingPlaceholders,
            'currentPlaceholder' => $this->currentPlaceholder,
            'searchResults'=>$this->searchResults,
            'currency' => $currency
        ]);
    }

    public function updatedSearch()
    {
        // Update the current placeholder index
        $this->currentPlaceholder = ($this->currentPlaceholder + 1) % count($this->rollingPlaceholders);
    }

    public function searchMenu(){
        if(strlen($this->search) > 3){
            $this->searchResults = MenuModel::where('name','LIKE',"%{$this->search}%")->get();
        }else{
            $this->searchResults = [];
        }
    }
}
