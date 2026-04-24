@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Page Header & Filters -->
    <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-2xl font-heading font-black text-gray-900 tracking-tight">{{ __('sessions.title') }}</h2>
                <p class="text-gray-500 text-sm">{{ __('sessions.subtitle') }}</p>
            </div>
            
            <form action="{{ url()->current() }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <input type="date" name="from" value="{{ request('from') }}" 
                           class="bg-gray-50 border-gray-100 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                    <span class="text-gray-400 text-xs font-bold uppercase tracking-widest">{{ __('sessions.to') }}</span>
                    <input type="date" name="to" value="{{ request('to') }}" 
                           class="bg-gray-50 border-gray-100 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500">
                </div>
                
                <button type="submit" class="bg-primary-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-md hover:bg-primary-700 transition-all w-full sm:w-auto">
                    {{ __('sessions.filter_logs') }}
                </button>
                @if(request()->hasAny(['from', 'to']))
                    <a href="{{ url()->current() }}" class="text-gray-400 hover:text-gray-600 text-xs font-bold uppercase">{{ __('sessions.clear') }}</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Sessions Table -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('devices.device_name') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('sessions.operator') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('sessions.duration') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">{{ __('sessions.cost') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($sessions as $session)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-primary-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 leading-none">{{ $session->device->name }}</p>
                                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-1">{{ __('sessions.id') }} {{ $session->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    <span class="text-xs font-bold text-gray-600">{{ $session->started_at->format('M d, H:i') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                    <span class="text-xs font-medium text-gray-400 italic">
                                        {{ $session->ended_at ? $session->ended_at->format('M d, H:i') : __('sessions.active') . '...' }}
                                    </span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] font-black uppercase tracking-widest border border-gray-200">
                                {{ $session->ended_at ? $session->duration . ' mins' : __('sessions.active') }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <span class="text-lg font-heading font-black text-gray-900">
                                ${{ number_format($session->total_price, 2) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="max-w-xs mx-auto space-y-4">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mx-auto">
                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-900">{{ __('sessions.no_sessions') }}</h4>
                                <p class="text-sm text-gray-400">{{ __('sessions.subtitle') }}</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($sessions->hasPages())
        <div class="bg-gray-50/50 px-8 py-6 border-t border-gray-100">
            {{ $sessions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
