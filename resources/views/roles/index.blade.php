@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();

    // Group permissions by their module prefix
    $grouped = $permissions->groupBy(function($p) {
        return ucfirst(explode('.', $p->name)[0]);
    });

    $moduleIcons = [
        'Devices'   => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z',
        'Sessions'  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'Orders'    => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
        'Expenses'  => 'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2z',
        'Products'  => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'Pos'       => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
        'Reports'   => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'Settings'  => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543-.94-3.31.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M12 15a3 3 0 100-6 3 3 0 000 6z',
        'Users'     => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
    ];

    $roleColors = [
        'owner'    => ['bg' => 'bg-amber-500', 'light' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-200', 'shadow' => 'shadow-amber-100'],
        'employee' => ['bg' => 'bg-primary-500', 'light' => 'bg-primary-50', 'text' => 'text-primary-600', 'border' => 'border-primary-200', 'shadow' => 'shadow-primary-100'],
        'default'  => ['bg' => 'bg-gray-600', 'light' => 'bg-gray-50', 'text' => 'text-gray-600', 'border' => 'border-gray-200', 'shadow' => 'shadow-gray-100'],
    ];
@endphp

<div class="space-y-10 animate-in fade-in duration-700" x-data="{ roleModal: false }">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-4xl font-heading font-black text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-500 tracking-tight">{{ __('roles.matrix_title') }}</h2>
            <p class="text-gray-500 text-sm font-medium mt-1">{{ __('roles.subtitle') }}</p>
        </div>
        <button @click="roleModal = true"
                class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 rounded-2xl text-white text-sm font-black shadow-xl shadow-primary-200 hover:bg-primary-700 hover:scale-105 active:scale-95 transition-all">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            {{ __('roles.create_role') }}
        </button>
    </div>

    {{-- Roles Grid --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        @foreach($roles as $role)
        @php
            $c = $roleColors[$role->name] ?? $roleColors['default'];
            $userCount = \App\Models\User::where('role_id', $role->id)->count();
        @endphp
        <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl {{ $c['shadow'] }}/20 flex flex-col overflow-hidden group hover:border-{{ $c['text'] }}/30 transition-all duration-500 hover:-translate-y-1">

            {{-- Role Card Header --}}
            <div class="relative p-8 pb-6 border-b border-gray-50">
                <div class="absolute inset-0 bg-gradient-to-br from-gray-50/50 to-transparent pointer-events-none rounded-t-[2.5rem]"></div>
                <div class="relative flex items-start justify-between gap-4">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl {{ $c['light'] }} {{ $c['text'] }} flex items-center justify-center border {{ $c['border'] }} group-hover:scale-110 transition-transform duration-500 shadow-sm">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-2xl font-black text-gray-900 capitalize tracking-tight">{{ $role->name }}</h3>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $role->description ?? __('roles.description_hint') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2 shrink-0">
                        <span class="text-[10px] font-black {{ $c['text'] }} {{ $c['light'] }} px-3 py-1.5 rounded-full uppercase tracking-widest border {{ $c['border'] }}">
                            {{ $role->permissions->count() }} {{ __('roles.permissions_label') }}
                        </span>
                        <span class="text-[10px] font-bold text-gray-400 bg-gray-50 px-3 py-1.5 rounded-full border border-gray-100">
                            {{ $userCount }} {{ __('roles.users_label') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Permissions Form --}}
            <form action="{{ route('roles.update', $role) }}" method="POST" class="flex-1 flex flex-col p-8">
                @csrf
                @method('PUT')

                {{-- Full Branch Access Toggle --}}
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 mb-6">
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ __('roles.full_branch_access') }}</p>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-0.5">{{ __('roles.full_branch_desc') }}</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="has_full_branch_access" value="1"
                               {{ $role->has_full_branch_access ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600 shadow-inner"></div>
                    </label>
                </div>

                {{-- Permissions grouped by module --}}
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">{{ __('roles.capabilities') }}</p>
                <div class="flex-1 space-y-5">
                    @foreach($grouped as $module => $perms)
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $moduleIcons[$module] ?? 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' }}"/>
                                </svg>
                            </div>
                            <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.15em]">{{ $module }}</h4>
                            <div class="flex-1 h-px bg-gray-100"></div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 ps-2">
                            @foreach($perms as $permission)
                            @php $checked = $role->permissions->contains($permission->id); @endphp
                            <label class="flex items-center gap-3 p-3.5 rounded-2xl cursor-pointer transition-all duration-200 border
                                {{ $checked ? $c['light'].' '.$c['border'].' shadow-sm' : 'bg-gray-50/50 border-transparent hover:border-gray-200 hover:bg-white' }}">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       {{ $checked ? 'checked' : '' }}
                                       class="w-4 h-4 rounded border-gray-300 {{ $c['text'] }} focus:ring-primary-500 transition-all shrink-0">
                                <div class="min-w-0">
                                    <p class="text-xs font-bold {{ $checked ? $c['text'] : 'text-gray-600' }} truncate transition-colors">{{ $permission->label }}</p>
                                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-tighter">{{ $permission->name }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Save Button --}}
                <div class="mt-8 pt-6 border-t border-gray-50">
                    <button type="submit"
                            class="w-full py-4 {{ $c['bg'] }} rounded-2xl text-white text-xs font-black uppercase tracking-[0.15em] hover:opacity-90 hover:shadow-xl {{ $c['shadow'] }} transition-all duration-300 active:scale-[0.98]">
                        {{ __('roles.update_rights', ['name' => $role->name]) }}
                    </button>
                </div>
            </form>
        </div>
        @endforeach
    </div>

    {{-- Create Role Modal --}}
    <div x-show="roleModal"
         x-cloak
         class="fixed inset-0 z-[60] flex items-center justify-center px-4"
         aria-modal="true" role="dialog">

        {{-- Backdrop --}}
        <div x-show="roleModal"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             @click="roleModal = false"
             class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm"></div>

        {{-- Modal Panel --}}
        <div x-show="roleModal"
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden">

            {{-- Modal Top Decoration --}}
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-400 via-indigo-500 to-purple-500"></div>

            <div class="p-8 pt-10">
                {{-- Close --}}
                <button @click="roleModal = false"
                        class="absolute top-6 end-6 w-9 h-9 flex items-center justify-center rounded-xl text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>

                {{-- Icon & Title --}}
                <div class="flex items-center gap-5 mb-8">
                    <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center text-primary-600">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight">{{ __('roles.create_role') }}</h3>
                        <p class="text-sm text-gray-400 mt-0.5">{{ __('roles.subtitle') }}</p>
                    </div>
                </div>

                <form action="{{ route('roles.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ms-1">{{ __('roles.role_id') }}</label>
                        <input type="text" name="name" required
                               class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-bold text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all placeholder-gray-300"
                               placeholder="{{ __('roles.placeholder_name') }}">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ms-1">{{ __('roles.description') }}</label>
                        <textarea name="description" rows="3"
                                  class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-medium text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all placeholder-gray-300 resize-none"
                                  placeholder="{{ __('roles.description_hint') }}"></textarea>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-primary-50 rounded-2xl border border-primary-100">
                        <div>
                            <p class="text-sm font-bold text-primary-900">{{ __('roles.full_branch_access') }}</p>
                            <p class="text-xs text-primary-600 mt-0.5">{{ __('roles.full_branch_desc') }}</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="has_full_branch_access" value="1" class="sr-only peer">
                            <div class="w-12 h-6 bg-primary-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        </label>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="roleModal = false"
                                class="flex-1 py-4 bg-gray-50 rounded-2xl text-gray-500 text-xs font-black uppercase tracking-widest hover:bg-gray-100 border border-gray-100 transition-all">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit"
                                class="flex-[2] py-4 bg-primary-600 rounded-2xl text-white text-xs font-black uppercase tracking-widest shadow-lg shadow-primary-200 hover:bg-primary-700 transition-all active:scale-95">
                            {{ __('roles.create_role') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
