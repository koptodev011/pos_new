<?php

namespace App\Livewire;

use Livewire\Component;

class Notification extends Component
{
    public $notifications = [
        ['id' => 1, 'message' => 'Congratulations', 'details' => 'Your food is being prepared', 'date' => '2024-04-25', 'visible' => true],
        ['id' => 2, 'message' => 'Notification 2', 'details' => 'Details of notification 2', 'date' => '2024-04-25', 'visible' => true],
        ['id' => 3, 'message' => 'Notification 3', 'details' => 'Details of notification 3', 'date' => '2024-04-26', 'visible' => true],
        // Add more notifications as needed
    ];

    public function render()
    {
        return view('livewire.notification');
    }
}
