<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return JsonResource::make(User::query()->get());
    }

    public function save(Request $request){

        $roleName =  $request->input('role');


        $attributes = $request->validate([
            'name' => ['required', 'min:1', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'min:12'],
            'phone' => ['required', 'min:10'],

        ]);

        $createdUser = User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => bcrypt($attributes['password']),
            'phone' => $attributes['phone']
        ]);
        $createdUser->assignRole($roleName);


        return JsonResource::make($createdUser);
    }
    public function roles(Request $request){
        return JsonResource::make(Role::query()->get());
    }
    public function add_role(Request $request){
        $attributes = $request->validate([
            'name' => ['required', 'min:1', 'max:255']
        ]);

        $attributes['guard_name'] = 'web';

        $entity = Role::create($attributes);

        return JsonResource::make($entity);
    }
}
