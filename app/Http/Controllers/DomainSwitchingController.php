<?php

namespace App\Http\Controllers;

use App\Domain;
use Illuminate\Http\Request;

class DomainSwitchingController extends Controller
{
    /**
     * Switch to a different domain.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'domain' => 'required'
        ]);

        if ($request->input('domain') == 'create') {
            return redirect()->route('domains.create');
        }

        if ($domain = Domain::findByUlid($request->input('domain'))) {
            auth()->user()->current_domain_id = $domain->id;
            auth()->user()->save();
        }

        return redirect()->route('home');
    }
}
