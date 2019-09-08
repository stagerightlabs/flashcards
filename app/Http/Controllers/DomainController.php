<?php

namespace App\Http\Controllers;

use App\Domain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DomainController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $this->authorize('create', Domain::class);

        $domain = Domain::create([
            'name' => $request->get('name'),
            'tenant_id' => $request->user()->tenant_id,
        ]);

        activity()
            ->causedBy($request->user())
            ->performedOn($domain)
            ->withProperties([
                'name' => $domain->name,
            ])
            ->log('created');

        Session::flash('success', 'Your new knowledge domain is ready.');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $ulid
     * @return \Illuminate\Http\Response
     */
    public function edit($ulid)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $ulid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ulid)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $domain = Domain::findByUlidOrFail($ulid);

        $this->authorize('update', $domain);

        $domain->name = $request->get('name');
        $domain->save();

        activity()
            ->causedBy($request->user())
            ->performedOn($domain)
            ->withProperties([
                'name' => $domain->name,
            ])
            ->log('updated');

        Session::flash('success', 'Domain updated.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $ulid
     * @return \Illuminate\Http\Response
     */
    public function destroy($ulid)
    {
        $domain = Domain::findByUlidOrFail($ulid);

        $this->authorize('delete', $domain);

        if ($domain->cards()->count() > 0) {
            return Session::flash('warning', "Domains with cards cannot be removed.");
            return redirect()->back();
        }

        $domain->delete();

        Session::flash('success', 'Domain Removed');
        return redirect()->back();
    }
}
