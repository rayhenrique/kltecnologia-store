<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title.' | ' : '' }}{{ config('app.name', 'KL Tecnologia') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-kltecnologia.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-kltecnologia.png') }}">

    <!-- Preload Self-Hosted Fonts -->
    <link rel="preload" href="{{ asset('fonts/dm-sans-400.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/sora-700.woff2') }}" as="font" type="font/woff2" crossorigin>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-100/70 text-slate-900 min-h-screen flex flex-col justify-between selection:bg-teal-500 selection:text-white">
    <div class="flex-1">
        @include('layouts.navigation')

        <div class="page-container mt-4">
            <x-flash />
        </div>

        <!-- Page Heading -->
        @isset($header)
            <header class="border-b border-slate-200 bg-white shadow-2xs">
                <div class="page-container py-5">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main id="conteudo" class="pb-16">
            {{ $slot }}
        </main>
    </div>

    <!-- Footer do Painel -->
    <footer class="border-t border-slate-200 bg-white py-5 text-xs text-slate-500">
        <div class="page-container flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-800">KL Tecnologia</span>
                <span>— Painel de Gestão E-commerce</span>
            </div>
            <div class="flex items-center gap-3 font-mono text-[11px] text-slate-400">
                <span class="inline-flex items-center gap-1 text-emerald-600 font-semibold">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Sistema Online
                </span>
                <span>•</span>
                <span>Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
            </div>
        </div>
    </footer>
</body>
</html>
