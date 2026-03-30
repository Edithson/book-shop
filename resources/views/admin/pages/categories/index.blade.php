@extends('admin.index')

@section('content')
<div class="space-y-6">

    {{-- En-tête --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif font-bold text-2xl text-ink">Catégories</h2>
            <p class="text-sm text-ink/50 mt-1">Gérez les thématiques de votre bibliothèque.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-ink text-cream font-semibold rounded-lg hover:bg-amber transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouvelle catégorie
        </a>
    </div>

    {{-- Alertes --}}
    @if(session('success'))
        <div class="px-4 py-3 bg-sage/10 border border-sage/30 rounded-xl text-sage text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Liste des catégories --}}
    <div class="stat-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-parchment/50 border-b border-amber/10 text-ink/60">
                    <tr>
                        <th class="px-6 py-4 font-medium">Nom de la catégorie</th>
                        <th class="px-6 py-4 font-medium">Slug</th>
                        <th class="px-6 py-4 font-medium text-center">Livres associés</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber/5">
                    @forelse($categories as $category)
                        <tr class="hover:bg-parchment/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-ink">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4 text-ink/50">
                                {{ $category->slug }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-medium {{ $category->books_count > 0 ? 'bg-sage/10 text-sage' : 'bg-ink/5 text-ink/40' }}">
                                    {{ $category->books_count }} livre(s)
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end items-center gap-2">
                                {{-- Bouton Voir --}}
                                <a href="{{ route('admin.categories.show', $category) }}"
                                class="inline-flex p-2 text-ink/40 hover:text-sage bg-ink/5 hover:bg-sage/10 rounded-lg transition-colors" title="Voir les livres">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                {{-- Bouton Éditer --}}
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                class="inline-flex p-2 text-ink/40 hover:text-amber bg-ink/5 hover:bg-amber/10 rounded-lg transition-colors" title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- Formulaire de Suppression --}}
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la catégorie « {{ $category->name }} » ?\n\nPas d\'inquiétude : les livres associés ne seront pas supprimés (ils n\'auront juste plus de catégorie).');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex p-2 text-ink/40 hover:text-rust bg-ink/5 hover:bg-rust/10 rounded-lg transition-colors" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-ink/40">
                                Aucune catégorie n'a été trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-amber/10">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
