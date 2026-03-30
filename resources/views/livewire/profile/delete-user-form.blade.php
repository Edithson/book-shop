<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';
    public bool $showingModal = false;

    public function confirmDeletion()
    {
        $this->resetErrorBag();
        $this->password = '';
        $this->showingModal = true;
    }

    public function closeModal()
    {
        $this->showingModal = false;
        $this->reset('password');
    }

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

{{-- CONTENEUR GLOBAL UNIQUE POUR LIVEWIRE --}}
<div>

    {{-- LA CARTE DE LA ZONE DE DANGER (Avec son overflow-hidden) --}}
    <section class="stat-card relative overflow-hidden p-6 sm:p-8 border border-rust/20 bg-rust/5">
        <div class="absolute top-0 right-0 w-32 h-32 bg-rust/5 rounded-bl-full pointer-events-none"></div>

        <header class="mb-6 relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-rust/10 flex items-center justify-center text-rust">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h2 class="font-serif font-bold text-xl text-rust">Zone de danger</h2>
            </div>
            <p class="text-sm text-ink/60 mt-2 max-w-2xl leading-relaxed">
                La suppression de votre compte est définitive. Toutes vos données, téléchargements et historiques seront effacés de nos serveurs.
            </p>
        </header>

        <button type="button"
                wire:click="confirmDeletion"
                class="relative z-10 px-5 py-2.5 bg-white border-2 border-rust text-rust font-semibold rounded-lg hover:bg-rust hover:text-white transition-all duration-300 text-sm">
            Supprimer mon compte
        </button>
    </section>

    {{-- LA MODALE EST MAINTENANT TOTALEMENT EN DEHORS DE LA SECTION --}}
    @if($showingModal)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 zl-fade-in">

            <div class="absolute inset-0 bg-ink/70 backdrop-blur-sm" wire:click="closeModal"></div>

            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-rust/20 zl-slide-up">

                <div class="h-1.5 w-full bg-rust"></div>

                <div class="p-6 sm:p-8">
                    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-rust/10 text-rust mb-5 mx-auto">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>

                    <h3 class="font-serif font-bold text-xl text-ink text-center mb-2">Confirmation requise</h3>
                    <p class="text-sm text-ink/60 text-center mb-6 leading-relaxed">
                        Cette action est <strong class="text-rust">irréversible</strong>. Entrez votre mot de passe actuel pour confirmer la suppression.
                    </p>

                    <form wire:submit="deleteUser" class="space-y-5">
                        <div>
                            <input type="password"
                                   wire:model="password"
                                   placeholder="Mot de passe actuel"
                                   required autofocus
                                   class="w-full px-4 py-3.5 bg-parchment border border-amber/20 rounded-xl text-sm text-ink placeholder-ink/40 focus:outline-none focus:ring-2 focus:ring-rust transition-all @error('password') border-rust bg-rust/5 @enderror" />

                            @error('password')
                                <p class="text-rust text-xs mt-1.5 font-medium text-center">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row gap-3 pt-3">
                            <button type="button" wire:click="closeModal" class="w-full sm:w-1/2 px-4 py-3.5 bg-parchment text-ink font-semibold rounded-xl hover:bg-amber/20 transition-colors text-sm">
                                Annuler
                            </button>
                            <button type="submit" class="w-full sm:w-1/2 px-4 py-3.5 bg-rust text-white font-semibold rounded-xl hover:bg-rust/90 transition-colors text-sm shadow-lg shadow-rust/20">
                                Confirmer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- LES STYLES SONT ÉGALEMENT EN DEHORS DU @IF POUR ÉVITER LE CLIGNOTEMENT --}}
    <style>
        @keyframes zlFadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes zlSlideUp { from { opacity: 0; transform: translateY(20px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .zl-fade-in { animation: zlFadeIn 0.2s ease-out forwards; }
        .zl-slide-up { animation: zlSlideUp 0.3s ease-out forwards; }
    </style>

</div>
