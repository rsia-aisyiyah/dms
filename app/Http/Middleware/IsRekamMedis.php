<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsRekamMedis
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (
            auth()->guest() || auth()->user()->username != 'admin' &&
            auth()->user()->username != 'rm'
            && auth()->user()->username != 'casemix'
            && auth()->user()->username != 'admin_casemix'
            && auth()->user()->username != 'direksi'
            && auth()->user()->username != 'ipcn'
            && auth()->user()->username != 'poli'
        ) {
            return redirect('/');
        }

        return $next($request);
    }
}
