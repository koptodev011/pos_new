<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;



use Illuminate\Support\Facades\Mail;


class ProfileController extends Controller
{
    public function ProfileData()
    {
        $user = Auth::user();
        if ($user) {
           
            $userData = [
                'name' => $user->name,
                'email'=>$user->email,
                'phone'=>$user->phone,
             
            ];
            return response()->json($userData);
        } else {
            return response()->json([], 200);
        }
    }




    public function update(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized' , 'Statuc code'=>401], 401);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:10',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);
        
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),'Statuc code'=>400], 400);
        }
    
        if ($request->hasFile('profile_photo_path')) {
            $profilePhoto = $request->file('profile_photo_path');
            $profilePhotoName = time() . '_' . $profilePhoto->getClientOriginalName();
            $profilePhoto->move(public_path('profile_photos'), $profilePhotoName);
            $profilePhotoPath = 'profile_photos/' . $profilePhotoName;
            $user->profile_photo_path = $profilePhotoPath;
        }
    
        // Update user details
        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->phone = $request->input('phone', $user->phone);
    
        // Save updated user data
        $user->save();
    
        // Generate profile photo URL
        $profilePhotoUrl = $user->profile_photo_path ? asset($user->profile_photo_path) : null;
    
        // Sending success response
        return response()->json([
            'message' => 'User details updated successfully',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'profile_photo_url' => $profilePhotoUrl,
            ],
        ]);
    }
    
    }
