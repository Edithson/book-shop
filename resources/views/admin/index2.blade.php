<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ ($pageTitle ?? 'Admin') }} — Zérolib</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>

    {{-- Config Tailwind --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        serif: ['Playfair Display', 'Georgia', 'serif'],
                        sans: ['DM Sans', 'sans-serif'],
                    },
                    colors: {
                        ink: '#0e0c0a', parchment: '#f5f0e8', cream: '#faf7f2',
                        amber: '#c8883a', amber2: '#e6a24e', rust: '#9c3d2e',
                        sage: '#4a6741', slate: '#2d3748',
                    },
                }
            }
        }
    </script>

    <style>
        :root { --ink:#0e0c0a; --parchment:#f5f0e8; --cream:#faf7f2; --amber:#c8883a; --rust:#9c3d2e; --sage:#4a6741; }
        * { box-sizing: border-box; }
        body { background-color: var(--cream); color: var(--ink); font-family: 'DM Sans', sans-serif; }
        body::before {
            content: ''; position: fixed; inset: 0; pointer-events: none; z-index: 9999; opacity: 0.4;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
        }
        .sidebar { background: var(--ink); width: 260px; min-height: 100vh; flex-shrink: 0; transition: transform 0.3s ease; }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px; padding: 10px 20px;
            border-radius: 8px; color: rgba(250,247,242,0.55); font-size: 0.875rem;
            font-weight: 500; transition: all 0.2s ease; text-decoration: none;
        }
        .sidebar-link:hover { background: rgba(200,136,58,0.12); color: var(--amber); }
        .sidebar-link.active { background: rgba(200,136,58,0.18); color: var(--amber); border-left: 2px solid var(--amber); }
        .stat-card { background: white; border: 1px solid rgba(200,136,58,0.15); border-radius: 12px; transition: box-shadow 0.2s, transform 0.2s; }
        .stat-card:hover { box-shadow: 0 8px 32px rgba(14,12,10,0.1); transform: translateY(-2px); }
        .data-table th { font-size: 0.7rem; letter-spacing: 0.08em; text-transform: uppercase; color: rgba(14,12,10,0.4); font-weight: 600; padding: 12px 16px; border-bottom: 1px solid rgba(200,136,58,0.2); }
        .data-table td { padding: 14px 16px; font-size: 0.875rem; border-bottom: 1px solid rgba(14,12,10,0.05); vertical-align: middle; }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:hover td { background: rgba(245,240,232,0.6); }
        .field-input { width: 100%; background: var(--parchment); border: 1px solid rgba(200,136,58,0.25); border-radius: 8px; padding: 10px 14px; font-family: 'DM Sans', sans-serif; font-size: 0.875rem; color: var(--ink); transition: border-color 0.2s, box-shadow 0.2s; }
        .field-input:focus { outline: none; border-color: var(--amber); box-shadow: 0 0 0 3px rgba(200,136,58,0.12); }
        .field-label { font-size: 0.75rem; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; color: rgba(14,12,10,0.45); margin-bottom: 6px; display: block; }
        .badge { padding: 3px 10px; border-radius: 999px; font-size: 0.7rem; font-weight: 600; }
        .badge-free { background: rgba(74,103,65,0.12); color: var(--sage); }
        .badge-premium { background: rgba(200,136,58,0.14); color: var(--amber); }
        .badge-pending { background: rgba(156,61,46,0.12); color: var(--rust); }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--cream); }
        ::-webkit-scrollbar-thumb { background: #c8b89a; border-radius: 3px; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(14,12,10,0.5); z-index: 39; }
        @media (max-width: 1024px) {
            .sidebar { position: fixed; top: 0; left: 0; bottom: 0; z-index: 40; transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
        }
    </style>

    @stack('styles')
</head>

{{--
    x-data minimal : seulement sidebarOpen pour le menu mobile.
    Toute la logique métier est dans les contrôleurs Laravel.
--}}
<body class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    @include('admin.layout.sidebar')

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        @include('admin.layout.header')

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mx-6 mt-4 px-4 py-3 bg-sage/10 border border-sage/30 text-sage text-sm rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mx-6 mt-4 px-4 py-3 bg-rust/10 border border-rust/30 text-rust text-sm rounded-lg flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>
