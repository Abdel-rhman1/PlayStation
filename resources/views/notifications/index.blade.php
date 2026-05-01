@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('notifications.title') }}</h2>
            <p class="text-gray-500 text-sm">{{ __('notifications.stay_updated') }}</p>
        </div>
        
        <div class="flex items-center gap-4">
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-white border border-gray-200 rounded-2xl text-sm font-bold text-gray-700 shadow-sm hover:bg-gray-50 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    {{ __('messages.mark_all_read') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-50">
            @forelse($notifications as $notification)
                <div class="p-8 flex items-start gap-6 transition-all hover:bg-gray-50/50 group {{ $notification->read_at ? 'opacity-60' : '' }}">
                    <!-- Icon Box -->
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-gray-100 {{ $notification->read_at ? 'bg-gray-50 text-gray-400' : 'bg-primary-50 text-primary-600' }}">
                        @php
                            $icon = $notification->data['icon'] ?? 'bell';
                            $type = $notification->data['type'] ?? 'info';
                        @endphp
                        @if($icon === 'play')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @elseif($icon === 'stop')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H10a1 1 0 01-1-1v-4z" /></svg>
                        @elseif($icon === 'shopping-cart')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        @elseif($icon === 'banknotes')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        @elseif($icon === 'user-plus')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                        @elseif($icon === 'plus')
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        @endif
                    </div>

                    <!-- Content -->
                    <div class="flex-1 space-y-2">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                            <h4 class="text-lg font-bold text-gray-900">{{ $notification->data['title'] ?? 'System Update' }}</h4>
                            <span class="text-xs font-medium text-gray-400 italic">{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-500 leading-relaxed max-w-3xl">
                            {{ $notification->data['message'] ?? '' }}
                        </p>
                        
                        <div class="pt-4 flex items-center gap-4">
                            @if($notification->data['action_url'] ?? false)
                                <a href="{{ $notification->data['action_url'] }}" class="inline-flex items-center gap-2 px-5 py-2 bg-gray-900 text-white rounded-xl text-xs font-bold hover:bg-black transition-all shadow-md">
                                    {{ __('messages.view') }}
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                </a>
                            @endif

                            @unless($notification->read_at)
                                <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-primary-600 hover:text-primary-700 transition-colors">
                                        {{ __('notifications.mark_read') }}
                                    </button>
                                </form>
                            @endunless
                        </div>
                    </div>

                    <!-- Status Dot -->
                    @unless($notification->read_at)
                        <div class="w-3 h-3 rounded-full bg-primary-600 animate-pulse mt-1.5 ring-4 ring-primary-50"></div>
                    @endunless
                </div>
            @empty
                <div class="p-20 text-center space-y-6">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mx-auto">
                        <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-xl font-bold text-gray-900">{{ __('notifications.all_caught_up') }}</h4>
                        <p class="text-sm text-gray-400">{{ __('notifications.empty_description') }}</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="bg-gray-50/50 p-8 border-t border-gray-50">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
