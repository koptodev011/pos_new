<?php

namespace App\Livewire;

use Livewire\Component;

class Carousel extends Component
{

    public $images = [
        'image3.jpg',
        'image1.jpg',
        
    ];
    public function render()
    {
        return view('livewire.customer.componant.carousel', [
            'images' => $this->images,
        ]);    }
}
