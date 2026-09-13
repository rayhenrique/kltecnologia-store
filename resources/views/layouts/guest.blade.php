<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title.' | ' : '' }}{{ config('app.name', 'KL Tecnologia') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-kltecnologia.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-kltecnologia.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&family=ibm-plex-mono:500,600&family=sora:600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950 text-slate-100 min-h-screen flex flex-col justify-between selection:bg-teal-500 selection:text-white hero-tech-bg">
    
    {{-- Header Minimalista para Auth --}}
    <header class="w-full border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-md sticky top-0 z-50">
        <div class="page-container flex h-16 items-center justify-between">
            <a href="{{ route('storefront.index') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-kltecnologia.png') }}" alt="KL" class="h-9 w-9 rounded-xl object-cover shadow-lg shadow-teal-500/20 group-hover:scale-105 transition duration-200" />
                <span class="font-display text-base font-bold tracking-tight text-white group-hover:text-teal-400 transition">
                    KL<span class="text-teal-400">Tecnologia</span>
                </span>
            </a>

            <div class="flex items-center gap-4 text-xs font-semibold">
                <a href="{{ route('catalog.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-slate-400 hover:text-teal-400 transition">
                    <svg class="h-3.5 w-3.5 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    Catálogo
                </a>
                <a href="{{ route('storefront.index') }}" class="inline-flex items-center gap-1.5 text-slate-300 hover:text-white rounded-lg border border-slate-800 bg-slate-900/90 px-3 py-1.5 transition hover:border-slate-700">
                    &larr; Voltar para a loja
                </a>
            </div>
        </div>
    </header>

    {{-- Área Central do Card --}}
    <main class="flex-1 flex items-center justify-center px-4 py-12 sm:py-16">
        <div class="w-full max-w-md">
            <x-flash />
            {{ $slot }}
        </div>
    </main>

    {{-- Footer com Certificação e Segurança --}}
    <footer class="w-full border-t border-slate-800/80 bg-slate-950/90 py-6 text-xs text-slate-500">
        <div class="page-container flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
            <p>
                &copy; {{ date('Y') }} KL Tecnologia. Todos os direitos reservados.
            </p>
            <div class="flex items-center gap-3 font-mono text-[11px] text-slate-400">
                <span class="inline-flex items-center gap-1">
                    <svg class="h-3 w-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd" />
                    </svg>
                    SSL 256-Bit
                </span>
                <span>•</span>
                <span>Entrega Imediata</span>
                <span>•</span>
                <span class="text-teal-400">100% Protegido</span>
            </div>
        </div>
    </footer>

</body>
</html>
