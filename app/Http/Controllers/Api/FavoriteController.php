<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\MenuHelper;
use Illuminate\Support\Facades\Validator;
use App\Models\Menu;

class FavoriteController extends Controller
{
    // public function index()
    // {
    //     $user = Auth::user();
    //     if ($user) {
    //         $menus = Favourite::with(['menu'=>function($query){
    //             $query->select('id','name','price','priority');
    //         },
    //         'menu.media'])->where('user_id', $user->id)->toSql();
    //         return response()->json($menus);
    //     } else {
    //         return response()->json([], 200);
    //     }
    // }

   
    // public function index()
    // {
       
    //     $user = Auth::user();
   
    //     if ($user) {
    //        $menus=Favourite::with('menu.media')->where('user_id', $this->user->id)->get();
    //        echo($menus);
    //        dd();
    //         // Transform the data using API resources
    //         $menusData = FavouriteResource::collection($menus);
    
    //         // Return JSON response
    //         return response()->json([
    //             'menus' => $menusData,
                
    //         ]);
    //     } else {
    //         return response()->json([
    //             'error' => 'Unauthenticated',
    //             'message' => 'User is not authenticated'
    //         ], 401);
    //     }
    // }
    





    // public function addFavorite($menuID)
    // {
      
    //     $helper = new MenuHelper();    
        
    //     if ($helper->addFavourite($menuID)) {
    //         $responseMessage = 'Menu successfully added in Favorites!';
    //     } else {
    //         $responseMessage = 'Menu successfully removed from Favorites!';
    //     }
        
       
    //     return response()->json([
    //         'message' => $responseMessage,
    //         'is_favorite' => $helper->isFavorite($menuID)
    //     ]);
    // }

    

    public function addFavorite($menuID)
{
    $validator = Validator::make(['menu_id' => $menuID], [
        'menu_id' => 'required|numeric|exists:menus,id',
    ]);
    
    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()->first(),'Status Code'=>400], 400);
    }
    $helper = new MenuHelper();    
    if ($helper->addFavourite($menuID)) {
        $responseMessage = 'Menu successfully added to Favorites!';
    } else {
        $responseMessage = 'Menu successfully removed from Favorites!';
    }
    
    return response()->json([
        'message' => $responseMessage,
        'is_favorite' => $helper->isFavorite($menuID)
    ]);
}


  
}
