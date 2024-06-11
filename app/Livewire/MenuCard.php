<?php

namespace App\Livewire;

use App\Helpers\CartHelper;
use App\Helpers\MenuHelper;
use Livewire\Component;


class MenuCard extends Component
{
    public $expand = false;
    public $isLiked = false;

    public $vertical = false;
    public $menu;
    public $status;
    public $currency;
    public $successMessage = '';
    public $showAddButton =true;
    
    protected $listeners = ['refreshFavorites' => '$refresh'];

    public function render()
    {
        return view('livewire.customer.componant.menu-card');
    }

    public function toggleExpand()
    {
        $this->expand = !$this->expand;
    }

    public function like()
    {
        // Toggle the like status
        $this->isLiked = !$this->isLiked;
    }

    public function onCartAdd($menuID)
    {
        $cart_helper = new CartHelper();
        $cart_helper->adjust([
            'type_id' => $menuID,
            'type' => 'Menu',
            'quantity' => 1,
            'method' => 'add'
        ]);
        $this->dispatch('cart-changed');
    }

    public function onCartSub($menuID)
    {
        $cart_helper = new CartHelper();
        $cart_helper->adjust([
            'type_id' => $menuID,
            'type' => 'Menu',
            'quantity' => 1,
            'method' => 'substract'
        ]);
        $this->dispatch('cart-changed');
    }

    public function fetchMenuQuantity($menuID)
    {
        $cartHelper = new CartHelper();

        return $cartHelper->fetchMenuQuantity($menuID);
    }

    public function isFavorite($menuID)
    {
        $helper = new MenuHelper();    
        return $helper->isFavorite($menuID);
    }

    public function addFavorite($menuID)
    {
        $helper = new MenuHelper();    
        
        if ($helper->addFavourite($menuID)) {
            $this->successMessage = 'Menu successfully added in Favorites!';
        } else {
            $this->successMessage = 'Menu successfully removed from Favorites!';
        }
        $this->dispatch('refreshFavorites');
        return $helper->isFavorite($menuID);
    }

    public function clearSuccessMessage()
    {
        $this->successMessage = '';
    }
}
