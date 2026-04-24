<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PlayStation Shop') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
                background-color: #020617;
            }
            .font-heading {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased text-white selection:bg-primary-500 selection:text-white">
        <div class="min-h-screen relative flex items-center justify-center p-6 overflow-hidden">
            <!-- Dynamic Background Elements -->
            <div class="absolute inset-0 z-0">
                <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary-600/20 blur-[120px] animate-pulse"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-600/20 blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
                <div class="absolute top-[20%] right-[10%] w-[20%] h-[20%] rounded-full bg-blue-500/10 blur-[80px]"></div>
            </div>

            <!-- Glass Container -->
            <div class="relative z-10 w-full max-w-[440px] animate-in fade-in zoom-in duration-700">
                <div class="mb-8 text-center">
                    <div class="inline-flex p-4 rounded-[2rem] bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-xl border border-white/10 mb-4 shadow-2xl">
                        <svg class="w-12 h-12 text-primary-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.812 18.062c.187.188.438.281.688.281s.531-.094.719-.281c.375-.375.375-.969 0-1.344-2.188-2.188-2.188-5.75 0-7.938.375-.375.375-.969 0-1.344-.375-.375-.969-.375-1.344 0-2.906 2.938-2.906 7.688-.063 10.626zm18.376-10.626c-.375-.375-.969-.375-1.344 0-.375.375-.375.969 0 1.344 2.188 2.188 2.188 5.75 0 7.938-.375.375-.375.969 0 1.344.188.188.438.281.688.281s.531-.094.719-.281c2.906-2.938 2.906-7.688-.063-10.626zM5.5 15.375c.188.188.438.281.688.282s.5-.094.688-.282c.375-.375.375-.969 0-1.344-1.25-1.25-1.25-3.281 0-4.531.375-.375.375-.969 0-1.344-.375-.375-.969-.375-1.344 0-2 2-2 5.219-.032 7.219zm13 0c.188.188.438.281.688.282s.5-.094.688-.282c.375-.375.375-.969 0-1.344-1.25-1.25-1.25-3.281 0-4.531-.375-.375-.375-.969 0-1.344-.375-.375-.969-.375-1.344 0-2 2-2 5.219-.032 7.219zM12 4c-4.418 0-8 3.582-8 8s3.582 8 8 8 8-3.582 8-8-3.582-8-8-8zm0 2c3.314 0 6 2.686 6 6s-2.686 6-6 6-6-2.686-6-6 2.686-6 6-6z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-heading font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white to-white/60">
                        {{ config('app.name', 'PlayStation Shop') }}
                    </h1>
                    <p class="text-white/40 text-sm mt-2 font-medium">Manage your gaming empire with ease.</p>
                </div>

                <div class="bg-white/5 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] p-8 md:p-10 shadow-2xl shadow-black/50 overflow-hidden relative group">
                    <!-- Subtle Glow -->
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary-500/10 rounded-full blur-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    {{ $slot }}
                </div>
                
                <p class="text-center mt-10 text-white/30 text-xs font-bold tracking-widest uppercase">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </body>
</html>
