<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Nearty') }} @isset($title) &middot; {{ $title }} @endisset</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-stone-100 text-stone-800">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen lg:flex">

            {{-- Sidebar --}}
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-40 w-64 bg-[#241C19] text-stone-200 transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-auto flex flex-col">
                <div class="h-16 flex items-center gap-2 px-6 border-b border-white/10">
                    <div class="w-9 h-9 rounded-xl bg-amber-500 flex items-center justify-center font-bold text-[#241C19] text-lg">N</div>
                    <div>
                        <p class="font-bold text-white leading-tight">Nearty</p>
                        <p class="text-[11px] text-stone-400 leading-tight">Titip aja, driver jemput</p>
                    </div>
                </div>

                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="home">Dashboard</x-sidebar-link>
                    <x-sidebar-link :href="route('titipan.index')" :active="request()->routeIs('titipan.*')" icon="bag">Titipan Saya</x-sidebar-link>
                    <x-sidebar-link :href="route('driver.index')" :active="request()->routeIs('driver.*')" icon="scooter">Mode Driver</x-sidebar-link>
                    <x-sidebar-link :href="route('ulasan.index')" :active="request()->routeIs('ulasan.*')" icon="star">Ulasan</x-sidebar-link>

                    @auth
                        @if(auth()->user()->isAdmin())
                            <p class="px-3 pt-4 pb-1 text-[11px] uppercase tracking-wider text-stone-500">Admin</p>
                            <x-sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" icon="users">Kelola User</x-sidebar-link>
                            <x-sidebar-link :href="route('titipan.trashed')" :active="request()->routeIs('titipan.trashed')" icon="trash">Titipan Dibatalkan</x-sidebar-link>
                        @endif
                    @endauth
                </nav>

                <div class="p-3 border-t border-white/10">
                    <x-sidebar-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" icon="cog">Profil Saya</x-sidebar-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-stone-300 hover:bg-white/5 hover:text-white transition">
                            <span>&#8630;</span> Log Out
                        </button>
                    </form>
                </div>
            </aside>

            <div @click="sidebarOpen = false" x-show="sidebarOpen" x-cloak class="fixed inset-0 bg-black/40 z-30 lg:hidden"></div>

            {{-- Main --}}
            <div class="flex-1 flex flex-col min-w-0">
                <header class="h-16 bg-white border-b border-stone-200 flex items-center justify-between px-4 lg:px-8 sticky top-0 z-20">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-stone-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        </button>
                        <div>
                            <h1 class="text-lg font-bold text-stone-800">{{ $title ?? 'Dashboard' }}</h1>
                            @isset($subtitle)<p class="text-xs text-stone-500">{{ $subtitle }}</p>@endisset
                        </div>
                    </div>

                    @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-stone-700">
                            <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-semibold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-stone-100 py-1 text-sm">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-stone-50">Profil Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="w-full text-left px-4 py-2 hover:bg-stone-50">Log Out</button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </header>

                <main class="flex-1 p-4 lg:p-8">
                    @if (session('success'))
                        <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
