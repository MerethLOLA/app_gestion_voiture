<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $this->ensureIsNotRateLimited($request);

        $validated = $request->validate([
            'login' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string'],
        ]);

        $credentials = [
            'password' => $validated['password'],
            'statut' => 'actif',
        ];

        if (filter_var($validated['login'], FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $validated['login'];
        } else {
            $credentials['username'] = $validated['login'];
        }

        try {
            // The SQL schema does not include remember_token.
            $authenticated = Auth::attempt($credentials, false);
        } catch (RuntimeException) {
            $authenticated = false;
        }

        if (! $authenticated) {
            RateLimiter::hit($this->throttleKey($request), 60);

            throw ValidationException::withMessages([
                'login' => 'Identifiant ou mot de passe invalide.',
            ]);
        }

        RateLimiter::clear($this->throttleKey($request));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function ensureIsNotRateLimited(Request $request): void
    {
        $key = $this->throttleKey($request);

        if (! RateLimiter::tooManyAttempts($key, 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($key);

        throw ValidationException::withMessages([
            'login' => "Trop de tentatives. Reessayez dans {$seconds} secondes.",
        ]);
    }

    private function throttleKey(Request $request): string
    {
        return Str::lower((string) $request->input('login')).'|'.$request->ip();
    }
}
