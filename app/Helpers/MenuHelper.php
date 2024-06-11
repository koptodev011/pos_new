<?php

namespace App\Helpers;

use App\Livewire\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Models\Favourite;
use App\Models\User;

class MenuHelper
{
    protected ?User $user;
    public function isFavorite($value)
    {
        
            $this->user = Auth::user();

            return $this->user==null ? false : Favourite::where('user_id', $this->user->id)->where('menu_id', $value)->exists() ;
       
    }

    public function addFavourite($value){
            $this->user = Auth::user();
            $favourite = Favourite::where('user_id', $this->user->id)->where('menu_id', $value)->first();
            if($favourite){
                $favourite->delete();
                return false;
            }else{
                Favourite::create([
                'user_id' => $this->user->id,
                'menu_id' => $value
            ]);
            return true;
        
        }
    }

}
