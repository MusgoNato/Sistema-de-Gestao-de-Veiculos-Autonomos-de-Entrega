<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TemPerfil
{
    public function handle(Request $request, Closure $next, ...$perfis)
    {
        $permitido = array_intersect($perfis, session('perfis', []));

        abort_unless(!empty($permitido), 403);

        return $next($request);
    }
}
