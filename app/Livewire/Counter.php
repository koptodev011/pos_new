<?php

namespace App\Livewire;

use App\Helpers\CartHelper;
use Livewire\Component;

class Counter extends Component
{
    public $quantity = 0;
    public $menu;

    public function mount($menu){
        $this->menu=$menu;
        $this->quantity=$this->fetchMenuQuantity();
    }
    public function render()
    {
        return view('livewire.customer.componant.counter');
    }

    public function fetchMenuQuantity()
    {
        $cartHelper = new CartHelper();
        $count=$cartHelper->fetchMenuQuantity($this->menu->id);

        return $count;
    }

    public function increment()
    {
        $cartHelper = new CartHelper();
        $cartHelper->adjust([
            'menu_id' => $this->menu->id,
            'type_id'=>$this->menu->id,
            'type'=>'Menu',
            'quantity' => 1,
            'method' => 'add'
        ]);

        $this->quantity++;
        $this->dispatch('customers.orders.cart.adjusted');
    }

    public function decrement()
    {
        if ($this->quantity > 0) {
            $cartHelper = new CartHelper();
            $cartHelper->adjust([
            'menu_id' => $this->menu->id,
            'type_id'=>$this->menu->id,
            'type'=>'Menu',
            'quantity' => 1,
            'method' => 'substract'
            ]);
            $this->quantity--;
            $this->dispatch('customers.orders.cart.adjusted');
        }
    }
}
