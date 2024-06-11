<?php

// Payment.php

namespace App\Livewire;

use Livewire\Component;
use App\Helpers\OrderHelper;
use App\Models\Order;

class Payment extends Component
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

    public function selectOption($option)
    {
        $this->customTip=0;
        if ($this->selectedOption == $option) {
            $this->selectedOption = 0;
            $this->tip=0;
        } else {
            $this->selectedOption = $option;
            $this->tip=$option;            
        }

        $order=new OrderHelper();
        $orderData=$order->orderSummary();
        
        $order=Order::find($orderData['orderData']['id']);
        
        $summary = [
            'total' => $orderData['summary']['total'],
            'sub_total' => $orderData['summary']['sub_total'],
            'tax' => [
                'text' => 'Taxes',
                'value' => $orderData['summary']['tax']['value']
            ],
            'discount' => [
                'text' => 'Discount',
                'value' => $orderData['summary']['discount']['value']
            ],
            'promo' => [
                'text' => '',
                'value' => $orderData['summary']['promo']['value']
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


    public function applyCoupon()
    {
        $userCouponCode=strtolower($this->couponCode);
        
        $orderHelper = new \App\Helpers\OrderHelper();
        $order=$orderHelper->applyCoupon($userCouponCode);

        if($order['error'] == true){
            $this->appliedCouponStatus = false;
            $this->couponApplied = false;
            $this->couponCode = '';
            $this->successMessage = $order['message'];
        }else{
            $this->appliedCouponStatus = true;

             // Here you would apply the coupon logic, check validity, etc.
            // For demonstration purposes, let's assume the coupon is valid
            $this->couponApplied = true;
            $this->appliedCoupon = $this->couponCode; // Store the applied coupon code
        }
        $this->dispatch('order-changed');
    }

    public function removeCoupon()
    {
        $orderHelper = new \App\Helpers\OrderHelper();
        $orderHelper->removeCoupon();
        $this->appliedCouponStatus=false;
        // Remove the applied coupon
        $this->couponApplied = false;
        $this->couponCode = ''; // Clear the coupon code input field
    }

    public function render()
    {
        $cartHelper = new \App\Helpers\CartHelper();
        $tenantUnit = $cartHelper->tenantUnit();
        $currency = $tenantUnit->country->getCurrency();

        $orderHelper = new \App\Helpers\OrderHelper();

        $orderData=$orderHelper->orderSummary();
        if($orderData['summary']['promo']['value'] !=0){
            $this->couponApplied = true;
            $this->couponCode = strtoupper($orderData['summary']['promo']['text']);
        }
        $this->selectedOption = $orderData['summary']['tip']['value'];
       
        return view('livewire.payment',[
            'currency' => $currency,
            'orderData' => $orderData
        ]);
    }

    

    // Method to toggle the modal visibility
    public function toggleModal()
    {
        $this->showModal = !$this->showModal;
        if($this->customTip == '' ){
            $this->customTip = 0;
        }
            $order=new OrderHelper();
            $orderData=$order->orderSummary();
            
            $order=Order::find($orderData['orderData']['id']);
            
            $summary = [
                'total' => $orderData['summary']['total'],
                'sub_total' => $orderData['summary']['sub_total'],
                'tax' => [
                    'text' => 'Taxes',
                    'value' => $orderData['summary']['tax']['value']
                ],
                'discount' => [
                    'text' => 'Discount',
                    'value' => $orderData['summary']['discount']['value']
                ],
                'promo' => [
                    'text' => '',
                    'value' => $orderData['summary']['promo']['value']
                ],
                'tip' => [
                    'text' => 'Tip',
                    'value' => $this->customTip
                ],
            ];
            $order->update([
                'summary' => $summary
            ]);
            
        
       
    }

    // Method to handle submission of custom amount (if needed)
    public function submitCustomAmount()
    {
        // Handle submission logic here (if needed)
    }

    public function clearSuccessMessage()
    {
        $this->successMessage = '';
    }

    public function payment(){
        $stripe = new \Stripe\StripeClient('sk_test_51PFC3jSDxrtqp8zZSxA5qptqGcBZsJOgwuj9ILJr43oOzs9grp5nNziZF1ryXbQmXHZfB1Jh1WidGsI2Fk9sWwBE00yameHE4K');
        $orderHelper = new \App\Helpers\OrderHelper();

        $orderData=$orderHelper->orderSummary();
        $listItems=[];
        $taxRate= $orderData['summary']['tax']['value'] / count($orderData['orderData']['orderItems']);
        $tip= $orderData['summary']['tip']['value'] / count($orderData['orderData']['orderItems']);
        foreach($orderData['orderData']['orderItems'] as $item){
            $price = $item['orderable']['price'];
            $totalAmount = ($price + $taxRate + $tip) * 100;
            
            $listItems[]=[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item['orderable']['name'],
                    ],
                    'unit_amount' => $totalAmount,
                ],
                'quantity' => $item['quantity'],
            ];
        }
        
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => [
                $listItems
        ],
                'mode' => 'payment',
                'success_url' => 'http://127.0.0.1:8000/success',
                'cancel_url' => 'http://127.0.0.1:8000/cancel',
            ]);
            
            header("HTTP/1.1 303 See Other");
            
            return redirect($checkout_session->url);
    }

   
    public function cancel(){
        return view('livewire.payment');
    }

   
}
