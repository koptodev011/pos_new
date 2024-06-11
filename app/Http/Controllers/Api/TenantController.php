<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        return JsonResource::make(Tenant::query()->get());
    }

    public function save(Request $request){

        $attributes = $request->validate([
            'name' => ['required', 'min:1', 'max:255'],
            'website' => ['required', 'min:1', 'max:255'],
            'gst' => ['required', 'min:6', 'max:12'],


        ]);

        $createdTenant = Tenant::create($attributes);

        return JsonResource::make($createdTenant);
    }
}
