<?php

namespace App\Http\Livewire;

use App\Card;
use Livewire\Component;

class CreateCardForm extends Component
{
    public $title;
    public $body;
    public $source;
    public $pageNumber;
    public $visible = false;

    protected $listeners = [
        'requestCreateCardModal' => 'showCreateCardModal',
        'requestModalClosure' => 'closeCardModal',
    ];

    public function createCard()
    {
        $this->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $card = Card::create([
            'title' => $this->title,
            'body' => $this->body,
            'source' => $this->source,
            'source_pages' => $this->pageNumber,
            'created_by' => auth()->user()->id,
            'domain_id' => auth()->user()->current_domain_id,
        ]);

        $this->emit('card.created', $card->ulid);
        $this->closeCardModal();
    }

    public function showCreateCardModal()
    {
        $this->visible = true;
    }

    public function closeCardModal()
    {
        $this->visible = false;
        $this->title = '';
        $this->body = '';
        $this->source = '';
        $this->pageNumber = '';
    }

    public function render()
    {
        return view('livewire.create-card-form');
    }
}
