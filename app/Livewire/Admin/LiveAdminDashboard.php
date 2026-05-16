<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class LiveAdminDashboard extends Component
{
    public $welcomeMessage = 'Mirë se vini në Dashboard!';

    public function testFunction()
    {
        session()->flash('message', 'Livewire po funksionon perfekt!');
    }

    public function render()
    {
        return view('livewire.admin.live-admin-dashboard')
            ->layout('layouts.dashboard.app');
    }
}
