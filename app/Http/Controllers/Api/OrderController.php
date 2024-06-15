<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CartHelper;
use App\Http\Controllers\Controller;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
class OrderController extends Controller
{
    public function place(Request $request)
    {

        $attributes = $request->validate([
            'key' => ['required', Rule::exists('carts', 'key')],
            'floor_table_id' => ['required', Rule::exists('floor_tables', 'id')],
            'address' => ['nullable', 'array', 'min:1'],
            'customer' => ['nullable', 'array', 'min:1'],
            'customer.name' => ['nullable', 'min:1'],
            'customer.email' => ['nullable', 'email'],
            'customer.phone' => ['nullable', 'min:4']
        ]);

        $cart_helper = new CartHelper($request);

        if(!$cart_helper->isCartValid($attributes)) {
            return response([
                'message' => 'Cart is empty or Floor Table is not set'
            ], 400);
        }

        $collect = collect($attributes);
        $result = $cart_helper->moveToOrder($attributes, $collect->get('customer', []));

        return [
            'data' => $result,
            'message' => 'Order Placed'
        ];

    }

    public function progressList(Request $request)
    {
        $user = $request->user();
        $orders = Order::with(['floorTable'])
            ->where('user_id', $user->id)
            ->get();

        return JsonResource::make($orders);
    }

    public function show(Request $request, Order $order)
    {
        $validator = \Validator::make($request->all(), [
            'floor_table_id' => 'required|numeric|exists:tenant_units,id'
       ]);
       
       if ($validator->fails()) {
           return response()->json(['error' => $validator->errors(),"Status Code"=>400], 400);
       }
        $orders = Order::with(['floorTable', 'orderItems.orderable.media', 'orderPayments', 'orderHistories'])
            ->where('floor_table_id', $request->floor_table_id)
            ->where('status', '<>','Completed')
            ->orderBy('id','DESC')
            ->first();

           
        return JsonResource::make($orders);
    }

    public function addPayment(Request $request, Order $order)
    {
        $total = $order->summary['total'];
        $attributes = $request->validate([
            'provider' => ['required', 'string', Rule::in(['Cash'])],
            'amount' => ['required', 'numeric', "min:1","max:{$total}"],
        ]);

        $paid_amount = $order->orderPayments()->sum('amount');
        $ispaid = doubleval($total) <= ($paid_amount + $attributes['amount']);

        $payment = $order->orderPayments()->create([
            'payment_provider' => $attributes['provider'],
            'tenant_unit_id' => $order->tenant_unit_id,
            'amount' => $attributes['amount'],
            'paid' => $ispaid
        ]);

        return JsonResource::make($payment);

    }

    public function cancel(Request $request, Order $order)
    {

        if($order->status == OrderStatus::Cancelled) {
            return response()->json([
                'message' => 'Order is already cancelled'
            ], 400);
        }

        $attributes = $request->validate([
            'reason' => ['required', 'min:2', 'string', 'max:255']
        ]);

        $order->update([
            'status' => OrderStatus::Cancelled
        ]);

        $order->orderHistories()->create([
            'title' => 'Order Cancelled',
            'subtitle' => $attributes['reason'],
            'status' => OrderStatus::Cancelled,
            'tenant_unit_id' => $order->tenant_unit_id
        ]);

        return JsonResource::make([
            'message' => 'Order has been cancelled'
        ]);


    }

    public function placeOrder(Request $request)
    {
     $validator = Validator::make($request->all(), [
    'key' => ['required', Rule::exists('carts', 'key')],
    'floor_table_id' => ['required', Rule::exists('floor_tables', 'id')],
    'address' => ['nullable', 'array', 'min:1'],
    'customer' => ['nullable', 'array', 'min:1'],
    'customer.name' => ['nullable', 'min:1'],
    'customer.email' => ['nullable', 'email'],
    'customer.phone' => ['nullable', 'min:4']
]);

if ($validator->fails()) {
    return response()->json(['errors' => $validator->errors()], 400);
}
$attributes = $validator->validated();

        $cart_helper = new CartHelper($request);
        $summary = $cart_helper->summary($attributes);
    
        if (!$cart_helper->isCartValid($attributes)) {
            return response()->json([
                'message' => 'Cart is empty or Floor Table is not set'
            ], 400);
        }    
        $collect = collect($attributes);

    
        $result = $cart_helper->moveToOrder($attributes, $collect->get('customer', []));
       
    
        return response()->json([
            'data' => $result,
            'message' => 'Order Placed',
            'summary' => $summary 
        ]);
    }
    


  

    public function PayNow(Request $request, Order $order)
    {
        $attributes = $request->validate([
            'key' => ['required', Rule::exists('carts', 'key')],
            'floor_table_id' => ['required', Rule::exists('floor_tables', 'id')],
        ]);
        $cart_helper = new CartHelper($request);
        $summary = $cart_helper->summary($attributes);
        $totalData = $summary['total'];
        return response()->json([
            'summary' => $summary,
            'total' => $totalData
        ]);

      
        
        // echo $request;
        // $total = $order->summary['total'];
        
        // $attributes = $request->validate([
        //     'provider' => ['required', 'string', Rule::in(['Cash','Online'])],
        //     'amount' => ['required', 'numeric', "min:1","max:{$total}"],
        // ]);

        // if($attributes){
        //     echo "True";
        // }
        // else{
        //     echo "False";
        // }

        // $paid_amount = $order->orderPayments()->sum('amount');
        // $ispaid = doubleval($total) <= ($paid_amount + $attributes['amount']);

        // $payment = $order->orderPayments()->create([
        //     'payment_provider' => $attributes['provider'],
        //     'tenant_unit_id' => $order->tenant_unit_id,
        //     'amount' => $attributes['amount'],
        //     'paid' => $ispaid
        // ]);

        // $floorTable = FloorTable::find($order->floor_table_id);
        // $floorTable->update([
        //     'status' => FloorTableStatus::Available
        // ]);

        // $order->update([
        //     'status' => OrderStatus::Completed,
        // ]);

        // return JsonResource::make($payment);
    }



    public function pro(Request $request)
    {
        $user = $request->user();
        $orders = Order::with(['floorTable'])
            ->where('user_id', $user->id)
            ->get();

        return JsonResource::make($orders);
    }
    
}
