<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $credentials['username'] = Str::lower(trim($credentials['username']));

        $throttleKey = $this->throttleKey($request);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors([
                    'username' => "Demasiados intentos fallidos. Intenta nuevamente en {$seconds} segundos.",
                ]);
        }

        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey, 300);

            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors([
                    'username' => 'Las credenciales no coinciden con nuestros registros.',
                ]);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        if (! $request->user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withInput($request->only('username'))
                ->withErrors([
                    'username' => 'Este usuario se encuentra inactivo.',
                ]);
        }

        return redirect()->intended(route($request->user()->defaultLandingRoute()));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->string('username')->toString()).'|'.$request->ip());
    }
}
