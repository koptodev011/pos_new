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
                'profile_photo_url'=>$user->profile_photo_url
            ];
            return response()->json($userData);
        } else {
            return response()->json(['message'=>"User is not loged In"], 404);
        }

    }



    public function update(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['error' => 'Unauthorized' , 'Status code' => 401], 401);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|regex:/^[^\d]+$/',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|numeric|digits:10',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),'Status code' => 400], 400);
        }
    
        if ($request->hasFile('profile_photo')) {
            // Delete old profile photo if it exists
            // if ($user->profile_photo_path) {
            //     Storage::delete($user->profile_photo_path);
            // }
    
            $profilePhoto = $request->file('profile_photo');
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
    


    public function OrderHistory(Request $request)
    {
        $user = Auth::user();
        $user_id=$user['id'];
        $orders = Order::with(['floorTable', 'orderItems.orderable.media', 'orderPayments', 'orderHistories'])
        ->where('user_id', $user_id)
        ->where('status', 'Completed') // Use single quotes for string values
        ->orderBy('id', 'DESC')
        ->get();
   
         return JsonResource::make($orders);
    }


  
    }
