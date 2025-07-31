<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $user = Auth::user();

    // Verifica se o status é diferente de 'active'
    if ($user->status !== 'active') {
        Auth::logout();
        return redirect()->route('login')->withErrors([
            'email' => 'Sua conta está inativa. Em Breve Você Reberá Um Email de Ativação!.',
        ]);
    }

    // Atualiza o último login
    $user->update(['last_login' => Carbon::now()]);

    // Redirecionamento conforme o papel do usuário
    $url = '';
    if ($user->role === 'admin') {
        $url = 'admin/dashboard';
    } elseif ($user->role === 'manager') {
        $url = 'agency/dashboard';
    } elseif ($user->role === 'user') {
        $url = '/dashboard';
    }

    return redirect()->intended($url);
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
