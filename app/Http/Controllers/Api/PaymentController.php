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

    public function waiterTip(Request $request,Order $order)
    {
        $request->validate([
            'option' => 'required', // Add more validation rules if necessary
        ]);
        $option = $request->input('option');
       echo $option;
     
        if ($this->selectedOption == $option) {
            $this->selectedOption = 0;
            $this->tip=0;
        } else {
            $this->selectedOption = $option;
            $this->tip=$option;            
        }
        $id=$order['id'];
        $orderData=$order['summary'];
        // $orderDataArray = json_decode($orderData, true);
        $orderDataArray = $orderData;
       
       
        $summary = [
            'total' => $orderDataArray['total'],
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

      
        $order->update([
            'summary' => $summary

        ]);
        
    }
    public function customTip(Request $request, Order $order)
    {
       
        $request->validate([
            'option' => 'required', // Add more validation rules if necessary
        ]);
        $option = $request->input('option');
        $this->selectOption($request, $order);
    }
    
    

    public function show(Request $request, Order $order)
    {
        echo "fun";
        $order->load(['floorTable', 'orderItems.orderable.media', 'orderPayments', 'orderHistories']);
        return JsonResource::make($order);
        
    }


    public function applyCoupon(Request $request,Order $order)
    {
        
        $request->validate([
            'coupan_code' => 'required', // Add more validation rules if necessary
            'tenant_unit_id' => 'required'
        ]);
        
        // Retrieve the input data
        $userCouponCode = strtolower($request->input('coupan_code'));
        
        $tenantUnitId = $request->input('tenant_unit_id');
        
      
       // Apply the coupon
        $orderHelper = new OrderHelper();
        $orderDetail = $orderHelper->ApiapplyCoupon($userCouponCode, $tenantUnitId,$order);
        if ($orderDetail['error'] == true) {
            return response()->json([
                'success' => false,
                'message' => $orderDetail['message']
            ]);
        } else {
            // Assume the coupon is valid and applied
            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully',
                'order' => $orderDetail
            ]);
        }
    }



    
}
