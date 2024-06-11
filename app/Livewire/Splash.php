<?php

namespace App\Livewire;

use Livewire\Component;

class Splash extends Component


{
    public $showSplashScreen = true; // Control whether to show the splash screen or not

    public function hideSplashScreen()
    {
        $this->showSplashScreen = false;
    }

    public function render()
    {
        return view('livewire.splash');
    }
}
