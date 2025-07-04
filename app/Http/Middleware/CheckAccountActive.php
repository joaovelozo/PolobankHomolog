<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Check if the user is authenticated
         if (auth()->check()) {
            // Verifique se a conta do usuário está inativa
            if (auth()->user()->status === 'inactive') {
                return redirect('checkout');
            }
        }

        return $next($request);
    }
}
