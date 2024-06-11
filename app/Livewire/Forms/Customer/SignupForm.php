<?php

namespace App\Livewire\Forms\Customer;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\WithFileUploads;


class SignupForm extends Form
{
  
    public $name = '';

    public $email = '';

    public $phone = null;

    public $password = null;

    public $password_confirmation = null;

   
    public $profile_photo_path;

    public $profile_photo_url;


    protected function rules()
    {
        return [
            'name' => 'required|min:2',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->user()->id],
            'phone' => 'nullable|min:6',
            'password' => 'required|string|min:4|confirmed',
            'profile_photo_path' => 'nullable|image|max:1024', // 1MB Max
        ];
    }
    
    public function create()
    {
        $attributes = $this->validate( [
            'name' => 'required|min:2',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'],
            'phone' => 'nullable|min:6',
            'password' => 'required|string|min:4|confirmed',
            'profile_photo_path' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $user = User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'phone' => $attributes['phone'],
            'password' => Hash::make($attributes['password']),
        ]);

        $user->assignRole('Customer');

        if($this->profile_photo_path != null) {
            $path = $this->profile_photo_path->storePublicly(path: 'photos');
            $user->update([
                'profile_photo_path' => $path
            ]);
        }

        Auth::login($user);
        return redirect('/customers/orders/home');

    }

    public function update(){
         $attributes = $this->validate( [
            'name' => 'required|min:2',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->user()->id],
            'phone' => 'nullable|min:6',
        ]);
        $user=Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        if($this->profile_photo_path != null) {
            $image_name = str()->uuid() . '.' . $this->profile_photo_path->extension();
            $path = $this->profile_photo_path->storeAs('photos', $image_name,'public');

            $user->update([
                'profile_photo_path' => $path
            ]);
        }
    }

    public function setProfileData(){
        $this->name  = Auth::user()->name;
        $this->email  = Auth::user()->email;
        $this->phone  = Auth::user()->phone;
        $this->profile_photo_url  = URL :: to('/storage/'.Auth::user()->profile_photo_path);
    }

}
