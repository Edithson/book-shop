<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="stat-card relative overflow-hidden p-6 sm:p-8 border border-amber/10 bg-white">
    {{-- Décoration de fond --}}
    <div class="absolute top-0 right-0 w-40 h-40 bg-parchment rounded-bl-full pointer-events-none"></div>

    <header class="mb-8 relative z-10">
        <h2 class="font-serif font-bold text-2xl text-ink">
            {{ __('Informations du profil') }}
        </h2>

        <p class="mt-2 text-sm text-ink/60 max-w-2xl leading-relaxed">
            {{ __("Mettez à jour les informations de votre compte et l'adresse e-mail associée.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="space-y-6 relative z-10">
        {{-- Nom complet --}}
        <div>
            <label for="name" class="block text-sm font-semibold text-ink mb-2">{{ __('Nom complet') }}</label>
            <input wire:model="name"
                   id="name"
                   name="name"
                   type="text"
                   required autofocus autocomplete="name"
                   class="w-full px-4 py-3 bg-parchment border border-amber/20 rounded-xl text-sm text-ink placeholder-ink/40 focus:outline-none focus:ring-2 focus:ring-amber transition-all @error('name') border-rust bg-rust/5 @enderror" />

            @error('name')
                <p class="text-rust text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Adresse e-mail --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-ink mb-2">{{ __('Adresse e-mail') }}</label>
            <input wire:model="email"
                   id="email"
                   name="email"
                   type="email"
                   required autocomplete="username"
                   class="w-full px-4 py-3 bg-parchment border border-amber/20 rounded-xl text-sm text-ink placeholder-ink/40 focus:outline-none focus:ring-2 focus:ring-amber transition-all @error('email') border-rust bg-rust/5 @enderror" />

            @error('email')
                <p class="text-rust text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror

            {{-- Gestion de la vérification d'email --}}
            @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber/5 border border-amber/20 rounded-xl">
                    <p class="text-sm text-ink/80 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        {{ __('Votre adresse e-mail n\'est pas vérifiée.') }}
                    </p>

                    <button wire:click.prevent="sendVerification"
                            type="button"
                            class="mt-2 text-sm font-semibold text-amber hover:text-ink transition-colors underline underline-offset-4 decoration-amber/40 hover:decoration-ink">
                        {{ __('Cliquez ici pour renvoyer l\'e-mail de vérification.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-3 text-sm font-medium text-sage flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Bouton et message de succès --}}
        <div class="flex items-center gap-4 pt-3 border-t border-amber/10">
            <button type="submit"
                    class="px-6 py-3 bg-ink text-cream font-semibold rounded-xl hover:bg-amber transition-colors duration-200 text-sm shadow-sm">
                {{ __('Enregistrer les modifications') }}
            </button>

            {{-- Notification de succès --}}
            <x-action-message class="me-3" on="profile-updated">
                <span class="text-sm text-sage font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('Profil mis à jour avec succès.') }}
                </span>
            </x-action-message>
        </div>
    </form>
</section>
