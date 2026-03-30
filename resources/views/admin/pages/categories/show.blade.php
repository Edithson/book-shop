@extends('admin.index')

@section('content')
<div class="space-y-6">

    {{-- En-tête avec bouton retour --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.categories.index') }}"
           class="p-2 rounded-lg hover:bg-parchment transition-colors text-ink/40 hover:text-ink">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h2 class="font-serif font-bold text-2xl text-ink">{{ $category->name }}</h2>
            <p class="text-sm text-ink/50 mt-1">Slug : {{ $category->slug }}</p>
        </div>
    </div>

    {{-- Contenu : Liste des livres de cette catégorie --}}
    <div class="stat-card overflow-hidden">
        <div class="px-6 py-4 border-b border-amber/10 bg-parchment/30 flex justify-between items-center">
            <h3 class="font-medium text-ink">Livres dans cette catégorie ({{ $books->total() }})</h3>
            <a href="{{ route('admin.books.create') }}" class="text-xs font-semibold text-amber hover:text-ink transition-colors">
                + Ajouter un livre
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-parchment/50 border-b border-amber/10 text-ink/60">
                    <tr>
                        <th class="px-6 py-4 font-medium">Titre</th>
                        <th class="px-6 py-4 font-medium">Statut</th>
                        <th class="px-6 py-4 font-medium">Prix</th>
                        <th class="px-6 py-4 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-amber/5">
                    @forelse($books as $book)
                        <tr class="hover:bg-parchment/30 transition-colors">
                            <td class="px-6 py-4 font-medium text-ink flex items-center gap-3">
                                <img src="{{ $book->cover_url }}" alt="Couverture" class="w-8 h-10 object-cover rounded shadow-sm">
                                {{ $book->title }}
                            </td>
                            <td class="px-6 py-4">
                                @if($book->is_published)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-sage/10 text-sage">Publié</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-ink/10 text-ink/50">Brouillon</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-ink/70">
                                {{ $book->formatted_price }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.books.edit', $book) }}"
                                   class="inline-flex p-2 text-ink/40 hover:text-amber bg-ink/5 hover:bg-amber/10 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-ink/40">
                                Aucun livre n'est encore associé à cette catégorie.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($books->hasPages())
            <div class="px-6 py-4 border-t border-amber/10">
                {{ $books->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
