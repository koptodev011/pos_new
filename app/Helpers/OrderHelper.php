<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\PromoCode;
use App\Models\PromoCodeUsers;
use App\Models\TenantUnit;
use illuminate\Database\Eloquent\Builder;


class OrderHelper
{
    
    protected static function prefix()
    {
        return '#';
    }

    public static function nextOrderNo()
    {
        $max = DB::table('orders')->max('id') + 1;
        return static::prefix() . sprintf('%05d', $max);
    }

    public static function newCode()
    {
        //return rand(100000, 999999);
        // if (env('STATIC_VERIFICATION_CODE', false)) {
        //     return env('STATIC_VERIFICATION_CODE_VALUE', 811812);
        // }
        // return rand(100000, 999999);
        return str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function orderSummary()
    {
        $orderData=Order::with('orderItems.orderable.media')->where('user_id',Auth::user()->id)
        ->where('status','!=','Completed')
        ->first();
    
        return [
            'orderData' => $orderData,
            'summary' => [
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
                    'text' => $orderData['summary']['promo']['text'],
                    'value' => $orderData['summary']['promo']['value']
                ],
                'tip' => [
                    'text' => 'Tip',
                    'value' => $orderData['summary']['tip']['value']
                ],
            ],
           
        ];
    }

    public function applyCoupon($code){
        

        $cartHelper = new \App\Helpers\CartHelper();
        $tenantUnitID = $cartHelper->tenantUnitID();

        $currentDate=now();

        $orderData=$this->orderSummary();
       
        $total=$orderData['summary']['total'];
        $discount=0;
       
        $coupon = PromoCode::whereHas('tenantUnits', function (Builder $query) use ($tenantUnitID) {
            $query->where('tenant_unit_id', $tenantUnitID);
        })
        ->where('code',$code)
        ->where('active',1)
        ->where('min_value','<',$total)
        ->where('max_value','<',$total)
        ->whereDate('start_date','<=',$currentDate)
        ->whereDate('end_date','>=',$currentDate)
        ->first();
        if($coupon){
            $promoCodeUser=PromoCodeUsers::where('user_id', Auth::user()->id)->where('promo_code_id', $coupon->id)->first();
            
        

            if($promoCodeUser){
                
                return [
                    'error' => true,
                   'message' => 'You have already used this promocode'
                ];

            }else{
                if($coupon['value_type']->value == 'Fixed'){
                    $discount=$coupon['value'];
                    $total = $total - $discount;
                }else{
                    $discount=($total * ($coupon['value']/100));
                    $total = $total - $discount;
                }
        
                $summary= [
                    'total' => $total,
                    'sub_total' => $orderData['summary']['sub_total'],
                    'tax' => [
                        'text' => 'Taxes',
                        'value' => $orderData['summary']['tax']['value']
                    ],
                    'discount' => [
                        'text' => 'Discount',
                        'value' => $discount
                    ],
                    'promo' => [
                        'text' => $code,
                        'value' => $coupon->id
                    ],
                    'tip' => [
                        'text' => 'Tip',
                        'value' => $orderData['summary']['tip']['value']
                    ],
                    
                ];
        
                    
                Order::where('id', $orderData['orderData']->id)->update([
                    'summary' => json_encode($summary)
                ]);

                PromoCodeUsers::create([
                    'user_id' => Auth::user()->id,
                    'promo_code_id' => $coupon->id
                ]);

                return [
                    'error' => false
                ];
            }

        }else{
            return [
                'error' => true,
               'message' => 'Invalid promocode'
            ];
        }

        
    }



