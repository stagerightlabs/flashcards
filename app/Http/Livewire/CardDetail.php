<?php

namespace App\Http\Livewire;

use App\Card;
use Livewire\Component;

class CardDetail extends Component
{
    public $ulid = '';
    protected $card;
    protected $listeners = [
        'cardSelected' => 'selectCardByUlid',
        'requestCardClosure' => 'closeCard',
    ];

    public function mount()
    {
        $this->selectCardByUlid(request()->get('card'));
    }

    public function render()
    {
        return view('livewire.card-detail')
            ->with('card', $this->card);
    }

    public function selectCardByUlid($ulid)
    {
        $this->ulid = $ulid;
        $this->card = Card::findByUlid($this->ulid);
        if ($this->card) {
            $this->emit('openCard', $this->card->ulid);
        }
    }

    public function closeCard()
    {
        $this->emit('closeCard', $this->ulid);
        $this->ulid = '';
        $this->card = null;
    }
}
