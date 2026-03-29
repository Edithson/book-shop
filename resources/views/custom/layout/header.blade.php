  <!-- NAVIGATION -->
  <header class="fixed top-0 left-0 right-0 z-50 bg-cream/90 backdrop-blur-sm border-b border-amber/20">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
      <!-- Logo -->
      <a href="#" class="flex items-center gap-2 group">
        <div class="w-8 h-8 bg-ink rounded flex items-center justify-center">
          <svg class="w-4 h-4 text-amber" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
          </svg>
        </div>
        <span class="font-serif text-xl font-bold tracking-tight">Zéro<span class="text-amber">lib</span></span>
      </a>

      <!-- Desktop nav -->
      <div class="hidden md:flex items-center gap-8">
        <a href="#" class="nav-link text-sm font-medium text-ink/70 hover:text-ink transition-colors">Catalogue</a>
        <a href="#" class="nav-link text-sm font-medium text-ink/70 hover:text-ink transition-colors">Catégories</a>
        <a href="#" class="nav-link text-sm font-medium text-ink/70 hover:text-ink transition-colors">À propos</a>
        <button class="px-4 py-2 bg-ink text-cream text-sm font-medium rounded hover:bg-amber transition-colors duration-200">
          Connexion
        </button>
      </div>

      <!-- Mobile burger -->
      <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2">
        <div class="w-5 h-0.5 bg-ink mb-1 transition-all" :class="mobileMenu ? 'rotate-45 translate-y-1.5' : ''"></div>
        <div class="w-5 h-0.5 bg-ink mb-1 transition-all" :class="mobileMenu ? 'opacity-0' : ''"></div>
        <div class="w-5 h-0.5 bg-ink transition-all" :class="mobileMenu ? '-rotate-45 -translate-y-1.5' : ''"></div>
      </button>
    </nav>

    <!-- Mobile menu -->
    <div x-show="mobileMenu" x-transition class="md:hidden bg-cream border-t border-amber/20 px-4 py-4 flex flex-col gap-4">
      <a href="#" class="text-sm font-medium text-ink/70">Catalogue</a>
      <a href="#" class="text-sm font-medium text-ink/70">Catégories</a>
      <a href="#" class="text-sm font-medium text-ink/70">À propos</a>
      <button class="px-4 py-2 bg-ink text-cream text-sm font-medium rounded w-full">Connexion</button>
    </div>
  </header>
