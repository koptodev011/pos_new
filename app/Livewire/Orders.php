<?php

namespace App\Livewire;
use App\Models\Menu as MenuModel;
use App\models\TenantUnit;
use App\Models\FloorTable;
use App\Helpers\CartHelper;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class Orders extends Component
{
    public FloorTable $floorTable;
    public $currency;
    public $summary;

    public function render()
    {
       if(Auth()->user()){
        $menus=Order::with('orderItems.orderable.media')
        ->where('user_id',Auth::user()->id)
        ->where('status','Placed')
        ->orWhere('status','Preparing')
        ->orWhere('status','Ready')
        ->first();
       
        $sub_total = 0;
        $total = 0;
      
        if($menus){
            foreach ($menus->orderItems as $order_item) {
                $sub_total += $order_item->quantity * $order_item->orderable->applied_price;
            }
            $sub_total = round($sub_total, 2);
            $tax = round($sub_total * 0.05, 2);
            $discount = 0;
            $promo = 0;
    
            $total = round(($sub_total + $tax) - ($discount + $promo), 2);
        }
    }else{
        $menus=[];
        $total=0;
    }
   
       
        return view('livewire.orders',
        [
            'menus'=>$menus,
            'total'=>$total,

        ]);
    }

    public function mount(?FloorTable $floorTable)
    {
        $this->floorTable = $floorTable;
        $cartHelper = new CartHelper();
        $cartHelper->init($this->floorTable);


        $tenantUnit = $cartHelper->tenantUnit();
        $this->currency = $tenantUnit->country->getCurrency();
        $cart=$cartHelper->sessionCart();
        $this->summary=$cartHelper->cartSummary($cart);
    }
    public function navigateToPayment()
    {
        // Navigate to the "favorite" route
        return redirect()->to(route('payment'));
    }
}
