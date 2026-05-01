<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PlayStation Pro') }} Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS, Alpine JS, SweetAlert2 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                    }
                }
            }
        }
        window.tenantId = '{{ auth()->user()->tenant_id ?? "default" }}';

        function deviceManager(deviceId, initialStatus, hourlyRate, fixedRate, startedAt) {
            return {
                id: deviceId,
                status: initialStatus,
                hasSession: !!startedAt,
                startTime: startedAt ? new Date(startedAt) : null,
                timerDisplay: '00:00:00',
                costDisplay: '0.00',
                hourlyRate: parseFloat(hourlyRate) || 0,
                fixedRate: parseFloat(fixedRate) || 0,
                interval: null,

                init() {
                    if (this.hasSession) {
                        this.startTimer();
                    }

                    if (window.Echo) {
                        window.Echo.private(`tenants.${window.tenantId}`)
                            .listen('DeviceTurnedOn', (e) => {
                                if (e.device.id === this.id) {
                                    this.status = 'ON';
                                    this.stopTimer();
                                    this.startTime = null;
                                    this.hasSession = false;
                                }
                            })
                            .listen('DeviceTurnedOff', (e) => {
                                if (e.device.id === this.id) {
                                    this.status = 'OFF';
                                    this.stopTimer();
                                    this.startTime = null;
                                    this.hasSession = false;
                                }
                            });
                    }
                },

                startTimer() {
                    if (!this.startTime) return;
                    this.updateDisplay();
                    this.interval = setInterval(() => this.updateDisplay(), 1000);
                },

                stopTimer() {
                    if (this.interval) clearInterval(this.interval);
                    this.timerDisplay = '00:00:00';
                    this.costDisplay = '0.00';
                },

                updateDisplay() {
                    if (!this.startTime) return;
                    const now = new Date();
                    const diff = Math.floor((now - this.startTime) / 1000);
                    if (diff < 0) return;

                    const h = Math.floor(diff / 3600);
                    const m = Math.floor((diff % 3600) / 60);
                    const s = diff % 60;

                    this.timerDisplay = [h, m, s].map(v => v.toString().padStart(2, '0')).join(':');
                    const hours = diff / 3600;
                    const total = this.fixedRate + (hours * this.hourlyRate);
                    this.costDisplay = total.toFixed(2);
                }
            }
        };

        // UI Restriction logic without modifying existing Blade templates
        document.addEventListener('DOMContentLoaded', () => {
            const hasRole = (role) => @json(auth()->user()?->hasRole('owner') ?? false);
            const permissions = @json(auth()->user()?->role?->permissions->pluck('name')->unique()->values() ?? []);

            if(!hasRole('owner')) {
                // Disable/Hide sensitive buttons based on text or common action patterns
                const buttons = document.querySelectorAll('button, a');
                buttons.forEach(btn => {
                    const text = btn.innerText.toLowerCase();
                    const href = btn.getAttribute('href') || '';
                    
                    // Example: Restricted strings
                    const restrictedTerms = ['delete', 'destroy', 'settings', 'expenses', 'reports'];
                    if (restrictedTerms.some(term => text.includes(term)) || restrictedTerms.some(term => href.includes(term))) {
                        btn.style.opacity = '0.5';
                        btn.style.pointerEvents = 'none';
                        btn.setAttribute('title', 'Action restricted by administrator');
                    }
                });
            }
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        @media print {
            aside, 
            header, 
            footer, 
            .no-print,
            .fixed,
            .sticky {
                display: none !important;
            }
            .lg\:ps-72 {
                padding-inline-start: 0 !important;
            }
            body, .bg-gray-50 {
                background-color: white !important;
            }
            main {
                padding-top: 0 !important;
                padding-bottom: 0 !important;
            }
            .max-w-7xl {
                max-width: none !important;
                width: 100% !important;
            }
            /* Reset absolute margins that might be added by browser */
            @page {
                margin: 0.5cm;
            }
        }
    </style>
</head>
<body class="h-full font-sans text-gray-900 antialiased {{ app()->getLocale() == 'ar' ? 'rtl-mode' : 'ltr-mode' }}" 
      x-data="{ 
        sidebarOpen: false, 
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
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            @if(session('success'))
                Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
            @endif
            @if(session('error'))
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Access Restricted',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#2563eb',
                    customClass: {
                        popup: 'rounded-[2rem]',
                        confirmButton: 'rounded-2xl px-6 py-3 font-bold'
                    }
                });
            @endif
            @if($errors->any())
                @foreach($errors->all() as $error)
                    Toast.fire({ icon: 'error', title: '{{ $error }}' });
                @endforeach
            @endif
        }
      }">
    
    <!-- Toast Notifications -->
    <div class="fixed bottom-8 end-8 z-[100] space-y-4 w-80">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-y-4 opacity-0"
                 x-transition:enter-end="translate-y-0 opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0 translate-x-10"
                 :class="{
                    'bg-gray-900': toast.type === 'success',
                    'bg-red-600': toast.type === 'error',
                    'bg-blue-600': toast.type === 'info'
                 }"
                 class="p-5 rounded-2xl shadow-2xl text-white flex items-center gap-4 border border-white/10 glass">
                <div class="w-2 h-2 rounded-full animate-pulse" :class="toast.type === 'success' ? 'bg-green-400' : 'bg-white'"></div>
                <p class="text-sm font-bold flex-1" x-text="toast.message"></p>
                <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="opacity-50 hover:opacity-100">&times;</button>
            </div>
        </template>
    </div>

    <!-- Generic Confirm Modal -->
    <div x-show="confirmModal.open" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm"
         x-transition:enter="transition opacity-0 duration-300">
        <div class="bg-white w-full max-w-sm rounded-[2.5rem] p-8 shadow-2xl text-center space-y-6"
             x-transition:enter="transition scale-95 duration-300">
            <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center text-red-600 mx-auto">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <h3 class="text-xl font-heading font-black text-gray-900" x-text="confirmModal.title"></h3>
                <p class="text-sm text-gray-400 mt-2" x-text="confirmModal.message"></p>
            </div>
            <div class="flex gap-4">
                <button @click="confirmModal.open = false" class="flex-1 py-4 rounded-2xl bg-gray-100 font-bold text-gray-500 hover:bg-gray-200 transition-all">No, Cancel</button>
                <button @click="confirmModal.onConfirm(); confirmModal.open = false" class="flex-1 py-4 rounded-2xl bg-red-600 font-bold text-white hover:bg-red-700 transition-all">Yes, Proceed</button>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden"
         @click="sidebarOpen = false"></div>

    <!-- Sidebar Component -->
    @include('layouts.partials.sidebar')

    <div class="lg:ps-72 flex flex-col min-h-screen">
        
        <!-- Topbar Component -->
        @include('layouts.partials.topbar')

        <!-- Main Content Area -->
        <main class="flex-1 py-10">
            <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>

        <!-- Footer Component -->
        @include('layouts.partials.footer')
    </div>

</body>
</html>
