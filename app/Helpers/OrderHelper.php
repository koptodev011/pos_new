<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\PromoCode;
use App\Models\PromoCodeUsers;
use App\Models\TenantUnit;
use illuminate\Database\Eloquent\Builder;
use App\Models\LoyaltyPoints;
use App\Models\LoyaltyPointsUser;
use App\Models\User;



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
      
        if($orderData){
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
                    'loyalty'=>[
                        'text' => $orderData['summary']['loyalty']['text'],
                        'value' => $orderData['summary']['loyalty']['value']
                    ]
                ],
               
            ];
        }
    }

    public function paymentDetailsSummary($floorTableId,$tenantUnitId)
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


    

    public function PaymentSummary()
    {
        $orderData = Order::with('orderItems.orderable.media')
        ->where('user_id', Auth::user()->id)
        ->where('status', '!=', 'Completed')
        ->first();
    $orderDataArray = json_decode(json_encode($orderData), true);
   
    $id = 0;
    if (isset($orderDataArray['order_items'])) {
        $outputArray = [];
        foreach ($orderDataArray['order_items'] as $item) {
            $output = [
                'Order_item:-' . $id => [
                    'order_items' => [
                        'name' => $item['orderable']['name'],
                        'price' => $item['orderable']['price'],
                        'quantity' => $item['quantity'],
                        // 'imageUrl' => $item['orderable']['media']['original_url'],
                         'total'=>$item['orderable']['price']* $item['quantity'],
                    ]
                ]
            ];
            $outputArray[] = $output;
            $id++;
        }
        $outputArray[]=$orderData['summary'];
        echo json_encode($outputArray, JSON_PRETTY_PRINT);
    }
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



    public function ApiapplyCoupon($code,$tenantUnitID,$floor_table_id)
    {
        $cartHelper = new \App\Helpers\CartHelper();
        $currentDate = now();
        $orders = Order::with(['floorTable', 'orderItems.orderable.media', 'orderPayments', 'orderHistories'])
        ->where('floor_table_id', $floor_table_id)
        ->where('status', '<>','Completed')
        ->orderBy('id','DESC')
        ->first();
        $id=$orders['id'];
        $orderData = $orders['summary'];
$total=$orders['summary']['total'];
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
         print_r($orderSummary);
         dd();
         
         return $orderSummary;

    }


    public function BillDetails($id) {
   
        
        $orderData=Order::with('orderItems.orderable.media')->where('user_id',Auth::user()->id)
        ->where('id', $id)->get();
      
 
        $orderSummary=[];

        
        foreach ($orderData as $order) {
         
            $summary = [
                'total' => $order['summary']['total'],
                'sub_total' =>$order['summary']['sub_total'],
                'tax' => [
                    'text' => 'Taxes',
                    'value' => $order['summary']['tax']['value']
                ],
                'discount' => [
                    'text' => 'Discount',
                    'value' => $order['summary']['discount']['value']
                ],
                'promo' => [
                   
                    'text' =>  $order['summary']['promo']['text'],
                    'value' =>  $order['summary']['promo']['value']
                ],
                'tip' => [
                   'text' =>$order['summary']['tip']['text'],
                   'value' =>$order['summary']['tip']['value'],
               ],
            ];
            $orderSummary[]=[
                'summary' => $summary,
                'order' => $order
            ];
        }
       return $orderSummary;

   }



    // public function loyaltyPointsDetails($tenantUnitID){
    //     $loyalty = LoyaltyPoints::whereHas('tenantUnits', function (Builder $query) use ($tenantUnitID) {
    //         $query->where('tenant_unit_id', $tenantUnitID);
    //     })
    //     ->where('active',1)
    //     ->orderBy('id','DESC')
    //     ->first();
    //     $loyalty = LoyaltyPoints::where('tenant_unit_id', $tenantUnitID)->get();

    //     return $loyalty;
    // }


    public function loyaltyPointsDetails($tenantUnitID){
        $loyalty = LoyaltyPoints::where('tenant_unit_id', $tenantUnitID)
        ->where('active', 1)
        ->orderBy('id', 'DESC')
        ->get();
       
        return $loyalty;
    }
    

    
    // public function loyaltyPointsDetails($tenantUnitID){
    //     $loyalty = LoyaltyPoints::where('tenant_unit_id', $tenantUnitID)->get();
    //     $loyaltyDetails = [];
    //     return $loyalty;
    // }


    public function getLoyalty($payableAmount,$tenantUnitID,$floorTableId){
        $orderData=$this->orderSummary();
    
        $loyaltyDetails =$this->loyaltyPointsDetails($tenantUnitID);
        $loyaltyPoint = $loyaltyDetails->first();
        $minValue = $loyaltyPoint->min_value;
        $maxValue = $loyaltyPoint->max_value;
        $gainPercentage = $loyaltyPoint->gain_percentage;
        $decodedData = json_decode($loyaltyDetails, true);

        if($loyaltyDetails){
            if($payableAmount > $minValue && $payableAmount <= $maxValue ){
            
                $getLoyalty=($payableAmount * ($gainPercentage/100));
                $user = Auth::user();
                $user_id=$user->id;
               
             $userData = LoyaltyPointsUser::where('user_id',$user_id)->first();
              
                LoyaltyPointsUser::create([
                    'user_id' => Auth::user()->id,
                    'points' => $getLoyalty,
                    'order_id' => $orderData['orderData']->id,
                    'type' => 'gained'
                ]);
              $updated_points = Auth::user()->loyalty_points + $getLoyalty;
             
                $user=User::find($user->id);
                $user->update([
                    'loyalty_points' => $updated_points
                ]);
               
            }
        }


    }



    public function applyLoyalty($tenantUnitID,$floorTableId){
       
        // $cartHelper = new \App\Helpers\CartHelper();
        // $tenantUnitID = $cartHelper->tenantUnitID();
        $orderData=$this->orderSummary();
   
        $total=$orderData['summary']['total'];

        $loyaltyDetails =$this->loyaltyPointsDetails($tenantUnitID);
        $loyaltyPoint = $loyaltyDetails->first();
        $minValue = $loyaltyPoint->min_value;
        $maxValue = $loyaltyPoint->max_value;
        $gainPercentage = $loyaltyPoint->gain_percentage;
        $decodedData = json_decode($loyaltyDetails, true);
        if($loyaltyDetails){
          
            if($total > $minValue && $total <= $maxValue ){
              
                if(Auth::user()){
                    $loyaltyPointsUser=LoyaltyPointsUser::where('user_id', Auth::user()->id)->where('order_id', $orderData['orderData']['id'])->where('type','used')->first();

                }else{
                    return [
                       'error' => true,
                       'message' => 'Please Login First'
                    ];
                }

               
                if($loyaltyPointsUser){
                   
                    $usedPoints=$loyaltyPointsUser['points'];
                    $total=$orderData['summary']['total'];                
                    
                    $summary= [
                        'total' => $total,
                       'sub_total' => $orderData['summary']['sub_total'],
                        'tax' => [
                            'text' => 'Taxes',
                            'value' => $orderData['summary']['tax']['value']
                            ],
                        'discount' => [
                            'text' => $orderData['summary']['discount']['text'],
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
                        'loyalty' => [
                            'text' => '',
                            'value' => 0
                        ],
                    ];
                    Order::where('id', $orderData['orderData']->id)->update([
                       'summary' => json_encode($summary)
                    ]);
                   
                    $loyaltyPointsUser=LoyaltyPointsUser::where('user_id', Auth::user()->id)->where('order_id', $orderData['orderData']->id)->where('type','used')->delete();

                    $user = Auth::user();
                  
                    $updated_points = Auth::user()->loyalty_points + $usedPoints;
                    
                    User::where('id',$user->id)->update(['loyalty_points' => $updated_points]);
                    return [
                        'error' => false
                    ];
    
                }else{
                
                    $utilize_percentage = $loyaltyPoint->utilize_percentage;
                    
                     if($utilize_percentage==''){
                            $loyaltyPercent=100;
                        }else{
                            $loyaltyPercent=$utilize_percentage;    
                        }

                       
                     $userLoyalty=Auth::user()->loyalty_points;
                    
                        $applyLoyalty=($userLoyalty * ($loyaltyPercent/100));
                       
                        $summary= [
                            'total' => $total,
                           'sub_total' => $orderData['summary']['sub_total'],
                            'tax' => [
                                'text' => 'Taxes',
                                'value' => $orderData['summary']['tax']['value']
                                ],
                            'discount' => [
                                'text' => $orderData['summary']['discount']['text'],
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
                            'loyalty' => [
                                'text' => Auth::user()->id,
                                'value' => $applyLoyalty
                            ],
                        ];
          
                        
                    Order::where('id', $orderData['orderData']->id)->update([
                        'summary' => json_encode($summary)
                    ]);
                
                   
                    LoyaltyPointsUser::create([
                        'user_id' => Auth::user()->id,
                        'points' => $applyLoyalty,
                        'order_id' => $orderData['orderData']->id,
                        'type' => 'used'
                    ]);
                   
                   
                    $user = Auth::user();
                  
                    $updated_points = Auth::user()->loyalty_points - $applyLoyalty;
                    
                    User::where('id',$user->id)->update(['loyalty_points' => $updated_points]);
                    return [
                        'error' => false
                    ];
                 
                  
                }
    
            }else{
                return [
                    'error' => true,
                   'message' => 'Failed to use loyalty points'
                ];
            }
            
        }
            
    }





}
