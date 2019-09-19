<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CreateCardButton extends Component
{
    public function click()
    {
        $this->emit('requestCreateCardModal');
    }

    public function render()
    {
        return view('livewire.create-card-button');
    }
}
