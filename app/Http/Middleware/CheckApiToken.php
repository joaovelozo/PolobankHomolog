<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiToken
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o cabeçalho Authorization está presente
        $token = $request->header('Authorization');

        if (!$token || $token !== 'Bearer ' . env('API_SECRET_TOKEN')) {
            return response()->json(['error' => 'Token não fornecido ou inválido'], 401);
        }

        return $next($request);
    }
}
