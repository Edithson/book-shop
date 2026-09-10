<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class SocialAuthController extends Controller
{
    // 1. Redirection vers le fournisseur (Google/Facebook)
    public function redirect($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404, 'Fournisseur d\'authentification non pris en charge.');
        }

        return Socialite::driver($provider)->redirect();
    }

    // 2. Traitement des données au retour
    public function callback($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login')->withErrors([
                'email' => 'Fournisseur d\'authentification non valide.'
            ]);
        }

        try {
            // Tentative stateful puis fallback automatique sur stateless() si problème de session/state (ex: VPS / proxy)
            try {
                $socialUser = Socialite::driver($provider)->user();
            } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
                $socialUser = Socialite::driver($provider)->stateless()->user();
            }

            if (!$socialUser || !$socialUser->getEmail()) {
                throw new Exception('Impossible de récupérer les informations de compte depuis ' . ucfirst($provider));
            }

            // Cherche un utilisateur avec cet email
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // L'utilisateur existe déjà. On associe les détails du fournisseur social
                $user->update([
                    'provider'          => $user->provider ?: $provider,
                    'provider_id'       => $user->provider_id ?: $socialUser->getId(),
                    'provider_token'    => $socialUser->token ?? $user->provider_token,
                    'email_verified_at' => $user->email_verified_at ?: now(),
                ]);
            } else {
                // L'utilisateur n'existe pas, on le crée avec type_id = 1 (utilisateur standard)
                $user = User::create([
                    'email'             => $socialUser->getEmail(),
                    'name'              => $socialUser->getName() ?? explode('@', $socialUser->getEmail())[0],
                    'provider'          => $provider,
                    'provider_id'       => $socialUser->getId(),
                    'provider_token'    => $socialUser->token ?? null,
                    'type_id'           => 1,
                    'email_verified_at' => now(),
                ]);
            }

            // Connecte l'utilisateur
            Auth::login($user, true);

            // Redirige vers la destination appropriée
            if ($user->type_id == 1) {
                return redirect()->route('home');
            } else {
                return redirect()->route('admin.dashboard');
            }

        } catch (Exception $e) {
            Log::error("Échec de l'authentification sociale ({$provider}) : " . $e->getMessage(), [
                'exception' => $e
            ]);

            return redirect()->route('login')->withErrors([
                'email' => 'Échec de la connexion via ' . ucfirst($provider) . '. Veuillez réessayer ou utiliser la connexion classique.'
            ]);
        }
    }
}
