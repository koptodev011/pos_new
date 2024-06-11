<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class Profile extends Component
{
    public function render()
    {
        return view('livewire.profile');
    }

    public function navigateToEditProfile()
    {
        // Navigate to the "favorite" route
        return redirect()->to(route('editprofile'));
    }

    public function navigateToLogout()
    {
        FacadesAuth::logout();
        return redirect('/customers/orders/home');
    }

    
}