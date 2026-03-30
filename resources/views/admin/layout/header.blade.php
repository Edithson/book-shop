{{-- TOP BAR --}}
<header class="bg-cream/90 backdrop-blur-sm border-b border-amber/15 h-16 flex items-center px-4 sm:px-6 gap-4 flex-shrink-0">

    {{-- Bouton menu mobile (le seul Alpine nécessaire ici : toggle sidebar) --}}
    <button class="lg:hidden p-2 rounded hover:bg-parchment transition-colors"
            @click="sidebarOpen = !sidebarOpen">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    {{-- Titre de la page --}}
    {{-- Chaque vue passe $pageTitle depuis son contrôleur, ex: compact('pageTitle') --}}
    <div class="flex items-center gap-2 min-w-0">
        <h1 class="font-serif font-bold text-lg truncate">
            {{ $pageTitle ?? 'Administration' }}
        </h1>
        @if(request()->routeIs('admin.dashboard'))
            <span class="text-ink/30 text-sm hidden sm:inline">
                — {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
            </span>
        @endif
    </div>

    {{-- Barre de recherche (desktop) --}}
    {{-- Soumet vers la route de recherche globale --}}
    <div class="hidden sm:flex flex-1 max-w-xs ml-auto">
        <form action="{{ route('admin.books.index') }}" method="GET" class="relative w-full">
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-ink/30 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
            </svg>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Rechercher un livre..."
                class="field-input pl-9 py-2 text-sm w-full"
            />
        </form>
    </div>

    {{-- Actions à droite --}}
    <div class="flex items-center gap-2 ml-2">

        {{-- Cloche notifications --}}
        {{-- Le badge rouge disparaît s'il n'y a aucune notif non lue --}}
        <div class="relative" x-data="{ open: false }">
            <button class="p-2 rounded-lg hover:bg-parchment transition-colors"
                    @click="open = !open" @click.outside="open = false">
                <svg class="w-5 h-5 text-ink/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if(($unreadNotifs ?? 0) > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-rust rounded-full"></span>
                @endif
            </button>

            {{-- Dropdown notifications (vide pour l'instant) --}}
            <div x-show="open"
                 x-transition
                 class="absolute right-0 top-full mt-2 w-72 bg-cream rounded-xl shadow-xl border border-amber/15 py-2 z-50">
                <div class="px-4 py-2 border-b border-amber/10">
                    <span class="text-xs font-semibold uppercase tracking-wider text-ink/40">Notifications</span>
                </div>
                @forelse($notifications ?? [] as $notif)
                    <div class="px-4 py-3 hover:bg-parchment transition-colors text-sm">
                        {{ $notif->message }}
                    </div>
                @empty
                    <div class="px-4 py-6 text-center text-sm text-ink/40">
                        Aucune nouvelle notification
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Raccourci : Ajouter un livre --}}
        <a href="{{ route('admin.books.create') }}"
           class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-ink text-cream text-sm rounded-lg hover:bg-amber transition-colors duration-200">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajouter
        </a>

    </div>
</header>
