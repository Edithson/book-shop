<section class="stat-card p-6 sm:p-8 border border-rust/20 bg-rust/5">
    <header class="mb-6">
        <h2 class="font-serif font-bold text-xl text-rust">Supprimer le compte</h2>
        <p class="text-sm text-ink/60 mt-1">
            Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.
            Avant de supprimer votre compte, veuillez télécharger les données que vous souhaitez conserver.
        </p>
    </header>

    <button type="button" onclick="openDeleteModal()" class="px-6 py-3 bg-rust text-white font-semibold rounded-lg hover:bg-rust/80 transition-colors duration-200 text-sm">
        Supprimer mon compte
    </button>

    {{-- MODAL DE CONFIRMATION (Caché par défaut) --}}
    <div id="delete-account-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-0">
        {{-- Overlay sombre --}}
        <div class="absolute inset-0 bg-ink/50 backdrop-blur-sm transition-opacity" onclick="closeDeleteModal()"></div>

        {{-- Contenu de la modale --}}
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg p-6 sm:p-8 border border-rust/20 transform transition-all">
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <h2 class="font-serif font-bold text-xl text-ink mb-2">
                    Êtes-vous sûr de vouloir supprimer votre compte ?
                </h2>

                <p class="text-sm text-ink/60 mb-6 leading-relaxed">
                    Cette action est irréversible. Toutes vos données seront effacées.
                    Veuillez entrer votre mot de passe pour confirmer la suppression définitive.
                </p>

                <div>
                    <label for="password" class="sr-only">Mot de passe</label>
                    <input type="password" id="password" name="password" placeholder="Votre mot de passe actuel" required
                           class="w-full px-4 py-3 bg-parchment border border-amber/20 rounded-lg text-sm text-ink placeholder-ink/40 focus:outline-none focus:ring-2 focus:ring-rust transition-all @error('password', 'userDeletion') border-rust @enderror" />
                    @error('password', 'userDeletion')
                        <p class="text-rust text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="px-6 py-3 border border-amber/30 rounded-lg text-sm font-semibold text-ink hover:bg-parchment transition-colors text-center">
                        Annuler
                    </button>

                    <button type="submit" class="px-6 py-3 bg-rust text-white font-semibold rounded-lg hover:bg-rust/80 transition-colors duration-200 text-sm text-center">
                        Confirmer la suppression
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- Petit script pour le toggle de la modale --}}
<script>
    function openDeleteModal() {
        const modal = document.getElementById('delete-account-modal');
        modal.classList.remove('hidden');
        // On donne le focus au champ mot de passe après un petit délai pour l'UX
        setTimeout(() => document.getElementById('password').focus(), 100);
    }

    function closeDeleteModal() {
        const modal = document.getElementById('delete-account-modal');
        modal.classList.add('hidden');
        // Réinitialiser le champ mot de passe
        document.getElementById('password').value = '';
    }

    // Ré-ouvrir automatiquement la modale si on revient avec des erreurs de validation (mot de passe incorrect)
    @if($errors->userDeletion->isNotEmpty())
        document.addEventListener('DOMContentLoaded', () => {
            openDeleteModal();
        });
    @endif
</script>
