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


    public function sendEBill($id)
    {
        $user = Auth()->user()->email;
        $orderHelper = new OrderHelper();
        $orderSummary = $orderHelper->orderDetails($id);

        dd($orderSummary);
        Mail::to($user)->send(new BillRequested($orderSummary));

        return response()->json(['message' => 'eBill sent successfully']);
    }



    
}
