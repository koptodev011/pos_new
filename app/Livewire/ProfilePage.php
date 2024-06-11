<?php

namespace App\Livewire;

use Livewire\Component;

class ProfilePage extends Component
{

    public $showModal = false;

    // Method to toggle modal visibility
    public function togglePopup()
    {
        $this->showModal = !$this->showModal;
    }

    public function render()
    {
        return view('livewire.profile-page');
    }
}
