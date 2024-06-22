<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Helpers\OrderHelper;
use App\Helpers\CartHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BillRequested;
use Barryvdh\DomPDF\Facade\Pdf;



class OrderHistory extends Controller
{
    public function orderHistory()
    {
        $orderHelper = new OrderHelper();
        $orderData = $orderHelper->orderHistory();

        return response()->json([
            'orderData' => $orderData,
        ]);
    }


    public function sendEBill(Request $request)
    {
      
        try {
            $attributes=$request->validate([
                'id' => ['required'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['errors' => $errors], 400);
        }
        $id = $request->input('id');
       
        $user = Auth()->user()->email;
       
        $orderHelper = new OrderHelper();
        $orderSummary = $orderHelper->orderDetails($id);
    
        // Mail::to($user)->send(new BillRequested($orderSummary));
        return response()->json(['message' => 'eBill sent successfully']);
        dd();
    }

    public function download(Request $request) {
        
        try {
            $attributes=$request->validate([
                'id' => ['required'],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->getMessages();
            return response()->json(['errors' => $errors], 400);
        }

        $id = $request->input('id');
        $orderHelper=new OrderHelper();
        $orderData=$orderHelper->BillDetails($id);
        
       
        // $cartHelper = new \App\Helpers\CartHelper();
        // $tenantUnit = $cartHelper->tenantUnit();
        // $currency = $tenantUnit->country->getCurrency();

       $data=[
        'orderData' => $orderData,
        'type' => 'pdf'
       ];
  
   
    $pdf = Pdf::loadView('mail.bill', $data);
    
    return $pdf->download('invoice.pdf');
      
    }
   
}
