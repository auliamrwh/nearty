<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nearty — Titip Beli Jajanan Lewat Driver Terdekat</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#0f172a] text-slate-100 min-h-screen flex items-center justify-center px-6">
    <div class="max-w-xl text-center">
        <div class="w-16 h-16 rounded-2xl bg-blue-500 text-[#0f172a] font-bold text-3xl flex items-center justify-center mx-auto mb-6">N</div>
        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3">Nearty</h1>
        <p class="text-slate-300 mb-8">
            Titip beli jajanan lewat driver terdekat, tanpa perlu keluar kost.
            Nitip ke sesama pengguna yang lagi jadi driver — mereka belanja, kamu tinggal bayar QR atau COD.
        </p>
        <div class="flex items-center justify-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl bg-blue-500 text-[#0f172a] font-semibold hover:bg-blue-400 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95">Buka Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl bg-blue-500 text-[#0f172a] font-semibold hover:bg-blue-400 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95">Masuk</a>
                <a href="{{ route('register') }}" class="px-6 py-3 rounded-xl border border-slate-500 text-slate-200 font-semibold hover:bg-white/5 transition-all duration-300 ease-in-out hover:scale-105 active:scale-95">Daftar</a>
            @endauth
        </div>
    </div>
</body>
</html>
