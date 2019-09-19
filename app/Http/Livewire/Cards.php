<?php

namespace App\Http\Livewire;

use App\Card;
use Livewire\Component;

class Cards extends Component
{
    protected $cards;
    protected $keyDown;
    protected $selected;

    protected $listeners = ['cardCreated' => 'receiveCard'];

    public function receiveCard($card)
    {
        $this->cards->prepend(Card::findByUlid($card));
    }

    public function mount()
    {
        $this->cards = Card::where('domain_id', auth()->user()->current_domain_id)
            ->get();
    }

    public function render()
    {
        return view('livewire.cards')
            ->with('cards', $this->cards);
    }

    public function select($ulid)
    {
        $this->emit('cardSelected', $ulid);
    }
}
