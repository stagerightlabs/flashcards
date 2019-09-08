<?php

namespace App\Http\Middleware;

use Closure;

class CheckDomain
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->domain()) {
            return $next($request);
        }

        $domains = $request->user()->tenant->domains;

        if ($domains->isNotEmpty()) {
            $request->user()->setDomain($domains->first());
            return $next($request);
        }

        return redirect()->route('domains.create');
    }
}
