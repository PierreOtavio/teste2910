<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        // Verifica se o usuário está autenticado e tem a permissão
        if (auth()->check() && auth()->user()->hasPermissionTo($permission)) {
            return $next($request);
        }

         return redirect('teste.index')->with('error', 'Você não tem permissão para acessar esta página.');

    }
}
