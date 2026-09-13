<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' | ' : '' }}{{ config('app.name', 'KL Tecnologia') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&family=ibm-plex-mono:500,600&family=sora:600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center px-4 py-10 bg-slate-50">
            <div>
                <a href="/">
                    <x-application-logo class="w-16 h-16 fill-current text-blue-600" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-white border border-slate-200 shadow-sm overflow-hidden rounded-2xl">
                <x-flash />
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
