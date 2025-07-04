<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StarckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o cabeçalho Authorization está presente
        $token = $request->header('Authorization');

        if (!$token || $token !== 'Bearer ' . env('API_STARK_TOKEN')) {
            return response()->json(['error' => 'Token não fornecido ou inválido'], 401);
        }

        return $next($request);
    }
}
