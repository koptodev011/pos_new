<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FloorTable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;




class FloorTableController extends Controller
{
    public function index(Request $request)
    {
        return JsonResource::make(FloorTable::query()->get());
    }

    public function save(Request $request)
    {
        $min_cap = $request->input('min_capacity', 1);
        $attributes = $request->validate([
            'name' => ['required', 'min:1', 'max:255'],
            'min_capacity' => ['required', 'min:1', 'integer'],
            'max_capacity' => ['required', 'integer', 'min:' . $min_cap],
            'extra_capacity' => ['present', 'min:0', 'max:5'],
            'floor' => ['nullable', 'string'],
        ]);

        $user = $request->user();
        $attributes['tenant_unit_id'] = $user->userTenantUnitList()[0];

        $entity = FloorTable::create($attributes);

        return JsonResource::make($entity);

    }

}
