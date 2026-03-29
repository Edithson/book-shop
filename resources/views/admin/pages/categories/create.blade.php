@extends('admin.index')

@section('content')
<div class="max-w-2xl space-y-6">

    {{-- En-tête --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.categories.index') }}"
           class="p-2 rounded-lg hover:bg-parchment transition-colors text-ink/40 hover:text-ink">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="font-serif font-bold text-xl">Ajouter une catégorie</h2>
            <p class="text-xs text-ink/40 mt-0.5">Le slug sera généré automatiquement.</p>
        </div>
    </div>

    {{-- Formulaire --}}
    <form method="POST" action="{{ route('admin.categories.store') }}" class="stat-card p-6 sm:p-8 space-y-5">
        @csrf

        <div>
            <label for="name" class="field-label">Nom de la catégorie <span class="text-rust">*</span></label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="ex : Programmation Web"
                class="field-input @error('name') border-rust @enderror"
                required
                autofocus
            />
            @error('name')
                <p class="text-rust text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Boutons --}}
        <div class="flex flex-col sm:flex-row gap-3 pt-4">
            <button type="submit"
                    class="flex-1 py-3 bg-ink text-cream font-semibold rounded-lg hover:bg-amber transition-colors duration-200 text-sm flex items-center justify-center gap-2">
                Enregistrer
            </button>
            <a href="{{ route('admin.categories.index') }}"
               class="px-6 py-3 border border-amber/30 rounded-lg text-sm hover:bg-parchment transition-colors text-center">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
