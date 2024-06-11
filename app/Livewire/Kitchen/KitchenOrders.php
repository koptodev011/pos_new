<?php

namespace App\Livewire\Kitchen;

use Livewire\Component;
use App\Helpers\OrderHelper;
use App\Models\Order;
use App\Enums\OrderStatus;


class KitchenOrders extends Component
{
  
    public function render()
    {
       
        $orderData=Order::with('orderItems.orderable.media')
        ->where('status','Placed')
        ->orWhere('status','Preparing')
        ->get();
       
        return view('livewire.Kitchen.kitchen-orders', [
            'orderData' => $orderData
        ]);
    }

    public function changeStatus($status,$id){
        
        $order=Order::where('id',$id);
        $order->update([
           'status' =>$status,
        ]);
    }
}
