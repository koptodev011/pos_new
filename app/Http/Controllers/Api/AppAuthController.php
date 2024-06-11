<?php

namespace App\Http\Controllers\API;
use App\Helpers\TokenCodeHelper;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordRequested;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;

class AppAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable',
            'password' => 'required|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $profilePhotoPath = null;
        

        //Here we get images and hash it and pass to store to the database
        if ($request->hasFile('profile_photo_path')) {
            $profilePhoto = $request->file('profile_photo_path');
            $profilePhotoName = time() . '_' . $profilePhoto->getClientOriginalName();
            $profilePhoto->move(public_path('profile_photos'), $profilePhotoName);
            $profilePhotoPath = 'profile_photos/' . $profilePhotoName;
        }

        $hashedPassword = Hash::make($request->password);
        
        // Create a new user instance
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $hashedPassword,
            'profile_photo_path' => $profilePhotoPath,
        ]);

        //Here we save the data to the database
        $user->save();
        //Sending response
        return response()->json(['message' => 'User registered successfully'], 200);
    }


    public function login(Request $request)
    {
        // dd($request);

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string',], 
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
       if($validator){
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
           return response()->json(['message' => 'Login failed.', 'success' => false, 'response' => 401], 401);
        } else {
            // $token = $user->createToken('MyApp')->accessToken;
            $token = $user->createToken($user->id)->plainTextToken;
            return response()->json(['token' => $token, 'success' => true, 'response' => 200], 200);
        } 
       }

       
    }
    

    public function forgotPassword(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required', 'email', 'max:255', Rule::exists('users', 'email')],
        ]);
        $user = User::where('email', $attributes['email'])->first();
    
        $tokenCode = TokenCodeHelper::newCode();

        $passwordReset = PasswordReset::where('email', $attributes['email'])->first();
        
        if ($passwordReset != null) {
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



    public function changePassword(Request $request)
    {
        $attributes = $request->validate([
            'old_password' => ['required'],
            'password' => ['required', 'confirmed']
        ]);

        if ($attributes['old_password'] == $attributes['password']) {
            return response()->json([
                'message' => 'New password should be different than old password'
            ], 400);
        }

        $user = $request->user();
        
        if (!Hash::check($attributes['old_password'], $user->password)) {
            return response()->json([
                'message' => 'Credentials are not valid'
            ], 400);
        }

        $user->fill([
            'password' => Hash::make($attributes['password'])
        ])->save();

        $user->tokens()->delete();

        return JsonResource::make([
            'message' => 'Password has been changed. Please relogin'
        ]);
    }

}
