<section class="stat-card p-6 sm:p-8 mb-8">
    <header class="mb-6">
        <h2 class="font-serif font-bold text-xl text-ink">Informations du profil</h2>
        <p class="text-sm text-ink/60 mt-1">
            Mettez à jour les informations de votre compte et votre adresse e-mail.
        </p>
    </header>

    {{-- Formulaire d'envoi d'email de vérification (caché) --}}
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        {{-- Nom --}}
        <div>
            <label for="name" class="block text-sm font-semibold text-ink mb-1.5">Nom complet</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="w-full px-4 py-3 bg-parchment border border-amber/20 rounded-lg text-sm text-ink focus:outline-none focus:ring-2 focus:ring-amber transition-all @error('name') border-rust @enderror" />
            @error('name')
                <p class="text-rust text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-semibold text-ink mb-1.5">Adresse e-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="w-full px-4 py-3 bg-parchment border border-amber/20 rounded-lg text-sm text-ink focus:outline-none focus:ring-2 focus:ring-amber transition-all @error('email') border-rust @enderror" />
            @error('email')
                <p class="text-rust text-xs mt-1">{{ $message }}</p>
            @enderror

            {{-- Gestion de la vérification d'email --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-sm text-ink/70">
                        Votre adresse e-mail n'est pas vérifiée.
                        <button form="send-verification" class="text-amber hover:text-ink font-medium transition-colors underline underline-offset-4">
                            Cliquez ici pour renvoyer l'e-mail de vérification.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-sage">
                            Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-3 bg-ink text-cream font-semibold rounded-lg hover:bg-amber transition-colors duration-200 text-sm">
                Enregistrer
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-sage font-medium">
                    Enregistré avec succès.
                </p>
            @endif
        </div>
    </form>
</section>
