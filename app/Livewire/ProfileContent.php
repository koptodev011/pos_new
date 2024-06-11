<?php

namespace App\Livewire;

use Livewire\Component;

class ProfileContent extends Component



{
    public $selectedStates = [];
    public $showModal = false; // Flag to control modal visibility

    public $imageUrls = [
        'Order History' => '/assets/images/bell.svg',
        'Table Reservation' => '/assets/images/reservation.svg',
        'Loyalty Points' => '/assets/images/Vector.svg',
    ];

    public function mount()
    {
        $names = ['Order History', 'Table Reservation', 'Loyalty Points'];

        foreach ($names as $name) {
            $this->selectedStates[$name] = false;
        }
    }

    

    public function toggleSelected($name)
    {
        // Toggle selected state for the clicked badge
        $this->selectedStates[$name] = !$this->selectedStates[$name];

        // Check if "Table Reservation" badge is clicked
        if ($name === 'Table Reservation' && $this->selectedStates[$name]) {
            // Set the flag to true to show the modal
            // $this->showModal = true;
            return redirect()->to(url('customers/orders/tablereservation'));
            
        }

        if ($name === 'Order History' && $this->selectedStates[$name]) {
            // Redirect to the desired route
            return redirect()->to(url('customers/orders/orderhistory'));
        }

        if ($name === 'Loyalty Points' && $this->selectedStates[$name]) {
            // Redirect to the desired route
            // return redirect()->to(url('customers/orders/loyaltypoints'));
            return redirect()->to(url('kitchen/orders/show'));
        }
    }

    public function render()
    {
        return view('livewire.customer.componant.profile-content');
    }
}
