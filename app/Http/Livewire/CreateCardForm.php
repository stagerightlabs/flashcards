<?php

namespace App\Http\Livewire;

use App\Card;
use Livewire\Component;

class CreateCardForm extends Component
{
    public $title;
    public $body;
    public $source;
    public $visible = false;
    protected $listeners = ['showCreateCardModal' => 'showModal'];

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
            'created_by' => auth()->user()->id,
            'domain_id' => auth()->user()->current_domain_id,
        ]);

        $this->title = '';
        $this->body = '';
        $this->source = '';

        $this->emit('cardCreated', $card->ulid);
    }

    public function showModal()
    {
        $this->visible = true;
    }

    public function cancelCard()
    {
        $this->visible = false;
        $this->title = '';
        $this->body = '';
        $this->source = '';
    }

    public function render()
    {
        return view('livewire.create-card-form');
    }
}
