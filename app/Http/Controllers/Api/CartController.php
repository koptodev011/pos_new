<?php

namespace App\Http\Controllers\Api;

use App\Enums\CartItemType;
use App\Helpers\CartHelper;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use function Pest\Laravel\from;

class CartController extends Controller
{
    public function adjust(Request $request)
    {

        $type_validator = Validator::make($request->all(), [
            'type' => ['required', Rule::enum(CartItemType::class)]
        ]);


        if ($type_validator->fails()) {
            return response([
                'errors' => $type_validator->errors()
            ], 422);
        }


        $type_validated = $type_validator->validated();
        $type = CartItemType::from($type_validated['type']);

        $rules = [
            'key' => ['required', Rule::exists('carts', 'key')],
            'quantity' => ['required', 'min:1'],
            'method' => ['required', 'string', Rule::in(['set', 'add', 'substract'])],
            'type_id' => ['required', Rule::exists($type->tbl(), 'id')],
            'floor_table_id' => ['required', Rule::exists('floor_tables', 'id')]
        ];

        $attributes = $request->validate($rules);
        $attributes['type'] = $type_validated['type'];
        $cart_helper = new CartHelper($request);
        $summary = $cart_helper->adjust($attributes);
        
        return JsonResource::make($summary);

    }

    public function setFloorTable(Request $request)
    {

        $attributes = $request->validate([
            'key' => ['required', Rule::exists('carts', 'key')],
            'floor_table_id' => ['required', Rule::exists('floor_tables', 'id')],
            'diners' => ['required', 'min:1']
        ]);
        $cart_helper = new CartHelper($request);
        $summary = $cart_helper->setFloorTable($attributes);
        return JsonResource::make($summary);


    }

    public function summary(Request $request)
    {
        $attributes = $request->validate([
            'key' => ['required', Rule::exists('carts', 'key')],
            'floor_table_id' => ['required', Rule::exists('floor_tables', 'id')]
        ]);
        $cart_helper = new CartHelper($request);
        $summary = $cart_helper->summary($attributes);
        return JsonResource::make($summary);

    }


    public function AddToCart(Request $request)
    {
        $type_validator = Validator::make($request->all(), [
            'type' => ['required', Rule::enum(CartItemType::class)]
        ]);

        if ($type_validator->fails()) {
            return response([
                'errors' => "Type is Not Found"
            ], 422);
        }

        $type_validated = $type_validator->validated();
      
        $type = CartItemType::from($type_validated['type']);
       
        $rules = [
            'key' => ['required', Rule::exists('carts', 'key')],
            'quantity' => ['required', 'min:1'],
            'method' => ['required', 'string', Rule::in(['set', 'add', 'substract'])],
            'type_id' => ['required', Rule::exists($type->tbl(), 'id')],
            'floor_table_id' => ['required', Rule::exists('floor_tables', 'id')]
        ];

     
        $attributes = $request->validate($rules);
   
        $attributes['type'] = $type_validated['type'];
     
        $cart_helper = new CartHelper($request);
        $summary = $cart_helper->adjust($attributes);
      
        return JsonResource::make($summary);
    }


    public function AllOrderDetails(Request $request)
    {
        $attributes = $request->validate([
            'key' => ['required', Rule::exists('carts', 'key')],
            'floor_table_id' => ['required', Rule::exists('floor_tables', 'id')]
        ]);

        $cart_helper = new CartHelper($request);
        $summary = $cart_helper->summary($attributes);

        return JsonResource::make($summary);
    }


    
    

  

}
