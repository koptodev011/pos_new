<?php

namespace App\Http\Controllers\Api;

use App\Helpers\TokenCodeHelper;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordRequested;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'device_name' => ['required', 'string', 'min:3']
        ]);

        

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return [
            'data' => $token
        ];

    }

    public function forgotPassword(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::exists('users', 'email')],
        ]);
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

        return new JsonResource([
            'message' => 'A code has been sent to your email address'
        ]);

    }

    public function resetPassword(Request $request)
    {

        $attributes = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::exists('users', 'email')],
            'token' => ['required'],
            'password' => ['required', 'min:6', 'confirmed']
        ]);


        $user = User::where('email', $attributes['email'])->first();
        $passwordReset = PasswordReset::where('email', $user->email)->first();
        if($passwordReset == null) {
            return response()->json([
                'message' => 'Token mismatch'
            ], 400);
        }

        if($passwordReset->token != $attributes['token']) {
            return response()->json([
                'message' => 'Token mismatch'
            ], 400);
        }

        $user->fill([
            'password' => Hash::make($attributes['password'])
        ]);

        $user->save();

        $user->tokens()->delete();

        $passwordReset->delete();

        return JsonResource::make([
            'message' => 'Password has been changed. Please Relogin'
        ]);

    }

    public function changePassword(Request $request)
    {

        $attributes = $request->validate([
            'old_password' => ['required', 'min:6'],
            'password' => ['required', 'min:6', 'confirmed']
        ]);

        if($attributes['old_password'] == $attributes['password']) {
            return response()->json([
                'message' => 'New password should be different than old password'
            ], 400);
        }

        $user = $request->user();
        if(!Hash::check($attributes['old_password'], $user->password)) {

            return response()->json([
                'message' => 'Credentials are not valid'
            ], 400);

        }

        $user->fill([
            'password' => $attributes['password']
        ]);
        $user->save();

        $user->tokens()->delete();

        return JsonResource::make([
            'message' => 'Password has been changed. Please Relogin'
        ]);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return [
            'data' => 'success'
        ];
    }

    public function profile(Request $request)
    {
        $cart_helper = new \App\Helpers\CartHelper($request);
        $cart = $cart_helper->fetchCart();
        $currency =$cart_helper->currency();
        return JsonResource::make([
            'user' => $request->user(),
            'cart' => $cart,
            'currency' => $currency
        ]);
    }
}
