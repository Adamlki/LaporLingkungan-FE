<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- === KONFIGURASI API NGROK === --}}
        {{-- GANTI link content di bawah ini dengan link Ngrok kamu yang aktif --}}
        {{-- Pastikan diakhiri dengan /api --}}
        <meta name="api-base-url" content="https://ganti-link-ini.ngrok-free.app/api">
        {{-- ============================== --}}

        <title>{{ config('app.name', 'LAPOR LINGKUNGAN') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    {{-- Menggunakan font-sans (yang sudah diatur sebagai Poppins di tailwind.config.js) --}}
    <body class="font-sans antialiased"> 
        <div class="min-h-screen bg-gray-50">
            
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white border-b border-gray-100 shadow-md"> {{-- Shadow lebih jelas --}}
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
            
            <footer class="bg-white border-t border-gray-100 shadow-inner mt-12 py-6 text-center text-sm text-gray-500"> {{-- Shadow inner untuk efek kedalaman --}}
                <div class="max-w-7xl mx-auto">
                    &copy; {{ date('Y') }} Lapor Lingkungan | Proyek Praktikum Web.
                </div>
            </footer>
        </div>
    </body>
</html>