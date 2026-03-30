<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Zérolib') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body::before {
            content: ''; position: fixed; inset: 0; pointer-events: none; z-index: 9999; opacity: 0.4;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
        }
        .zl-input {
            width: 100%; background: white; border: 1.5px solid rgba(200,136,58,0.22);
            border-radius: 10px; padding: 11px 14px; font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem; color: #0e0c0a; transition: border-color 0.2s, box-shadow 0.2s;
        }
        .zl-input:focus { outline: none; border-color: #c8883a; box-shadow: 0 0 0 3px rgba(200,136,58,0.12); }
        .zl-input::placeholder { color: rgba(14,12,10,0.32); }
        .zl-label { font-size: 0.72rem; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase; color: rgba(14,12,10,0.45); margin-bottom: 6px; display: block; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp 0.6s ease forwards; }
        .fade-up-2 { animation: fadeUp 0.6s ease 0.15s forwards; opacity: 0; }
        .fade-up-3 { animation: fadeUp 0.6s ease 0.3s forwards; opacity: 0; }
    </style>
</head>
<body style="background-color: #faf7f2; font-family: 'DM Sans', sans-serif;">

    {{-- Aucun logo, aucun wrapper Laravel -- le composant Livewire s'affiche directement --}}
    {{ $slot }}

    @livewireScripts
</body>
</html>
