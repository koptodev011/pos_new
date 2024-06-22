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
        $roleName =  "Customer";
        $validator = Validator::make($request->all(),[
            'name' =>'required|string|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|numeric|digits:10',
            'password' => 'required|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',    
        ]);
       if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $profilePhotoPath = null;
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

        $user->assignRole($roleName);
        $user->save();
        
        return response()->json(['message' => 'User registered successfully'], 200);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string',], 
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
       if($validator){
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
           return response()->json(['message' => 'Login failed.', 'status' => "Failed"], 400);
        } else {
            $token = $user->createToken($user->id)->plainTextToken;
            return response()->json(['token' => $token, 'status' => "Success"], 200);
        } 
       }
    }
    

    public function sendOtp(Request $request)
    {
        try {
            $attributes = $request->validate([
                'email' => ['required', 'email', 'max:255', Rule::exists('users', 'email')],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['errors' => $errors], 400);
        }
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
            'message' => 'Otp Sent Successfully',
            'PasswordReset'=> $passwordReset
        ]);
    }

    public function veryfyOtp(Request $request){
        try {
            $attributes=$request->validate([
                'otp'=>['required','numeric','max:4'],
                'email' => ['required', 'email', 'max:255', Rule::exists('users', 'email')],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['errors' => $errors], 400);
        }
        $passwordReset = PasswordReset::where('email', $attributes['email'])->first();
        if($attributes['email']==$passwordReset['email'] && $attributes['otp']==$passwordReset['token']){
            return response()->json(['message' => 'OTP Verified Successfully'],200);
        }else{
            return response()->json(['message' => 'Invalid email or otp'],400);
        }
    }

public function resetPassword(Request $request){
    try {
        $attributes=$request->validate([
            'email' => ['required', 'email', 'max:255', Rule::exists('users', 'email')],
            'password' => ['required', 'min:6', 'confirmed']
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        $errors = $e->validator->errors()->getMessages();
        return response()->json(['errors' => $errors], 400);
    }
    
    $user = User::where('email', $attributes['email'])->first();
    $passwordReset = PasswordReset::where('email', $attributes['email'])->first();
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
        try {
            $attributes=$request->validate([
                'old_password' => ['required'],
                'password' => ['required', 'confirmed']
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['errors' => $errors], 400);
        }

       
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


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return [
            'message'=>"Logout",
            'data' => 'success'
        ];
    }

}
