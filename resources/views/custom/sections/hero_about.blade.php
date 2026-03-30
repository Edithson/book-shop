  <style>
    /* Hero diagonal */
    .hero-cut {
      clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
    }

    /* Stagger animations */
    .stagger-1 { animation: fadeUp 0.7s ease 0.1s forwards; opacity: 0; }
    .stagger-2 { animation: fadeUp 0.7s ease 0.25s forwards; opacity: 0; }
    .stagger-3 { animation: fadeUp 0.7s ease 0.4s forwards; opacity: 0; }
    .stagger-4 { animation: fadeUp 0.7s ease 0.55s forwards; opacity: 0; }
    .stagger-5 { animation: fadeUp 0.7s ease 0.7s forwards; opacity: 0; }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(28px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* Section divider ornament */
    .ornament {
      display: flex;
      align-items: center;
      gap: 16px;
      color: var(--amber);
    }
    .ornament::before,
    .ornament::after {
      content: '';
      flex: 1;
      height: 1px;
      background: linear-gradient(to right, transparent, rgba(200,136,58,0.4), transparent);
    }

    /* Value card hover */
    .value-card {
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .value-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 16px 40px rgba(14,12,10,0.1);
    }

    /* Pull quote style */
    .pull-quote {
      border-left: 3px solid var(--amber);
      background: linear-gradient(to right, rgba(200,136,58,0.06), transparent);
    }

    /* CC badge */
    .cc-badge {
      background: rgba(74,103,65,0.1);
      border: 1px solid rgba(74,103,65,0.25);
    }

    /* Timeline dot */
    .timeline-dot {
      width: 10px;
      height: 10px;
      background: var(--amber);
      border-radius: 50%;
      flex-shrink: 0;
      margin-top: 6px;
      box-shadow: 0 0 0 3px rgba(200,136,58,0.2);
    }
  </style>

  <!-- HERO -->
  <section class="hero-cut bg-ink pt-28 pb-32 sm:pt-36 sm:pb-40 relative overflow-hidden">

    <!-- Lignes décoratives de fond -->
    <div class="absolute inset-0 opacity-5">
      <div class="absolute top-12 left-0 right-0 h-px bg-amber"></div>
      <div class="absolute top-24 left-0 right-0 h-px bg-amber"></div>
      <div class="absolute bottom-20 left-0 right-0 h-px bg-amber"></div>
    </div>
    <!-- Cercle décoratif -->
    <div class="absolute -right-24 top-1/2 -translate-y-1/2 w-96 h-96 rounded-full border border-amber/10"></div>
    <div class="absolute -right-16 top-1/2 -translate-y-1/2 w-64 h-64 rounded-full border border-amber/8"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative">
      <div class="stagger-1">
        <span class="inline-block text-amber text-xs font-semibold tracking-[0.2em] uppercase mb-4">
          Notre histoire
        </span>
      </div>
      <h1 class="stagger-2 font-serif font-black text-cream text-4xl sm:text-5xl lg:text-6xl leading-tight mb-6">
        Préserver un héritage,<br/>
        <em class="text-amber not-italic">partager une passion.</em>
      </h1>
      <p class="stagger-3 text-cream/60 text-lg sm:text-xl leading-relaxed max-w-2xl">
        Zérolib est né d'une conviction simple : les connaissances qui ont formé une génération
        entière de développeurs francophones méritent d'être sauvegardées et transmises.
      </p>
    </div>
  </section>
