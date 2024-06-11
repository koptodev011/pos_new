<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Order;
use App\Enums\OrderStatus;



class Success extends Component
{

    public function render()
    {
        $orderHelper = new \App\Helpers\OrderHelper();
        $orderData=$orderHelper->orderSummary();
        $order_id=$orderData['orderData']['id'];
        $order=Order::where('id',$order_id)->first();
       
        $order->update([
           'status' =>OrderStatus::Completed,
        ]);
        

        return view('livewire.success');
    }

}
