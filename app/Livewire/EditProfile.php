<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Forms\Customer\SignupForm;
use Illuminate\Support\Facades\Auth;


class EditProfile extends Component
{
    use WithFileUploads;

    public SignupForm $form;
    public $successMessage = '';

    
    public function updateProfile()
    {
        $this->form->update();
        $this->successMessage = 'Profile updated successfully';
        return redirect()->back();
    }

    public function mount(){
        $this->form->setProfileData(Auth::user());

    }

    public function render()
    {
        return view('livewire.edit-profile');
    }

    public function clearSuccessMessage()
    {
        $this->successMessage = '';
    }
    
}
