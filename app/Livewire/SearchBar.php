<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Menu as MenuModel;

class SearchBar extends Component
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
        return view('livewire.customer.componant.search-bar', [
            'rollingPlaceholders' => $this->rollingPlaceholders,
            'currentPlaceholder' => $this->currentPlaceholder,
            'searchResults'=>$this->searchResults,
        ]);
    }

    public function updatedSearch()
    {
        // Update the current placeholder index
        $this->currentPlaceholder = ($this->currentPlaceholder + 1) % count($this->rollingPlaceholders);
    }

    public function searchMenu(){
        if(strlen($this->search) > 2){
            $this->searchResults = MenuModel::select('name')->where('name', 'LIKE', '%' . $this->search . '%')->get();
        }
           
    }
}



