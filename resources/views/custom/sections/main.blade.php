  <!-- SEARCH & FILTERS -->
  <section id="catalogue" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Search -->
    <div class="flex flex-col sm:flex-row gap-4 mb-10">
      <div class="relative flex-1">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-ink/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input
          type="text"
          x-model="search"
          placeholder="Rechercher un livre, un auteur…"
          class="search-bar w-full pl-11 pr-4 py-3.5 bg-parchment border border-amber/20 rounded text-sm text-ink placeholder-ink/40 transition-all"
        />
      </div>
      <select x-model="sortBy" class="px-4 py-3.5 bg-parchment border border-amber/20 rounded text-sm text-ink focus:outline-none focus:ring-2 focus:ring-amber">
        <option value="recent">Plus récents</option>
        <option value="alpha">A → Z</option>
        <option value="popular">Populaires</option>
      </select>
    </div>

    <!-- Category pills -->
    <div class="flex flex-wrap gap-2 mb-10">
      <template x-for="cat in categories" :key="cat">
        <button
          @click="activeCategory = cat"
          :class="activeCategory === cat ? 'cat-pill active' : 'cat-pill'"
          class="px-4 py-2 border border-ink/20 rounded-full text-xs font-medium hover:border-ink transition-all duration-200"
          x-text="cat"
        ></button>
      </template>
    </div>

    <!-- Section title -->
    <div class="flex items-center justify-between mb-8">
      <h2 class="font-serif text-2xl font-bold" x-text="`${filteredBooks.length} livre${filteredBooks.length > 1 ? 's' : ''} trouvé${filteredBooks.length > 1 ? 's' : ''}`"></h2>
      <div class="flex gap-2">
        <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-ink text-cream' : 'bg-parchment text-ink'" class="p-2 rounded transition-colors">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h7v7H3V3zm0 11h7v7H3v-7zm11-11h7v7h-7V3zm0 11h7v7h-7v-7z"/></svg>
        </button>
        <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-ink text-cream' : 'bg-parchment text-ink'" class="p-2 rounded transition-colors">
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M3 5h18v2H3V5zm0 6h18v2H3v-2zm0 6h18v2H3v-2z"/></svg>
        </button>
      </div>
    </div>

    <!-- GRID VIEW -->
    <div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      <template x-for="book in filteredBooks" :key="book.id">
        <div class="book-card bg-white rounded-lg overflow-hidden cursor-pointer border border-amber/10" @click="openModal(book)">
          <!-- Cover -->
          <div class="book-cover h-52 relative overflow-hidden" :style="`background: ${book.color}`">
            <div class="absolute inset-0 flex flex-col items-center justify-center px-4 text-center">
              <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mb-3">
                <span class="text-xl" x-text="book.icon"></span>
              </div>
              <div class="font-serif font-bold text-white text-sm leading-tight" x-text="book.title"></div>
            </div>
            <!-- Badge -->
            <span
              :class="book.free ? 'badge-free' : 'badge-premium'"
              class="absolute top-3 right-3 text-white text-[10px] font-bold px-2 py-1 rounded"
              x-text="book.free ? 'GRATUIT' : 'PREMIUM'"
            ></span>
          </div>
          <!-- Info -->
          <div class="p-4">
            <span class="text-[10px] text-amber font-semibold tracking-widest uppercase" x-text="book.category"></span>
            <h3 class="font-serif font-bold text-sm mt-1 mb-1 line-clamp-2 leading-snug" x-text="book.title"></h3>
            <p class="text-ink/50 text-xs" x-text="book.author"></p>
            <div class="flex items-center justify-between mt-4">
              <span class="text-xs text-ink/40" x-text="book.pages + ' pages'"></span>
              <button
                @click.stop="download(book)"
                class="flex items-center gap-1.5 px-3 py-1.5 bg-ink text-cream text-xs font-medium rounded hover:bg-amber transition-colors duration-200"
              >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Télécharger
              </button>
            </div>
          </div>
        </div>
      </template>
    </div>

    <!-- LIST VIEW -->
    <div x-show="viewMode === 'list'" class="flex flex-col gap-3">
      <template x-for="book in filteredBooks" :key="book.id">
        <div class="book-card bg-white rounded-lg border border-amber/10 flex gap-4 p-4 cursor-pointer" @click="openModal(book)">
          <div class="book-cover w-16 h-20 rounded flex-shrink-0 flex items-center justify-center text-2xl" :style="`background: ${book.color}`">
            <span x-text="book.icon"></span>
          </div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-2">
              <div>
                <span class="text-[10px] text-amber font-semibold tracking-widest uppercase" x-text="book.category"></span>
                <h3 class="font-serif font-bold text-sm mt-0.5 leading-snug" x-text="book.title"></h3>
                <p class="text-ink/50 text-xs mt-0.5" x-text="book.author"></p>
              </div>
              <span
                :class="book.free ? 'badge-free' : 'badge-premium'"
                class="text-white text-[10px] font-bold px-2 py-0.5 rounded flex-shrink-0"
                x-text="book.free ? 'GRATUIT' : 'PREMIUM'"
              ></span>
            </div>
            <p class="text-ink/40 text-xs mt-2 line-clamp-2" x-text="book.description"></p>
            <div class="flex items-center justify-between mt-3">
              <span class="text-xs text-ink/40" x-text="book.pages + ' pages · ' + book.year"></span>
              <button
                @click.stop="download(book)"
                class="flex items-center gap-1.5 px-3 py-1.5 bg-ink text-cream text-xs font-medium rounded hover:bg-amber transition-colors duration-200"
              >
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Télécharger
              </button>
            </div>
          </div>
        </div>
      </template>
    </div>

    <!-- Empty state -->
    <div x-show="filteredBooks.length === 0" class="text-center py-24">
      <div class="text-5xl mb-4">📚</div>
      <h3 class="font-serif text-xl font-bold mb-2">Aucun résultat</h3>
      <p class="text-ink/50 text-sm">Essayez d'autres mots-clés ou catégories.</p>
    </div>
  </section>
