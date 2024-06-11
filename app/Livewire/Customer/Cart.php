<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Menu as MenuModel;
use App\Models\FloorTable;
use App\Helpers\CartHelper;

class Cart extends Component
{
    public $currency;

    protected $listeners = ['cart-changed' => '$refresh'];

    public function render()
    {
        $cartHelper = new CartHelper();
        $cart = $cartHelper->sessionCart();
        $summary = $cartHelper->cartSummary($cart);
        $tablePin = '';
        return view(
            'livewire.customer.cart',
            [
                'summary' => $summary, 'tablePin' => $tablePin
            ]
        );
    }

    public function navigateToOrders()
    {
        // Navigate to the "favorite" route
        return redirect()->to(route('orders'));
    }

    public function placeOrder()
    {
        $cart_helper = new CartHelper();
        $result = $cart_helper->moveToOrder();
        $this->navigateToOrders();
    }

    public function mount()
    {
        $cartHelper = new CartHelper();

        $tenantUnit = $cartHelper->tenantUnit();
        $this->currency = $tenantUnit->country->getCurrency();
    }
}
