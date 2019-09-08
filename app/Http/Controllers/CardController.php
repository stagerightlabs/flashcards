<?php

namespace App\Http\Controllers;

use App\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CardController extends Controller
{
    /**
     * Display a listing of cards.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new card.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Card::class);
    }

    /**
     * Store a newly created card in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'domain_id' => 'required',
        ]);

        $this->authorize('create', Card::class);

        $card = Card::create([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
            'source' => $request->get('source', null),
            'created_by' => $request->user()->id,
            'domain_id' => $request->get('domain_id'),
        ]);

        activity()
            ->causedBy($request->user())
            ->performedOn($card)
            ->withProperties([
                'title' => $card->title,
                'body' => $card->body,
                'source' => $card->source,
            ])
            ->log('created');

        Session::flash('success', 'Your new card has been created.');
        return redirect()->back();
    }

    /**
     * Display the specified card.
     *
     * @param string $ulid
     * @return \Illuminate\Http\Response
     */
    public function show($ulid)
    {
        $this->authorize('view', $card);
    }

    /**
     * Show the form for editing the specified card.
     *
     * @param string $ulid
     * @return \Illuminate\Http\Response
     */
    public function edit($ulid)
    {
        //
    }

    /**
     * Update the specified card in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $ulid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ulid)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $card = Card::findByUlidOrFail($ulid);

        $this->authorize('update', $card);

        $card->title = $request->get('title');
        $card->body = $request->get('body');
        $card->source = $request->get('source', null);
        $card->save();

        activity()
            ->causedBy($request->user())
            ->performedOn($card)
            ->withProperties([
                'title' => $card->title,
                'body' => $card->body,
                'source' => $card->source,
            ])
            ->log('updated');

        Session::flash('success', 'Card updated.');
        return redirect()->back();
    }

    /**
     * Remove the specified card from storage.
     *
     * @param string $ulid
     * @return \Illuminate\Http\Response
     */
    public function destroy($ulid)
    {
        $card = Card::findByUlidOrFail($ulid);

        $this->authorize('delete', $card);

        $card->activities()->delete();
        $card->delete();

        return redirect()->back();
    }
}
