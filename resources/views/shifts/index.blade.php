@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-in fade-in duration-700">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-heading font-black text-gray-900 tracking-tight">{{ __('shifts.title_index') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('shifts.subtitle_index') }}</p>
        </div>
        
        <div class="flex items-center gap-3">
            @if(Auth::user()->shifts()->active()->exists())
                <a href="{{ route('shifts.active') }}" class="px-5 py-2.5 rounded-xl bg-green-500 text-white text-xs font-black uppercase tracking-widest hover:bg-green-600 transition-all shadow-lg shadow-green-200 flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-white animate-pulse"></div>
                    {{ __('shifts.title_active') }}
                </a>
            @else
                <a href="{{ route('shifts.start') }}" class="px-5 py-2.5 rounded-xl bg-primary-600 text-white text-xs font-black uppercase tracking-widest hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    {{ __('shifts.btn_start') }}
                </a>
            @endif
        </div>
    </div>

    <!-- Shifts Table -->
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('shifts.col_shift_id') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('shifts.col_started_at') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('shifts.col_status') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest">{{ __('shifts.col_opening_balance') }}</th>
                        <th class="px-8 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-end">{{ __('shifts.col_actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($shifts as $shift)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        {{-- Shift ID --}}
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl {{ $shift->status === 'open' ? 'bg-green-100 text-green-600' : 'bg-gray-50 text-gray-400' }} flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="font-black text-gray-900 text-lg leading-none">#{{ $shift->id }}</span>
                            </div>
                        </td>
                        {{-- Start Time --}}
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-sm font-bold text-gray-800">{{ $shift->start_time->format('M d, Y') }}</span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $shift->start_time->format('h:i A') }}</span>
                            </div>
                        </td>
                        {{-- Status --}}
                        <td class="px-8 py-6">
                            @if($shift->status === 'open')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-widest border border-green-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    {{ __('shifts.status_open') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-[10px] font-black uppercase tracking-widest border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    {{ __('shifts.status_closed') }}
                                </span>
                            @endif
                        </td>
                        {{-- Opening Balance --}}
                        <td class="px-8 py-6">
                            <span class="text-sm font-bold text-gray-700">${{ number_format($shift->opening_balance, 2) }}</span>
                        </td>
                        {{-- Actions --}}
                        <td class="px-8 py-6 text-end">
                            <a href="{{ route('shifts.summary', $shift) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-gray-50 text-gray-500 hover:bg-primary-50 hover:text-primary-600 transition-all border border-gray-100 hover:border-primary-100 text-xs font-black uppercase tracking-widest">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ __('shifts.view_details') }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="max-w-xs mx-auto space-y-4">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-200 mx-auto">
                                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-900">{{ __('shifts.no_shifts') }}</h4>
                                <p class="text-sm text-gray-400">{{ __('shifts.subtitle_index') }}</p>
                                <a href="{{ route('shifts.start') }}" class="inline-flex items-center gap-2 mt-2 px-5 py-2.5 rounded-xl bg-primary-600 text-white text-xs font-black uppercase tracking-widest hover:bg-primary-700 transition-all shadow-lg shadow-primary-200">
                                    {{ __('shifts.btn_start') }}
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($shifts->hasPages())
        <div class="bg-gray-50/50 px-8 py-6 border-t border-gray-100">
            {{ $shifts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