    public function ApiapplyCoupon($code, $tenantUnitID ,Order $order)
    {
        $cartHelper = new \App\Helpers\CartHelper();
       
        $currentDate = now();
        $orderData = $order['summary'];
        $id=$order['id'];
        
       
        $total=$orderData['total'];
        $discount = 0;
        $coupon = PromoCode::whereHas('tenantUnits', function (Builder $query) use ($tenantUnitID) {
                $query->where('tenant_unit_id', $tenantUnitID);
            })
            ->where('code', $code)
            ->where('active', 1)
            ->where('min_value', '<', $total)
            ->where('max_value', '<', $total)
            ->whereDate('start_date', '<=', $currentDate)
            ->whereDate('end_date', '>=', $currentDate)
            ->first();
   
        if ($coupon) {
            $promoCodeUser = PromoCodeUsers::where('user_id', Auth::user()->id)
                ->where('promo_code_id', $coupon->id)
                ->first();
    
            if ($promoCodeUser) {
                return [
                    'error' => true,
                    'message' => 'You have already used this promocode'
                ];
            } else {
                if ($coupon['value_type'] == 'Fixed') {
                    $discount = $coupon['value'];
                    $total = $total - $discount;
                } else {
                    $discount = ($total * ($coupon['value'] / 100));
                    $total = $total - $discount;
                }
    
                $summary = [
                    'total' => $total,
                    'sub_total' => $orderData['sub_total'],
                    'tax' => [
                        'text' => 'Taxes',
                        'value' => $orderData['tax']['value']
                    ],
                    'discount' => [
                        'text' => 'Discount',
                        'value' => $discount
                    ],
                    'promo' => [
                        'text' => $code,
                        'value' => $coupon->id
                    ],
                    'tip' => [
                        'text' => 'Tip',
                        'value' => $orderData['tip']['value']
                    ],
                ];
                $orderUpdated = Order::where('id', $id)->update([
                    'summary' => json_encode($summary)
                ]);
                PromoCodeUsers::create([
                    'user_id' => Auth::user()->id,
                    'promo_code_id' => $coupon->id
                ]);
    
                return [
                    'error' => false
                ];
            }
        } else {
            return [
                'error' => true,
                'message' => 'Invalid promocode'
            ];
        }
    }
    

    public function removeCoupon(){
        $orderData=$this->orderSummary();
        $code=$orderData['summary']['promo']['value'];
        $total=$orderData['summary']['total'];

    
        $discount=$orderData['summary']['discount']['value'];
        $total = $total + $discount;
    

        $summary= [
            'total' => $total,
           'sub_total' => $orderData['summary']['sub_total'],
            'tax' => [
                'text' => 'Taxes',
                'value' => $orderData['summary']['tax']['value']
                ],
            'discount' => [
                'text' => 'Discount',
                'value' => 0
            ],
            'promo' => [
                'text' => 'Promocode',
                'value' => 0
            ],
            'tip' => [
                'text' => 'Tip',
                'value' => $orderData['summary']['tip']['value']
            ],
        ];
        Order::where('id', $orderData['orderData']->id)->update([
           'summary' => json_encode($summary)
        ]);
       
        PromoCodeUsers::where('user_id',Auth::user()->id)
        ->where('promo_code_id',$code)->delete();
    }

    public function orderHistory(){
       
        $orderData=Order::with('orderItems.orderable.media')->where('user_id',Auth::user()->id)
        ->orderBy('id','desc')
        ->get();
      
        $orderSummary=[];
         
        foreach ($orderData as $order) {
            $summary = [
                'total' => $order->summary['total'],
                'sub_total' => $order->summary['sub_total'],
                'tax' => [
                    'text' => 'Taxes',
                    'value' => $order->summary['tax']['value']
                ],
                'discount' => [
                    'text' => 'Discount',
                    'value' => $order->summary['discount']['value']
                ],
                'promo' => [
                    'text' => $order->summary['promo']['text'],
                    'value' => $order->summary['promo']['value']
                ],
                'tip' => [
                    'text' => 'Tip',
                    'value' => $order->summary['tip']['value']
                ],
            ];
        
            $orderSummary[]=[
                'summary' => $summary,
                'order' => $order
            ];
        }
       
        return $orderSummary;

    }

    public function orderDetails($id) {
        
        
         $orderData=Order::with('orderItems.orderable.media')->where('user_id',Auth::user()->id)
         ->where('id', $id)->get();
         $orderSummary=[];
         
         foreach ($orderData as $order) {
             $summary = [
                 'total' => $order->summary['total'],
                 'sub_total' => $order->summary['sub_total'],
                 'tax' => [
                     'text' => 'Taxes',
                     'value' => $order->summary['tax']['value']
                 ],
                 'discount' => [
                     'text' => 'Discount',
                     'value' => $order->summary['discount']['value']
                 ],
                 'promo' => [
                     'text' => $order->summary['promo']['text'],
                     'value' => $order->summary['promo']['value']
                 ],
                 'tip' => [
                    'text' => $order->summary['promo']['text'],
                    'value' => $orderData['summary']['tip']['value']
                ],
             ];
         
             $orderSummary[]=[
                 'summary' => $summary,
                 'order' => $order
             ];
         }
         return $orderSummary;

    }

}
