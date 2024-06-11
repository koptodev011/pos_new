<?php

namespace App\Livewire\Forms\Customer;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ResetPinForm extends Form
{
    
    #[Validate('required|email|exists:users,email')]
    public $email = '';

    #[Validate('required')]
    public $token = '';

    #[Validate('required|min:4|confirmed')]
    public $password = '';

    #[Validate('required')]
    public $password_confirmation = '';

    public function submitPin() {
        $attributes = $this->validate();
        $user = User::where('email', $attributes['email'])->first();
        $passwordReset = PasswordReset::where('email', $user->email)->first();
        if($passwordReset == null) {
            throw ValidationException::withMessages([
               'reset_pin_form.token' => ['The provided otp is incorrect.'],
            ]);
            return;
        }

        if($passwordReset->token != $attributes['token']) {
            throw ValidationException::withMessages([
               'reset_pin_form.token' => ['The provided otp is incorrect.'],
            ]);
            return;
        }

        $user->fill([
            'password' => Hash::make($attributes['password'])
        ]);

        $user->save();

        $passwordReset->delete();
    }

}
