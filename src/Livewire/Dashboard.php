<?php

namespace Tabatii\LocalMail\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('localmail::components.layout')]
class Dashboard extends Component
{
    public function render()
    {
        return view('localmail::livewire.dashboard')->title('Dashboard');
    }
}
