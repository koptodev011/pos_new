<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Menu as MenuModel;
use App\models\TenantUnit;
use App\Models\FloorTable;
use App\Models\User;
use App\Models\Favourite;
use App\Helpers\CartHelper;
use Illuminate\Support\Facades\Auth;


class Favorite extends Component
{

    public $hideHeader = false;
    public $hideNavbar = false;
    protected ?User $user;

    protected $listeners = ['refreshFavorites' => '$refresh'];

    public function render(){
        $this->user = Auth::user();
        if($this->user){
            $cartHelper = new \App\Helpers\CartHelper();
            $tenantUnit = $cartHelper->tenantUnit();
            $currency = $tenantUnit->country->getCurrency();
            $menus=Favourite::with('menu.media')->where('user_id', $this->user->id)->get();
            return view('livewire.favorite', [
                'menus'=>$menus,
                'currency'=>$currency,
            ]);
        }else{
            $menus=[];
            $currency='';
            return view('livewire.favorite', [
                'menus'=>$menus,
                'currency'=>$currency,
            ]);
        }
    }
}
