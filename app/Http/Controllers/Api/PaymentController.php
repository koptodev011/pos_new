<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\OrderHelper;
use App\Helpers\CartHelper;
use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderPayment;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    
    protected $listeners = ['order-changed' => '$refresh'];

    public $selectedTab = 'singlePayment'; // Default to single payment view
    public $showModal = false; // Variable to control modal visibility

    public $couponCode;
    public $couponApplied = false;
    public $appliedCoupon;
    public $appliedCouponStatus = true;
    public $selectedOption = null;
    public $successMessage = '';
    public $tip=0;
    public $customTip=0;

    public function waiterTip(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'Waiter_Tip' => 'required|numeric',
            'floor_table_id' => 'required|numeric|exists:tenant_units,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 400);
        }        

        $Waiter_Tip = $request->input('Waiter_Tip');
    
            if ($this->selectedOption == $Waiter_Tip) {
            $this->selectedOption = 0;
            $this->tip=0;
        } else {
            $this->selectedOption = $Waiter_Tip;
            $this->tip=$Waiter_Tip;    
               
        }
        $orders = Order::with(['floorTable', 'orderItems.orderable.media', 'orderPayments', 'orderHistories'])
        ->where('floor_table_id', $request->floor_table_id)
        ->where('status', '<>','Completed')
        ->orderBy('id','DESC')
        ->first();

      
        $orderData=$orders['summary'];
        $orderDataArray = $orderData;
      
     
        $summary = [
            'total' => $orderDataArray['total']+$Waiter_Tip,
            'sub_total' => $orderDataArray['sub_total'],
            'tax' => [
                'text' => 'Taxes',
                'value' => $orderDataArray['tax']['value']
            ],
            'discount' => [
                'text' => 'Discount',
                'value' => $orderDataArray['discount']['value']
            ],
            'promo' => [
                'text' => '',
                'value' => $orderDataArray['promo']['value']
            ],
            'tip' => [
                'text' => 'Tip',
                'value' => $this->tip
            ],
        ];

        $orders = Order::with(['floorTable', 'orderItems.orderable.media', 'orderPayments', 'orderHistories'])
        ->where('floor_table_id', $request->floor_table_id)
        ->where('status', '<>','Completed')
        ->orderBy('id','DESC')
        ->update(['summary' => json_encode($summary)]);
      
       
        return response()->json([
            'message' => 'Tip applied successfully',
            'success' => true,
            
        ]);
        
    }


    public function customTip(Request $request, Order $order)
    {
       
        $request->validate([
            'option' => 'required', // Add more validation rules if necessary
        ]);
        $option = $request->input('option');
        $this->waiterTip($request, $order);
    }
    
    

    public function show(Request $request, Order $order)
    {
        $order->load(['floorTable', 'orderItems.orderable.media', 'orderPayments', 'orderHistories']);
        return JsonResource::make($order);
        
    }


    public function applyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupan_code' => 'required',
            'tenant_unit_id' => 'required|numeric',
            'floor_table_id' => 'required|numeric|exists:tenant_units,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->all()
            ], 400);
        }
        
        $userCouponCode = strtolower($request->input('coupan_code'));
        $tenantUnitId = $request->input('tenant_unit_id');
        $floor_table_id = $request->input('floor_table_id');
    
        $orderHelper = new OrderHelper();
        $orderDetail = $orderHelper->ApiapplyCoupon($userCouponCode,$tenantUnitId,$floor_table_id);
        if ($orderDetail['error'] == true) {
            return response()->json([
                'message' => $orderDetail['message'],
            ]);
        } else {
            // Assume the coupon is valid and applied
            return response()->json([
                'message' => 'Coupon applied successfully',
            ]);
        }
    }


    public function OrderHistory(Request $request)
    {
        $user = Auth::user();
        $user_id=$user['id'];
        $orders = Order::with(['floorTable', 'orderItems.orderable.media', 'orderPayments', 'orderHistories'])
        ->where('user_id', $user_id)
        ->where('status', 'Completed') // Use single quotes for string values
        ->orderBy('id', 'DESC')
        ->get();
   
         return JsonResource::make($orders);
    }


    // public function render(Request $request,Order $order)
    // {

        // $request->validate([
        //     'tenant_unit_id' => 'required'
        // ]);
        
        // $tenantUnitId = $request->input('tenant_unit_id');
        
        // $cartHelper = new \App\Helpers\CartHelper();
        // $tenantUnit = $cartHelper->tenantUnit();
        // $currency = $tenantUnit->country->getCurrency();



        // $orderHelper = new \App\Helpers\OrderHelper();

        // $orderData=$orderHelper->orderSummary();
        // // echo $orderData;
        // print_r($orderData);

        // $order->load(['floorTable', 'orderItems.orderable.media', 'orderPayments', 'orderHistories']);
        // return JsonResource::make($order);
        
       
        // if($orderData){
        //     if($orderData['summary']['promo']['value'] !=0){
        //         $this->couponApplied = true;
        //         $this->couponCode = strtoupper($orderData['summary']['promo']['text']);
        //     }
        //     $paid_amount = OrderPayment::where('order_id',$orderData['orderData']->id)->sum('amount');
         
        //     $guestPayments= OrderPayment::where('order_id',$orderData['orderData']->id)->get();
        //     if(session()->has('client_key')){
        //         if(count($guestPayments)>0){
        //             $this->selectedTab = 'splitPayment';
        //         }
        //     }elseif($orderData['orderData']['meta']['type'] == 'splitPayment'){
        //         $this->selectedTab = 'splitPayment';
        //     }else{
        //         $this->selectedTab = 'singlePayment';
        //     }
           
        //     $this->selectedOption = $orderData['summary']['tip']['value'];
        //     return view('livewire.payment',[
        //         'currency' => $currency,
        //         'orderData' => $orderData,
        //         'guestPayments' => $guestPayments,
        //         'paid_amount' => $paid_amount,
        //     ]);
        // }else{
        //     return view('livewire.payment',[
        //         'currency' => $currency,
        //         'orderData' => $orderData
        //     ]);
        // }
        
      
      
    // }



    // public function render()
    // {
    //     if(auth()->user()) {
    //         $menus = Order::with('orderItems.orderable.media')
    //             ->where('user_id', auth()->user()->id)
    //             ->where('status', 'Placed')
    //             ->orWhere('status', 'Preparing')
    //             ->orWhere('status', 'Ready')
    //             ->first();
    //     } else {
    //         $cartHelper = new CartHelper();
    //         $cart = $cartHelper->sessionCart();
    //         $menus = Order::with('orderItems.orderable.media')
    //             ->where('floor_table_id', $cart->floor_table_id)
    //             ->where('tenant_unit_id', $cart->floorTable->tenant_unit_id)
    //             ->where('status', 'Placed')
    //             ->orWhere('status', 'Preparing')
    //             ->orWhere('status', 'Ready')
    //             ->first(); 
    //     }
       
    //     $sub_total = 0;
    //     $total = 0;
    //     $paid_amount = 0;

    //     if($menus) {
    //         $paid_amount = $menus->orderPayments()->sum('amount');
    //         foreach ($menus->orderItems as $order_item) {
    //             $sub_total += $order_item->quantity * $order_item->orderable->applied_price;
    //             }
                
    //             $sub_total = round($sub_total, 2);
    //             $tax = round($sub_total * 0.05, 2);
    //             $discount = 0;
    //             $promo = 0;
                
    //             $total = round(($sub_total + $tax) - ($discount + $promo), 2) - $paid_amount;
                
    //     }

    //     return response()->json([
    //         'menus' => $menus,
    //         'total' => $total,
    //     ]);
    // }


    // public function render()
    // {
        // $cartHelper = new \App\Helpers\CartHelper();
        // $tenantUnit = $cartHelper->tenantUnit();
        // $currency = $tenantUnit->country->getCurrency();

    //     $orderHelper = new \App\Helpers\OrderHelper();

    //     $orderData = $orderHelper->orderSummary();
    //     dd($orderData);
    //     $response = [];
       
    //     if($orderData){
    //         if($orderData['summary']['promo']['value'] !=0){
    //             $response['couponApplied'] = true;
    //             $response['couponCode'] = strtoupper($orderData['summary']['promo']['text']);
    //             dd($orderData['summary']['promo']['text']);
    //         }
    //         $paid_amount = OrderPayment::where('order_id', $orderData['orderData']->id)->sum('amount');
         
    //         $guestPayments = OrderPayment::where('order_id', $orderData['orderData']->id)->get();

    //         if(session()->has('client_key')){
    //             if(count($guestPayments) > 0){
    //                 $response['selectedTab'] = 'splitPayment';
    //             }
    //         }elseif($orderData['orderData']['meta']['type'] == 'splitPayment'){
    //             $response['selectedTab'] = 'splitPayment';
    //         }else{
    //             $response['selectedTab'] = 'singlePayment';
    //         }
           
    //         $response['selectedOption'] = $orderData['summary']['tip']['value'];
    //         $response['currency'] = $currency;
    //         $response['orderData'] = $orderData;
    //         $response['guestPayments'] = $guestPayments;
    //         $response['paid_amount'] = $paid_amount;
    //     }

    //     return response()->json($response);
    // }


    public function paymentDetails()
    {
        try {
            // $cartHelper = new CartHelper();
            // $tenantUnit = $cartHelper->tenantUnit();
            // $currency = $tenantUnit->country->getCurrency();


       $orderHelper = new OrderHelper();
            $orderData = $orderHelper->PaymentSummary();

           
            $response = [
                // 'currency' => $currency,
                'orderData' => $orderData
            ];

            

            if ($orderData['summary']['promo']['value'] != 0) {
                $response['couponApplied'] = true;
                $response['couponCode'] = strtoupper($orderData['summary']['promo']['text']);
               
            }

            $response['selectedOption'] = $orderData['summary']['tip']['value'];

            return response()->json($response);
           
        } catch (\Exception $e) {
           
        }
    }



    
}
