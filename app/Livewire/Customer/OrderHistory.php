<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Helpers\OrderHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\BillRequested;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderHistory extends Component
{
    public function render()
    {
        $orderHelper=new OrderHelper();
        $orderData=$orderHelper->orderHistory();

        $cartHelper = new \App\Helpers\CartHelper();
        $tenantUnit = $cartHelper->tenantUnit();
        $currency = $tenantUnit->country->getCurrency();

    
        return view('livewire.Customer.Componant.order-history',[
            'orderData' => $orderData,
            'currency' => $currency
        ]);
    }

    public function sendEBill($id)
    {
        $user=Auth()->user()->email;
        $orderHelper=new OrderHelper();
        $orderSummary=$orderHelper->orderDetails($id);

        $cartHelper = new \App\Helpers\CartHelper();
        $tenantUnit = $cartHelper->tenantUnit();
        $currency = $tenantUnit->country->getCurrency();

       
       Mail::to($user)->send(new BillRequested($orderSummary,$currency));
    }

    public function download($id) {

        $orderHelper=new OrderHelper();
        $orderData=$orderHelper->orderDetails($id);
       
        $cartHelper = new \App\Helpers\CartHelper();
        $tenantUnit = $cartHelper->tenantUnit();
        $currency = $tenantUnit->country->getCurrency();

       $data=[
        'orderData' => $orderData,
        'type' => 'pdf'
       ];
   
    $pdf = Pdf::loadView('mail.bill', $data);
    return $pdf->download('invoice.pdf');
      
    }
}
