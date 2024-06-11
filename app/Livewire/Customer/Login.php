<?php

namespace App\Livewire\Customer;

use App\Livewire\Forms\Customer\ForgotPinForm;
use App\Livewire\Forms\Customer\LoginForm;
use App\Livewire\Forms\Customer\ResetPinForm;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;


class Login extends Component
{
    public $showForgotPwdDialog = false;
    public $showResetPwdDialog = false;

    public LoginForm $login_form;

    public ForgotPinForm $forgot_pin_form;

    public ResetPinForm $reset_pin_form;

    public function openForgotPinModal()
    {
        $this->showForgotPwdDialog = true;
    }

    public function login()
    {
        $attributes = $this->login_form->login();
        $user = User::where('email', $attributes['email'])->first();
        if (! $user || ! Hash::check($attributes['password'], $user->password)) {
            throw ValidationException::withMessages([
               'login_form.email' => ['The provided credentials are incorrect.'],
            ]);
            return;
        }

        Auth::login($user);
        $cartHelper = new \App\Helpers\CartHelper();
        $cart = $cartHelper->sessionCart();
        $cart['user_id']=Auth::user()->id;
        return redirect('/customers/orders/home');

    }

    public function sendResetPin()
    {
        $this->forgot_pin_form->sendEmail();
        $this->showForgotPwdDialog = false;
        $this->showResetPwdDialog = true;
    }

    public function submitPin()
    {
        $this->reset_pin_form->submitPin();
        $this->showResetPwdDialog = false;

        return redirect()->back();

    }

    public function render()
    {
        return view('livewire.customer.login');
    }
}
