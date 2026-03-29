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
            <h2 class="font-serif font-bold text-xl">Modifier la catégorie</h2>
            <p class="text-xs text-ink/40 mt-0.5">Slug actuel : {{ $category->slug }}</p>
        </div>
    </div>

    {{-- Formulaire --}}
    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="stat-card p-6 sm:p-8 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="field-label">Nom de la catégorie <span class="text-rust">*</span></label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $category->name) }}"
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
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Mettre à jour
            </button>
            <a href="{{ route('admin.categories.index') }}"
               class="px-6 py-3 border border-amber/30 rounded-lg text-sm hover:bg-parchment transition-colors text-center">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection
