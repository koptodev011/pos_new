<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Favourite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\MenuHelper;

class FavoriteController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user) {
            $menus = Favourite::with(['menu'=>function($query){
                $query->select('id','name','price','priority');
            },
            'menu.media'])->where('user_id', $user->id)->get();
            return response()->json($menus);
        } else {
            return response()->json([], 200);
        }
    }

    public function addFavorite($menuID)
    {
        $helper = new MenuHelper();    
        
        if ($helper->addFavourite($menuID)) {
            $responseMessage = 'Menu successfully added in Favorites!';
        } else {
            $responseMessage = 'Menu successfully removed from Favorites!';
        }
        
       
        return response()->json([
            'message' => $responseMessage,
            'is_favorite' => $helper->isFavorite($menuID)
        ]);
    }



  
}
