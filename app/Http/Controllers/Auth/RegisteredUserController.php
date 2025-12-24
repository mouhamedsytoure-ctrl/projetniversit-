<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 1) vérifier si c’est le premier utilisateur
        $isFirstUser = User::count() === 0;

        // 2) créer l’utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3) assigner le rôle
        if ($isFirstUser) {
            $user->assignRole('admin');     // ✅ admin principal
        } else {
            $user->assignRole('etudiant');  // ✅ rôle par défaut
        }

        // 4) login + redirection
        event(new Registered($user));
        auth()->login($user);

        return redirect()->route('accueil');
    }

    
}