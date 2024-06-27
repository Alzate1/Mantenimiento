<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->idtipo_usuario == 1) {
            return $next($request);
        }else{
            return redirect('home')->with('error', 'No tienes permisos para acceder a esta página.');
        }

    }
}
