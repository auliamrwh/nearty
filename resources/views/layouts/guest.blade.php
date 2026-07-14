<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Nearty') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-100">
            <a href="/" class="flex items-center gap-2 mb-2">
                <img src="{{ asset('images/logo.png') }}" alt="Nearty Logo"
                     class="w-12 h-12 rounded-full object-cover shadow-md">
                <span class="text-xl font-bold text-slate-800">Nearty</span>
            </a>
            <p class="text-xs text-slate-400 mb-4">Titip aja, driver yang jemput jajanmu</p>

            <div class="w-full sm:max-w-md mt-2 px-6 py-6 bg-white shadow-sm transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border border-slate-200 overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
