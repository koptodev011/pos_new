<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Menu as MenuModel;
use App\models\TenantUnit;
use App\Models\FloorTable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;
class MenuController extends Controller
{
    public function index(Request $request)
    {
        $list = Menu::query()->with('menuCategories')->get()->append(['tagNames', 'images']);
        return JsonResource::make($list);
    }

    public function index1(Request $request)
    {
        $list = Menu::query()->with('menuCategories')->get()->append(['tagNames', 'images']);
        return JsonResource::make($list);
    }


 

    public function getMenuData(Request $request)
    {
        $tenantUnitId = $request->input('tenant_unit_id');
        session()->put($tenantUnitId);
        session()->put('floor_table_id',1);
        $cartHelper = new \App\Helpers\CartHelper();    
        $tenantUnit = $cartHelper->tenantUnit();
      
        $categories = Category::whereHas('tenantUnits', function (Builder $query) use ($tenantUnitId) {
            $query->where('tenant_unit_id', $tenantUnitId);
        })->get();

        $menuData = [];
        $tags = ['Recommended', 'Hottest Offers', 'Hot Selling'];
        foreach ($tags as $tag) {
            $menuData[$tag] = MenuModel::withAnyTags([$tag], 'menu')->whereHas('tenantUnits', function ($query) use ($tenantUnitId) {
                $query->where('tenant_unit_id', $tenantUnitId);
            })->get();
         
        }
        $list = Menu::query()->with('menuCategories')->get()->append(['tagNames', 'images']);
        $cart_key = session()->get('cart_key');
      
        $data=[];
        $data['menus']=$menuData;
        $data['categories']=$categories;
        $data['allMenu']=$list;
        $data['cart_key']=$cart_key;
        return JsonResource::make($data);
    }


    public function getUserFavorites(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $menus = Favourite::with('menu.media')->where('user_id', $user->id)->get();

        return response()->json(['favorites' => $menus]);
    }
}
