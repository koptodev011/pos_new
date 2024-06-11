<?php

namespace App\Livewire\Host;

use Livewire\Component;
use App\Models\Order;

class ServingOrders extends Component
{
    public function render()
    {
        $orderData=Order::with('orderItems.orderable.media')
        ->Where('status','Ready')
        ->get();
       
        return view('livewire.Host.serving-orders',[
            'orderData' => $orderData,
        ]);
    }

    public function changeStatus($status,$id){
        
        $order=Order::where('id',$id);
        $order->update([
           'status' =>$status,
        ]);
    }
}
