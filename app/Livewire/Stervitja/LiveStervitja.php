<?php

namespace App\Livewire\Stervitja;

use Livewire\Component;

class LiveStervitja extends Component
{
    public function render()
    {
        return view('livewire.stervitja.live-stervitja')->layout('layouts.dashboard.app');
    }
}
