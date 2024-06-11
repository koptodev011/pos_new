<?php

namespace App\Livewire\Customer;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Menu as MenuModel;
use App\models\TenantUnit;
use App\Models\FloorTable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class Home extends Component
{
    public FloorTable $floorTable;

    public function mount(?FloorTable $floorTable)
    {
        if($floorTable == null){
            $this->floorTable=FloorTable::find(session()->get('floor_table_id'))->first();
        }
        $this->floorTable = $floorTable;
        $cartHelper = new \App\Helpers\CartHelper();
        $cartHelper->init($this->floorTable);
    }

    public function render()
    {
        $cartHelper = new \App\Helpers\CartHelper();
        $tenantUnit = $cartHelper->tenantUnit();
        $currency = $tenantUnit->country->getCurrency();

         $categories = Category::whereHas('tenantUnits', function (Builder $query) use ($tenantUnit) {
            $query->where('tenant_unit_id', $tenantUnit->id);
        })->get();

        $menu_tags = [];
        $tags = ['Recommended', 'Hottest Offers', 'Hot Selling'];
        foreach($tags as $tag) {
            $menu_tags[$tag] = MenuModel::withAnyTags([$tag], 'menu')->whereHas('tenantUnits', function (Builder $query) use ($tenantUnit) {
                $query->where('tenant_unit_id', $tenantUnit->id);
            })->get();
        }
        return view('livewire.customer.home', ['categories' => $categories,'menu_tags' => $menu_tags,'currency' => $currency]);
    }

    public function placeholder(array $params = [])
    {
        return view('livewire.placeholders.skeleton', $params);
    }
}
