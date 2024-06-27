<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DontAnalistMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            auth()->check() && auth()->user()->idtipo_usuario == 1 || auth()->check() && auth()->user()->idtipo_usuario == 2
            || auth()->check() && auth()->user()->idtipo_usuario == 3
        ) {
            return $next($request);
        } else {
            return redirect('home')->with('error', 'No tienes permisos para acceder a esta página.');
        }
    }
}
