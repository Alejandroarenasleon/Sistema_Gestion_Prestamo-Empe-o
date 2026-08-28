<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
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
            'login' => ['required', 'string', 'max:40'],
            'password' => ['required', 'string'],
        ]);

        $usuario = Usuario::where('login', $credentials['login'])->first();

        if (! $usuario || ! $usuario->activo) {
            throw ValidationException::withMessages([
                'login' => 'Credenciales inválidas o usuario inactivo.',
            ]);
        }

        if (! Hash::check($credentials['password'], $usuario->password_hash)) {
            throw ValidationException::withMessages([
                'login' => 'Credenciales inválidas.',
            ]);
        }

        Auth::login($usuario, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
