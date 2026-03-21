<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialAuthController extends Controller
{
    // 1. Redirection vers le fournisseur (Google/Facebook)
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // 2. Traitement des données au retour
    public function callback($provider)
    {
        try {
            // Récupère les infos de l'utilisateur depuis Google/Facebook
            $socialUser = Socialite::driver($provider)->user();

            // Cherche un utilisateur avec cet email, ou le crée s'il n'existe pas
            $user = User::updateOrCreate([
                'email' => $socialUser->getEmail(),
            ], [
                'name' => $socialUser->getName(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'provider_token' => $socialUser->token,
                // Le mot de passe reste null (car rendu optionnel dans notre migration)
            ]);

            // Connecte l'utilisateur
            Auth::login($user, true);

            // Redirige vers le tableau de bord (ou la boutique)
            return redirect()->route('dashboard');

        } catch (Exception $e) {
            // En cas de problème (ex: l'utilisateur a annulé), on le renvoie au login
            return redirect()->route('login')->withErrors([
                'email' => 'Échec de la connexion via ' . ucfirst($provider)
            ]);
        }
    }
}
