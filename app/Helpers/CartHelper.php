<?php

namespace App\Helpers;

use App\Enums\CartItemType;
use App\Models\Cart;
use App\Models\CartItem;
use App\Enums\OrderStatus;
use App\Models\FloorTable;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartHelper
{
    protected ?User $user;

    protected bool $is_api_request;

    protected $attributes;

    public function __construct(?Request $request = null)
    {
        if ($request != null) {
            $this->user = $request->user();
            $this->is_api_request = $request->expectsJson();
        } else {
            $this->is_api_request = false;
        }
    }

    public function getUser()
    {
        if($this->is_api_request) {
            return $this->user;
        } else {
            return Auth::user();
        }
    }

    public function init(FloorTable $floorTable)
    {
        if (!session()->has('cart_key')) {
            $key = md5(uniqid(rand(), true));
            $cart=Cart::Where('floor_table_id', $floorTable->id)->orderBy('id', 'desc')->first();
            if($cart){
                $key=$cart->key;
            }
            else{
                Cart::create([
                    'key' => $key,
                    'floor_table_id' => $floorTable->id
                ]);
            }

            session()->put('cart_key', $key);
            session()->put('floor_table_id', $floorTable->id);
        }
    }

    public function tenantUnitID()
    {
        $cart = $this->sessionCart();
        return $cart->floorTable->tenant_unit_id;
    }

    public function tenantUnit()
    {
        $cart = $this->sessionCart();
        return $cart->floorTable->tenantUnit;
    }

    public function sessionCart()
    {
        $cart_key = session()->get('cart_key');
      
        $cart = Cart::where('key', $cart_key)->first();
        if ($cart !== null) {
            $cart->load(['cartItems.cartable']);
        }
        if($cart==null){
            //remove this key from session
            session()->forget('cart_key');
            $floorTable = FloorTable::find(session()->get('floor_table_id'));
            $this->init($floorTable);
            $cart=$this->sessionCart();
        }
        
        return $cart;
    }

    public function sessionCartKey()
    {
        return session()->get('cart_key');
    }

    public function fetchMenuQuantity($menuID)
    {
        $cart = $this->sessionCart();
        if ($cart !== null) {
            $cart_item = $cart->cartItems()->whereHasMorph('cartable', [Menu::class], function ($query) use ($menuID) {
                $query->where('id', $menuID);
            })->first();
            return $cart_item != null ? $cart_item->quantity : 0;
        }
        return 0;
    }

    public function setFloorTable($attributes)
    {
        $cart = Cart::where(['key' => $attributes['key'], 'floor_table_id' => $attributes['floor_table_id']])->first();
        if($cart == null) {
            $cart = Cart::create([
                'key' => $attributes['key'],
                'user_id' => $this->user?->id,
            ]);
        }
        $cart->update([
            'floor_table_id' => $attributes['floor_table_id'],
            'diners' => $attributes['diners']
        ]);
        return $this->cartSummary($cart);
    }

    public function fetchCart()
    {
        $cart = Cart::where('user_id', $this->user->id)->first();
        if ($cart == null) {
            $key = md5(uniqid(rand(), true));
            $cart = Cart::create([
                'key' => $key,
                'user_id' => $this->user->id,
            ]);
        }
        return $cart;
    }

    public function adjust($attributes)
    {
       
        if (!$this->is_api_request) {
            $attributes['key'] = $this->sessionCartKey();
        }
       
        $this->attributes = $attributes;
        $collect = collect($attributes);

        $clauses = [
            'key' => $attributes['key']
        ];
        if(collect($attributes)->has('floor_table_id')) {
            $clauses['floor_table_id'] = $attributes['floor_table_id'];
        }
        $cart = Cart::where($clauses)->first();
        $type = CartItemType::from($collect->get('type'));
        $type_id = $collect->get('type_id');

        $cart_item = $cart->cartItems()->whereHasMorph('cartable', [$type->modelClass()], function ($query) use ($type_id) {
            $query->where('id', $type_id);
        })->first();

        $quantity = $collect->get('quantity');
        if ($cart_item == null) {
            $cart_item = $cart->cartItems()->create([
                'cartable_type' => $type->modelClass(),
                'cartable_id' => $collect->get('type_id'),
                'quantity' => 0
            ]);
        }

        $method = $collect->get('method', 'set');
        if ($method == 'add') {
            $cart_item->increment('quantity', $quantity);
        }

        if ($method == 'substract') {
            $cart_quantity = $cart_item->quantity;
            if ($cart_quantity - $quantity <= 0) {
                $cart_item->delete();
            } else {
                $cart_item->decrement('quantity', $quantity);
            }
        }

        if ($method == 'set') {
            $cart_item->update([
                'quantity' => $quantity
            ]);
        }

        return $this->cartSummary($cart);
    }

    public function cartSummary(Cart $cart)
    {
        $cart->load(['cartItems.cartable.media', 'floorTable']);
        $sub_total = 0;
      
        foreach ($cart->cartItems as $cart_item) {
            $sub_total += $cart_item->quantity * $cart_item->cartable->applied_price;
        }
        $sub_total = round($sub_total, 2);
        $tax = round($sub_total * 0.05, 2);
        $discount = 0;
        $promo = 0;

        $total = round(($sub_total + $tax) - ($discount + $promo), 2);

        return [
            'cart' => $cart,
            'summary' => [
                'total' => $total,
                'sub_total' => $sub_total,
                'tax' => [
                    'text' => 'Taxes',
                    'value' => $tax
                ],
                'discount' => [
                    'text' => 'Discount',
                    'value' => $discount
                ],
                'promo' => [
                    'text' => '',
                    'value' => $promo
                ],
                'tip' => [
                    'text' => 'Tip',
                    'value' => 0
                ],
            ],
            'currency' => $this->currency()
        ];
    }

    public function currency()
    {
        return [
            'sign' => '₹',
            'iso' => 'INR',
            'text' => 'Rupee'
        ];
    }

    public function summary($attributes)
    {

        if (!$this->is_api_request) {
            $attributes['key'] = $this->sessionCartKey();
        }
        $this->attributes = $attributes;

        $clauses = [
            'key' => $attributes['key']
        ];
        if(collect($attributes)->has('floor_table_id')) {
            $clauses['floor_table_id'] = $attributes['floor_table_id'];
        }
        $cart = Cart::where($clauses)->first();
        // $cart = Cart::where('key', $this->attributes['key'])->first();
        return $this->cartSummary($cart);
    }




    public function userCartSummary()
    {
        $cart = Cart::where('user_id', $this->user->id)->first();
        return $this->cartSummary($cart);
    }

    public function isUserCartValid()
    {
        $cart = Cart::where('user_id', $this->user->id)->first();
        return $cart->cartItems()->count() > 0 && $cart->floor_table_id != null;
    }

    public function isCartValid($attributes)
    {
        $clauses = [
            'key' => $attributes['key']
        ];
        if(collect($attributes)->has('floor_table_id')) {
            $clauses['floor_table_id'] = $attributes['floor_table_id'];
        }
        $cart = Cart::where($clauses)->first();
        return $cart->cartItems()->count() > 0 && $cart->floor_table_id != null;
    }

    public function resetUserCart()
    {
        if ($this->is_api_request) {
            $cart = Cart::where('user_id', $this->user->id)->first();
        } else {
            $cart = $this->sessionCart();
        }
        $cart->cartItems()->delete();
        $cart->update([
            'promo_code_id' => null,
            'diners' => 1
        ]);
    }

    public function resetCart(Cart $cart)
    {
        $cart->cartItems()->delete();
        $cart->update([
            'promo_code_id' => null,
            'diners' => 1
        ]);

    }

    public function moveToOrder($cart_info = [], $customer_info = [])
    {

        $cart_summary = $this->summary($cart_info);
        $order_no = OrderHelper::nextOrderNo();

        $customer = collect($customer_info);

        $user = $this->getUser();

        DB::transaction(function () use (&$cart_summary, &$order_no, &$customer, &$user) {

            $summary = $cart_summary['summary'];
            $cart = Cart::find($cart_summary['cart']['id']);
            $cart->load(['cartItems.cartable', 'floorTable']);

            $cart->update([
                'user_id' => $user->id
            ]);

            $tenant_unit_id = $cart->floorTable->tenant_unit_id;
            $code = OrderHelper::newCode();
            $order = Order::create([
                'order_no' => $order_no,
                'user_id' => $user->id,
                'floor_table_id' => $cart->floor_table_id,
                'diners' => $cart->diners,
                'code' =>  $code,
                'summary' => $summary,
                'customer' => $customer,
                'address' => $customer->get('address'),
                'tenant_unit_id' => $tenant_unit_id
            ]);


            foreach ($cart->cartItems as $cartItem) {
                $order->orderItems()->create([
                    'orderable_id' => $cartItem->cartable_id,
                    'orderable_type' => $cartItem->cartable_type,
                    'price' => $cartItem->cartable->applied_price,
                    'quantity' => $cartItem->quantity,
                    'tenant_unit_id' => $tenant_unit_id
                ]);
            }

            $order->orderHistories()->create([
                'title' => 'Order Placed',
                'subtitle' => null,
                'status' => OrderStatus::Placed,
                'tenant_unit_id' => $tenant_unit_id
            ]);

            $this->resetCart($cart);
        });

        return [
            'order_no' => $order_no
        ];


    }




    public function getOrderDetails($orderId)
{
    $order = Order::where('order_no', $orderId)->first();

    if (!$order) {
        return response()->json(['error' => 'Order not found'], 404);
    }

    $data = [
        'order_no' => $order->order_no,
        'user_id' => $order->user_id,
        'floor_table_id' => $order->floor_table_id,
        'diners' => $order->diners,
        'code' => $order->code,
        'summary' => $order->summary,
        'customer' => $order->customer, // Assuming customer is stored as JSON in the database
        'address' => $order->address,
        'tenant_unit_id' => $order->tenant_unit_id
    ];

    return response()->json($data);
}
}
