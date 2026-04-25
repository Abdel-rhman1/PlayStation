<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PlayStation Pro') }} | System Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS & Alpine JS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a',
                        },
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .admin-sidebar { background: #0f172a; color: white; }
    </style>
</head>
<body class="h-full font-sans text-gray-900 antialiased {{ app()->getLocale() == 'ar' ? 'rtl-mode' : 'ltr-mode' }}" 
      x-data="{ 
        sidebarOpen: false, 
        appLocale: '{{ app()->getLocale() }}',
        toasts: [],
        addToast(message, type = 'success') {
            const id = Date.now();
            this.toasts.push({ id, message, type });
            setTimeout(() => this.toasts = this.toasts.filter(t => t.id !== id), 5000);
        },
        confirmModal: { open: false, title: '', message: '', onConfirm: null },
        askConfirm(title, message, callback) {
            this.confirmModal = { open: true, title, message, onConfirm: callback };
        },
        init() {
            @if(session('success')) this.addToast('{{ session('success') }}', 'success'); @endif
            @if(session('error')) this.addToast('{{ session('error') }}', 'error'); @endif
        }
      }">
    
    <!-- Toast Notifications -->
    <div class="fixed bottom-8 end-8 z-[100] space-y-4 w-80">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-y-4 opacity-0"
                 x-transition:enter-end="translate-y-0 opacity-100"
                 class="p-5 rounded-2xl shadow-2xl text-white flex items-center gap-4 border border-white/10 glass bg-slate-900">
                <div class="w-2 h-2 rounded-full bg-primary-400 animate-pulse"></div>
                <p class="text-sm font-bold flex-1" x-text="toast.message"></p>
                <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="opacity-50 hover:opacity-100">&times;</button>
            </div>
        </template>
    </div>

    <!-- Confirm Modal -->
    <div x-show="confirmModal.open" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white w-full max-w-sm rounded-[2.5rem] p-8 shadow-2xl text-center space-y-6">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center text-red-600 mx-auto">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="text-xl font-heading font-black text-gray-900" x-text="confirmModal.title"></h3>
                <p class="text-sm text-gray-400 mt-2" x-text="confirmModal.message"></p>
            </div>
            <div class="flex gap-4">
                <button @click="confirmModal.open = false" class="flex-1 py-4 rounded-2xl bg-gray-100 font-bold text-gray-500">Cancel</button>
                <button @click="confirmModal.onConfirm(); confirmModal.open = false" class="flex-1 py-4 rounded-2xl bg-red-600 font-bold text-white shadow-lg">Proceed</button>
            </div>
        </div>
    </div>

    <!-- Sidebar Component -->
    @include('layouts.partials.admin-sidebar')

    <div class="lg:ps-72 flex flex-col min-h-screen">
        <!-- Topbar -->
        <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-md border-b border-slate-100">
            <div class="px-8 h-20 flex items-center justify-between">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-400"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg></button>
                <div class="text-xs font-black uppercase tracking-[0.3em] text-slate-400">System Admin Portal</div>
                <div class="flex items-center gap-4">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-black uppercase tracking-widest text-red-500 hover:text-red-600 transition-colors">Sign Out</button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 py-12">
            <div class="px-8 max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
