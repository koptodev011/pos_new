<?php

namespace App\Livewire\Customer;

use App\Livewire\Forms\Customer\SignupForm;
use Livewire\Component;
use Livewire\WithFileUploads;

class SignUp extends Component
{
    use WithFileUploads;

    public SignupForm $form;

    public function register()
    {
        $this->form->create();
        return redirect()->back();
    }

    public function render()
    {
        return view('livewire.customer.sign-up');
    }
}
