<?php

namespace App\Livewire\Forms\Customer;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{

    #[Validate('required|email')]
    public $email = '';
 
    #[Validate('required')]
    public $password = '';

    public function login() {
        return $this->validate();
    }

}
