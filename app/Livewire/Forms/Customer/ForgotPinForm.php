<?php

namespace App\Livewire\Forms\Customer;

use App\Helpers\TokenCodeHelper;
use App\Mail\ForgotPasswordRequested;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ForgotPinForm extends Form
{
    #[Validate('required|email|exists:users,email')]
    public $email = '';

    public function sendEmail() {
        $attributes = $this->validate();

         $user = User::where('email', $attributes['email'])->first();
            $tokenCode = TokenCodeHelper::newCode();

            $passwordReset = PasswordReset::where('email', $attributes['email'])->first();
            if($passwordReset != null) {
                $passwordReset->update([
                    'token' => $tokenCode
                ]);
            } else {
                $passwordReset = PasswordReset::create([
                    'email' => $attributes['email'],
                    'token' => $tokenCode
                ]);
            }


            Mail::to($user)->send(new ForgotPasswordRequested($passwordReset));

    }

}
