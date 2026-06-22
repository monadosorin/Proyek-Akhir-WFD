<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIBIMA – Sistem Informasi Booking Bimbingan Mahasiswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sibima-bg {
            background-image: url('{{ asset('images/sibima-bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="sibima-bg text-gray-900 antialiased min-h-screen flex flex-col">
    <header class="w-full px-6 py-4 flex justify-between items-center max-w-6xl mx-auto">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/sibima-logo.png') }}" alt="SIBIMA" class="h-10 w-10 rounded-md shadow-sm bg-white">
            <span class="text-xl font-bold tracking-tight">SIBIMA</span>
        </div>
        <nav class="flex gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm bg-gray-800 text-white rounded-md hover:bg-gray-700">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="px-4 py-2 text-sm bg-white/80 backdrop-blur border border-gray-300 rounded-md hover:bg-white">Login</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-500">Register</a>
            @endauth
        </nav>
    </header>

    <main class="flex-1 flex items-center">
        <div class="max-w-3xl mx-auto px-6 py-12 text-center">
            <div class="bg-white/70 backdrop-blur-md rounded-2xl px-6 py-10 sm:px-10 shadow-xl">
                <h1 class="text-3xl sm:text-5xl font-bold tracking-tight">Booking Bimbingan Skripsi, Tanpa Drama.</h1>
                <p class="mt-6 text-base sm:text-lg text-gray-700">
                    SIBIMA memudahkan mahasiswa memesan slot bimbingan skripsi sekaligus membantu dosen
                    mengatur jadwal bimbingan secara terpusat. Lupakan chat WA dan email yang bolak-balik.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-500">Daftar Sekarang</a>
                    <a href="{{ route('login') }}" class="px-6 py-3 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Login</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
