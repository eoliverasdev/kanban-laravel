<?php

namespace App\Http\Controllers;

use App\Models\User; // Necessari per crear nous usuaris
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Necessari per encriptar la contrasenya

class AuthController extends Controller
{
    /**
     * Mostra el formulari d'inici de sessió.
     */
    public function showLoginForm()
    {
        // Retorna la vista de login (resources/views/auth/login.blade.php)
        return view('auth.login');
    }

    /**
     * Processa l'intent d'inici de sessió.
     */
    public function login(Request $request)
    {
        // 1. Validació de credencials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Intent d'inici de sessió
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirecció a la pàgina de taulers
            return redirect()->intended(route('boards.index'));
        }

        // 3. Fallada d'autenticació
        return back()->withErrors([
            'email' => 'Les credencials proporcionades no coincideixen amb els nostres registres.',
        ])->onlyInput('email');
    }

    /**
     * Mostra el formulari de registre.
     */
    public function showRegisterForm()
    {
        // Retorna la vista de registre (resources/views/auth/register.blade.php)
        return view('auth.register');
    }

    /**
     * Processa el registre del nou usuari.
     */
    public function register(Request $request)
    {
        // 1. Validació de dades
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Creació de l'Usuari
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // 3. Inici de Sessió automàtic
        Auth::login($user);

        // 4. Redirecció a la pàgina de taulers
        return redirect()->route('boards.index')
                         ->with('success', 'Benvingut! El teu compte ha estat creat correctament.');
    }
}