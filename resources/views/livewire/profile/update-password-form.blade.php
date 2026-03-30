<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component
{
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password'         => ['required', 'string', Password::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="stat-card relative overflow-hidden p-6 sm:p-8 border border-amber/10 bg-white">

    {{-- Décoration de fond — identique au formulaire profil --}}
    <div class="absolute top-0 right-0 w-40 h-40 bg-parchment rounded-bl-full pointer-events-none"></div>

    <header class="mb-8 relative z-10">
        <h2 class="font-serif font-bold text-2xl text-ink">
            {{ __('Mettre à jour le mot de passe') }}
        </h2>
        <p class="mt-2 text-sm text-ink/60 max-w-2xl leading-relaxed">
            {{ __('Utilisez un mot de passe long et aléatoire pour maintenir votre compte en sécurité.') }}
        </p>
    </header>

    <form wire:submit="updatePassword" class="space-y-6 relative z-10">

        {{-- Mot de passe actuel --}}
        <div x-data="{ show: false }">
            <label for="update_password_current_password"
                   class="block text-sm font-semibold text-ink mb-2">
                {{ __('Mot de passe actuel') }}
            </label>
            <div class="relative">
                <input wire:model="current_password"
                       id="update_password_current_password"
                       name="current_password"
                       :type="show ? 'text' : 'password'"
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full px-4 py-3 bg-parchment border border-amber/20 rounded-xl text-sm text-ink placeholder-ink/40 focus:outline-none focus:ring-2 focus:ring-amber transition-all pr-11
                              @error('current_password') border-rust bg-rust/5 @enderror" />
                <button type="button" @click="show = !show"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-ink/30 hover:text-amber transition-colors">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('current_password')
                <p class="text-rust text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nouveau mot de passe --}}
        <div x-data="{ show: false }">
            <label for="update_password_password"
                   class="block text-sm font-semibold text-ink mb-2">
                {{ __('Nouveau mot de passe') }}
            </label>
            <div class="relative">
                <input wire:model="password"
                       id="update_password_password"
                       name="password"
                       :type="show ? 'text' : 'password'"
                       autocomplete="new-password"
                       placeholder="Min. 8 caractères"
                       class="w-full px-4 py-3 bg-parchment border border-amber/20 rounded-xl text-sm text-ink placeholder-ink/40 focus:outline-none focus:ring-2 focus:ring-amber transition-all pr-11
                              @error('password') border-rust bg-rust/5 @enderror"
                       oninput="updatePwdStrength(this.value)" />
                <button type="button" @click="show = !show"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-ink/30 hover:text-amber transition-colors">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            {{-- Indicateur de force --}}
            <div class="flex gap-1 mt-2">
                <div class="h-1 flex-1 rounded-full bg-ink/8 transition-all duration-300" id="pwd-bar1"></div>
                <div class="h-1 flex-1 rounded-full bg-ink/8 transition-all duration-300" id="pwd-bar2"></div>
                <div class="h-1 flex-1 rounded-full bg-ink/8 transition-all duration-300" id="pwd-bar3"></div>
                <div class="h-1 flex-1 rounded-full bg-ink/8 transition-all duration-300" id="pwd-bar4"></div>
            </div>
            <p id="pwd-strength-label" class="text-xs mt-1" style="color:rgba(14,12,10,0.35); min-height:1rem;"></p>
            @error('password')
                <p class="text-rust text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirmation mot de passe --}}
        <div x-data="{ show: false }">
            <label for="update_password_password_confirmation"
                   class="block text-sm font-semibold text-ink mb-2">
                {{ __('Confirmer le nouveau mot de passe') }}
            </label>
            <div class="relative">
                <input wire:model="password_confirmation"
                       id="update_password_password_confirmation"
                       name="password_confirmation"
                       :type="show ? 'text' : 'password'"
                       autocomplete="new-password"
                       placeholder="Répétez le mot de passe"
                       class="w-full px-4 py-3 bg-parchment border border-amber/20 rounded-xl text-sm text-ink placeholder-ink/40 focus:outline-none focus:ring-2 focus:ring-amber transition-all pr-11
                              @error('password_confirmation') border-rust bg-rust/5 @enderror" />
                <button type="button" @click="show = !show"
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-ink/30 hover:text-amber transition-colors">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <p class="text-rust text-xs mt-1.5 font-medium">{{ $message }}</p>
            @enderror
        </div>

        {{-- Bouton + message succès --}}
        <div class="flex items-center gap-4 pt-3 border-t border-amber/10">
            <button type="submit"
                    class="px-6 py-3 bg-ink text-cream font-semibold rounded-xl hover:bg-amber transition-colors duration-200 text-sm shadow-sm flex items-center gap-2">
                <span wire:loading.remove wire:target="updatePassword">
                    {{ __('Mettre à jour le mot de passe') }}
                </span>
                <span wire:loading wire:target="updatePassword"
                      class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" class="opacity-25"/>
                        <path fill="currentColor" class="opacity-75" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    Mise à jour…
                </span>
            </button>

            <x-action-message on="password-updated">
                <span class="text-sm text-sage font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ __('Mot de passe mis à jour.') }}
                </span>
            </x-action-message>
        </div>

    </form>
</section>

<script>
    function updatePwdStrength(val) {
        const bars   = ['pwd-bar1','pwd-bar2','pwd-bar3','pwd-bar4'].map(id => document.getElementById(id));
        const label  = document.getElementById('pwd-strength-label');
        const colors = ['#9c3d2e','#c8883a','#c8aa3a','#4a6741'];
        const labels = ['Très faible','Faible','Correct','Fort'];

        let score = 0;
        if (val.length >= 8)           score++;
        if (/[A-Z]/.test(val))         score++;
        if (/[0-9]/.test(val))         score++;
        if (/[^A-Za-z0-9]/.test(val))  score++;

        bars.forEach((b, i) => {
            b.style.background = i < score ? colors[score - 1] : 'rgba(14,12,10,0.08)';
        });
        label.textContent = val.length > 0 ? (labels[score - 1] ?? '') : '';
        label.style.color = val.length > 0 ? colors[score - 1] : 'rgba(14,12,10,0.35)';
    }
</script>
