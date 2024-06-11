<?php

namespace App\Livewire\Customer;

use Livewire\Component;

class TableReservation extends Component
{
   
    public $showModal = false;

    public function render()
    {
        return view('livewire.Customer.table-reservation');
    }

    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
        
    }
    
}
