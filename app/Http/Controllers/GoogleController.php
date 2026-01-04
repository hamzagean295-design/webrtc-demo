<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Laravel\Socialite\Socialite;
use Illuminate\Support\Str;
use App\Models\User;

class GoogleController extends Controller
{
    public function register(): View
    {
        return view('auth.register');
    }

    public function redirect(Request $request)
    {
        $role = $request->input('role');
        session(['role' => $role]);

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // On vérifie si on a un "post-it" (un rôle en session)
            if (session()->has('role')) {
                // CAS: INSCRIPTION
                $role = session('role');

                // On jette le "post-it" pour qu'il ne soit pas réutilisé
                session()->forget('role');

                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'role' => $role, // On assigne le rôle sauvegardé !
                    'password' => Hash::make(Str::random(24)) // Mot de passe aléatoire
                ]);
            } else {
                // CAS: CONNEXION (pas de rôle en session)
                $user = User::where('email', $googleUser->email)->first();

                if (!$user) {
                    // Si l'utilisateur n'existe pas, on le renvoie à l'inscription
                    return redirect()->route('register')
                        ->withErrors(['email' => 'Ce compte n\'existe pas. Veuillez d\'abord vous inscrire.']);
                }
            }

            // On connecte l'utilisateur
            Auth::login($user);

            // On redirige vers le bon tableau de bord
            // (Cette logique peut être améliorée plus tard avec un middleware)
            return redirect()->intended('/dashboard');
            // if ($user->role === 'medecin') {
            //     return redirect()->intended('/medecin');
            // }
            //
            // return redirect()->intended('/patient');
        } catch (Exception $e) {
            return redirect()->route('register')->withErrors(['email' => 'Une erreur est survenue.']);
        }
    }
}
