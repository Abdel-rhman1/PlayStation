@extends('layouts.app')

@section('content')
<div class="space-y-8" x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') || 'profile' }">
    <!-- Page Header -->
    <div>
        <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('settings.title') }}</h2>
        <p class="text-gray-500 text-sm">{{ __('settings.subtitle') }}</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="flex items-center gap-1 bg-white p-1.5 rounded-[1.5rem] border border-gray-100 shadow-sm w-fit">
        <button @click="activeTab = 'profile'" 
                :class="activeTab === 'profile' ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-50'"
                class="px-6 py-3 rounded-2xl text-sm font-bold transition-all duration-200">
            {{ __('settings.profile') }}
        </button>
        <button @click="activeTab = 'billing'" 
                :class="activeTab === 'billing' ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-50'"
                class="px-6 py-3 rounded-2xl text-sm font-bold transition-all duration-200">
            {{ __('settings.subscription') }}
        </button>
        <button @click="activeTab = 'limits'" 
                :class="activeTab === 'limits' ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-500 hover:bg-gray-50'"
                class="px-6 py-3 rounded-2xl text-sm font-bold transition-all duration-200">
            {{ __('settings.limits') }}
        </button>
    </div>

    <!-- Tab Content -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 min-h-[500px]">
        
        <!-- Profile Tab -->
        <div x-show="activeTab === 'profile'" x-cloak class="p-10 max-w-2xl space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="flex items-center gap-6 pb-8 border-b border-gray-50">
                <div class="w-24 h-24 rounded-[2rem] bg-primary-600 flex items-center justify-center text-white text-3xl font-heading font-black shadow-xl shadow-primary-100">
                    {{ substr($user->name ?? 'A', 0, 1) }}
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ __('settings.account_details') }}</h3>
                    <p class="text-sm text-gray-400">{{ __('settings.account_subtitle') }}</p>
                </div>
            </div>

            <form action="{{ route('settings.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('settings.full_name') }}</label>
                        <input type="text" name="name" class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 font-bold" value="{{ $user->name }}">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('settings.email') }}</label>
                        <input type="email" name="email" class="w-full bg-gray-50 border-gray-100 rounded-2xl px-5 py-4 focus:ring-2 focus:ring-primary-500 font-bold" value="{{ $user->email }}">
                    </div>
                </div>
                <div class="pt-4">
                    <button type="submit" class="bg-primary-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-primary-700 transition-all shadow-lg active:scale-95">{{ __('messages.save') }}</button>
                </div>
            </form>
        </div>

        <!-- Subscription Tab -->
        <div x-show="activeTab === 'billing'" x-cloak class="p-10 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="bg-primary-600 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-primary-200">
                <div class="absolute -end-20 -top-20 w-80 h-80 bg-primary-500 rounded-full blur-3xl opacity-50"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="space-y-2">
                        <span class="bg-white/20 text-white text-[10px] font-black uppercase tracking-[0.2em] px-4 py-1.5 rounded-full">{{ __('settings.plan') }}</span>
                        <h3 class="text-4xl font-heading font-black">{{ $plan->name ?? 'Standard Plan' }}</h3>
                        <p class="text-primary-100 font-medium">
                            {{ __('settings.renew') }} 
                            <span class="font-bold border-b border-primary-200 cursor-help">
                                {{ $tenant->subscription_ends_at ? $tenant->subscription_ends_at->format('M d, Y') : 'N/A' }}
                            </span>
                        </p>
                    </div>
                    <div class="text-end">
                        <p class="text-5xl font-heading font-black mb-2">
                            {{ __('messages.currency_symbol') }} {{ number_format($plan->price ?? 0, 2) }}<span class="text-xl opacity-60">/mo</span>
                        </p>
                        <button class="bg-white text-primary-600 px-8 py-3 rounded-xl font-bold hover:bg-gray-50 transition-colors shadow-lg" 
                                @click="addToast('Billing portal integration coming soon.', 'info')">
                            {{ __('settings.manage_billing') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="p-8 rounded-[2rem] border border-gray-100 bg-gray-50 space-y-4">
                    <h4 class="font-bold text-gray-900">{{ __('settings.features') }}</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ __('settings.unlimited_pos') }}
                        </li>
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ __('settings.realtime_control') }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Limits Tab -->
        <div x-show="activeTab === 'limits'" x-cloak class="p-10 space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="max-w-2xl space-y-8">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ __('settings.device_quotas') }}</h3>
                    <p class="text-sm text-gray-400">{{ __('settings.quotas_subtitle') }}</p>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-end mb-2">
                        <p class="text-sm font-bold text-gray-600">{{ __('settings.consoles_slots') }}</p>
                        <p class="text-sm font-black text-gray-900">{{ $deviceCount }} <span class="text-gray-400 font-medium">/ {{ $deviceLimit }}</span></p>
                    </div>
                    <div class="h-3 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-primary-600 transition-all duration-700" style="width: {{ $usagePercent }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400">{{ __('settings.quota_usage', ['percent' => round($usagePercent)]) }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
