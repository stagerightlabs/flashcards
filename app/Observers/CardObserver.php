<?php

namespace App\Observers;

use App\Card;
use Illuminate\Http\Request;

class CardObserver
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the card "created" event.
     *
     * @param \App\Card $card
     * @return void
     */
    public function created(Card $card)
    {
        activity()
            ->causedBy($this->request->user())
            ->performedOn($card)
            ->withProperties([
                'title' => $card->title,
                'body' => $card->body,
                'source' => $card->source,
            ])
            ->log('created');
    }

    /**
     * Handle the card "updated" event.
     *
     * @param \App\Card $card
     * @return void
     */
    public function updated(Card $card)
    {
        activity()
            ->causedBy($this->request->user())
            ->performedOn($card)
            ->withProperties([
                'title' => $card->title,
                'body' => $card->body,
                'source' => $card->source,
            ])
            ->log('updated');
    }

    /**
     * Handle the card "deleted" event.
     *
     * @param \App\Card $card
     * @return void
     */
    public function deleting(Card $card)
    {
        $card->activities()->delete();
    }

    /**
     * Handle the card "restored" event.
     *
     * @param \App\Card $card
     * @return void
     */
    public function restored(Card $card)
    {
        //
    }

    /**
     * Handle the card "force deleted" event.
     *
     * @param \App\Card $card
     * @return void
     */
    public function forceDeleted(Card $card)
    {
        //
    }
}
