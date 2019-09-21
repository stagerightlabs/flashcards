<?php

namespace App\Http\Livewire;

use App\Card;
use Livewire\Component;

class CardDetail extends Component
{
    public $ulid = '';
    public $mode = 'read';
    public $editTitle = '';
    public $editBody = '';
    public $editSource = '';
    public $errorMessage = '';
    protected $card;
    protected $listeners = [
        'cardSelected' => 'selectCardByUlid',
        'requestCardClosure' => 'closeCard',
        'requestReadMode' => 'enterReadMode'
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

    public function enterEditMode()
    {
        $this->editTitle = $this->card->title;
        $this->editBody = $this->card->body;
        $this->editSource = $this->card->source;
        $this->mode = 'edit';
    }

    public function enterReadMode()
    {
        $this->mode = 'read';
        $this->setErrorMessage('');
    }

    public function updateCard()
    {
        $this->setErrorMessage('');

        if (auth()->user()->can('update', $this->card)) {
            $this->card->title = $this->editTitle;
            $this->card->body = $this->editBody;
            $this->card->source = $this->editSource;
            $this->card->save();

            $this->emit('card.updated', $this->card->ulid);

            $this->enterReadMode();
        } else {
            $this->errorMessage = "Oops - you cant do that.";
        }
    }

    protected function setErrorMessage($message = '')
    {
        $this->errorMessage = $message;
    }
}
