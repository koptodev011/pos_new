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
use App\Models\Menu;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Menu as MenuModel;
use App\models\TenantUnit;
use App\Models\FloorTable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Crypt;
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


public function paymentDetails()
{
    try {
        $orderHelper = new OrderHelper();
        $orderData = json_decode($orderHelper->PaymentSummary(), true);
        $response = [
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



public function payment(){
    // $cartHelper = new \App\Helpers\CartHelper();
    // $tenantUnit = $cartHelper->tenantUnit();
    // $currency = $tenantUnit->country->getCurrency();


    $orderHelper = new \App\Helpers\OrderHelper();

    $orderData=$orderHelper->orderSummary();

    $paid_amount = OrderPayment::where('order_id',$orderData['orderData']->id)->sum('amount');

    $listItems=[];

    // $totalAmount = ($orderData['summary']['total'] + $orderData['summary']['tip']['value'] - $paid_amount) * 100;
    if($orderData['orderData']['meta']['type'] == 'splitPayment'){
        $totalAmount = ( ($orderData['summary']['total'] + $orderData['summary']['tip']['value']) / $orderData['orderData']['diners']) * 100;
    }else{
        if(Auth::user() && session()->has('client_key') && $orderData['summary']['loyalty']['text']==Auth::user()->id){
            $totalAmount = (($orderData['summary']['total'] -$orderData['summary']['discount']['value'] - $orderData['summary']['loyalty']['value'] ) + $orderData['summary']['tip']['value'] - $paid_amount) * 100;

        }else{

            $totalAmount = ($orderData['summary']['total'] + $orderData['summary']['tip']['value'] - $paid_amount) * 100;
        }
    }
    
 $selectedMode="Online";
    $encryptedOrderId = Crypt::encryptString($orderData['orderData']->id);
  
    $encryptedTotalAmount = Crypt::encryptString($totalAmount);
 
    // $encryptedMode = Crypt::encryptString($this->$selectedMode);
    // dd();
    if(Auth()->user()){
        $encryptedUser=Crypt::encryptString(Auth()->user()->name);
    
    }else{
        $encryptedUser=Crypt::encryptString('guest');

    }

    if($this->selectedMode == 'Online'){
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

    $listItems[]=[
        'price_data' => [
            // 'currency' => $currency->code,
            'currency' => 'usd',
            'product_data' => [
                'name' => 'partial payment',
            ],
            'unit_amount' => $totalAmount,
        ],
        'quantity' => 1,
    ];

    $checkout_session = $stripe->checkout->sessions->create([
        'line_items' => [
            $listItems
        ],
            'mode' => 'payment',
            'success_url' => 'http://127.0.0.1:8000/success/'.$encryptedOrderId.'/'.$encryptedTotalAmount . '/' .$encryptedUser . '/' . $encryptedMode,
            'cancel_url' => 'http://127.0.0.1:8000/cancel',  
        ]);
        
        header("HTTP/1.1 303 See Other");
        
        return redirect($checkout_session->url);
    }
    else{
        $url='http://127.0.0.1:8000/success/'.$encryptedOrderId.'/'.$encryptedTotalAmount . '/' .$encryptedUser . '/' .$encryptedMode;
        return redirect($url);
        
    }
    
}






public function paymentDetails123(Request $request)
{
     $validatedData = $request->validate([
        'floor_table_id' => 'required|integer',
        'tenant_unit_id' => 'required|integer',
        'client_key'=>'required'
    ]);
    $floorTableId = $validatedData['floor_table_id'];
    $tenantUnitId = $validatedData['tenant_unit_id'];
    $client_key = $validatedData['client_key'];
    // $cartHelper = new CartHelper();
    // $tenantUnit = $cartHelper->tenantUnit();
    // $currency = $tenantUnit->country->getCurrency();

    $orderHelper = new OrderHelper();
    // $loyaltyPointsDetails = $orderHelper->loyaltyPointsDetails($tenantUnit->id);

    $orderData = $orderHelper->paymentDetailsSummary($floorTableId,$tenantUnitId);
 
   
    $guestPayments = [];
    $paid_amount = 0;
    $selectedTab = 'singlePayment';
    
    if ($orderData) {
        if ($orderData['summary']['promo']['value'] != 0) {
            $couponApplied = true;
            $couponCode = strtoupper($orderData['summary']['promo']['text']);
        }

        // if (Auth::user()) {
        //     if ($orderData['summary']['loyalty']['text'] == Auth::user()->id && $orderData['summary']['loyalty']['value'] > 0) {
        //         $loyaltyApplied = true;
        //     }
        // }

        $paid_amount = OrderPayment::where('order_id', $orderData['orderData']->id)->sum('amount');
   
        $guestPayments = OrderPayment::where('order_id', $orderData['orderData']->id)->get();
        $cartHelper = new CartHelper();
        // $client_key = session()->get('client_key');
      
        // if (session()->has('client_key')) {
        //     if (count($guestPayments) > 0) {
        //         $selectedTab = 'splitPayment';
        //     }
        //     echo "True";
        // } elseif ($orderData['orderData']['meta']['type'] == 'splitPayment') {
        //     $selectedTab = 'splitPayment';
        //     echo "False";
        // }

        if($client_key){
            if (count($guestPayments) > 0) {
                $selectedTab = 'splitPayment';
                    }
                } elseif ($orderData['orderData']['meta']['type'] == 'splitPayment') {
                    $selectedTab = 'splitPayment';
        }
        
        $selectedOption = $orderData['summary']['tip']['value'];
       

        return response()->json([
            // 'currency' => $currency,
            'orderData' => $orderData,
            'guestPayments' => $guestPayments,
            'paid_amount' => $paid_amount,
            // 'loyaltyDetails' => $loyaltyPointsDetails,
            'selectedTab' => $selectedTab,
            'selectedOption' => $selectedOption,
            'couponApplied' => $couponApplied ?? false,
            'couponCode' => $couponCode ?? null,
            // 'loyaltyApplied' => $loyaltyApplied ?? false,
        ]);
    } else {
        return response()->json([
            // 'currency' => $currency,
            'orderData' => $orderData,
            'guestPayments' => $guestPayments,
            // 'loyaltyDetails' => $loyaltyPointsDetails,
        ]);
    }
}


}
