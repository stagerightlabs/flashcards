<?php

namespace App\Http\Livewire;

use App\Card;
use Livewire\Component;

class Cards extends Component
{
    protected $cards;
    protected $keyDown;
    protected $selected;

    protected $listeners = [
        'card.created' => 'receiveCard',
        'card.updated' => 'updateCard',
        'card.deleted' => 'removeCard',
    ];

    public function receiveCard($card)
    {
        $this->cards->prepend(Card::findByUlid($card));
    }

    public function updateCard($ulid)
    {
        $this->cards = $this->cards->map(function($card) use ($ulid) {
            if ($card->ulid == $ulid) {
                return $card->fresh();
            }
            return $card;
        });
    }

    public function removeCard($ulid)
    {
        $this->cards = $this->cards->reject(function($card) use ($ulid) {
            return $card->ulid == $ulid;
        });
    }

    public function mount()
    {
        $this->cards = Card::where('domain_id', auth()->user()->current_domain_id)
            ->orderByDesc('created_at')
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
