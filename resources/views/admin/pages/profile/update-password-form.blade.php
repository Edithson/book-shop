<section class="stat-card p-6 sm:p-8 mb-8">
    <header class="mb-6">
        <h2 class="font-serif font-bold text-xl text-ink">Modifier le mot de passe</h2>
        <p class="text-sm text-ink/60 mt-1">
            Assurez-vous d'utiliser un mot de passe long et aléatoire pour rester en sécurité.
        </p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        {{-- Mot de passe actuel --}}
        <div>
            <label for="update_password_current_password" class="block text-sm font-semibold text-ink mb-1.5">Mot de passe actuel</label>
            <input type="password" id="update_password_current_password" name="current_password" autocomplete="current-password"
                   class="w-full px-4 py-3 bg-parchment border border-amber/20 rounded-lg text-sm text-ink focus:outline-none focus:ring-2 focus:ring-amber transition-all @error('current_password', 'updatePassword') border-rust @enderror" />
            @error('current_password', 'updatePassword')
                <p class="text-rust text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nouveau mot de passe --}}
        <div>
            <label for="update_password_password" class="block text-sm font-semibold text-ink mb-1.5">Nouveau mot de passe</label>
            <input type="password" id="update_password_password" name="password" autocomplete="new-password"
                   class="w-full px-4 py-3 bg-parchment border border-amber/20 rounded-lg text-sm text-ink focus:outline-none focus:ring-2 focus:ring-amber transition-all @error('password', 'updatePassword') border-rust @enderror" />
            @error('password', 'updatePassword')
                <p class="text-rust text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirmer le mot de passe --}}
        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-semibold text-ink mb-1.5">Confirmer le nouveau mot de passe</label>
            <input type="password" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password"
                   class="w-full px-4 py-3 bg-parchment border border-amber/20 rounded-lg text-sm text-ink focus:outline-none focus:ring-2 focus:ring-amber transition-all @error('password_confirmation', 'updatePassword') border-rust @enderror" />
            @error('password_confirmation', 'updatePassword')
                <p class="text-rust text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-3 bg-ink text-cream font-semibold rounded-lg hover:bg-amber transition-colors duration-200 text-sm">
                Mettre à jour
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-sage font-medium">
                    Mot de passe mis à jour.
                </p>
            @endif
        </div>
    </form>
</section>
