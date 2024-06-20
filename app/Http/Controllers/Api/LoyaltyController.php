<?php

namespace App\Http\Controllers\Api;
use App\Models\LoyaltyPoints;
use App\Models\LoyaltyPointsUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\OrderHelper;
use Illuminate\Support\Facades\Validator;



class LoyaltyController extends Controller
{




    
    public function loyaltyPointsDetails(Request $request){
   
        $validator = \Validator::make($request->all(), [
            'tenant_unit_id' => 'required|numeric|exists:tenant_units,id'
       ]);
       
       if ($validator->fails()) {
           return response()->json(['error' => $validator->errors(),"Status Code"=>400], 400);
       }
    
    
       $tenantUnitID = $request->input('tenant_unit_id');
       
       $loyaltyPoints = LoyaltyPoints::where('tenant_unit_id', $tenantUnitID)->get();
  
    return response()->json([
    'loyalty_points' => $loyaltyPoints
    ], 200);
    
    }


    public function getLoyalty(Request $request ){
        $validator = \Validator::make($request->all(), [
            'tenant_unit_id' => 'required|numeric|exists:tenant_units,id',
            'payableAmount'=>'required|numeric',
            'floor_table_id'=>'required|numeric'
       ]);
       if ($validator->fails()) {
           return response()->json(['error' => $validator->errors(),"Status Code"=>400], 400);
       }
       $tenantUnitID = $request->input('tenant_unit_id');
       $payableAmount = $request->input('payableAmount');
       $floorTableId = $request->input('floor_table_id');

       $orderHelper = new \App\Helpers\OrderHelper();
       $getLoyality=$orderHelper->getLoyalty($payableAmount,$tenantUnitID,$floorTableId);

       return response()->json(['message' => 'Loyalty points added successfully']);
    }






}