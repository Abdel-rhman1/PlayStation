@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-in fade-in duration-700" x-data="{ roleModal: false }">
    <!-- Header with Create Button -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-heading font-bold text-gray-900">{{ __('roles.matrix_title') }}</h2>
            <p class="text-gray-500 text-sm">{{ __('roles.subtitle') }}</p>
        </div>
        <div>
            <button @click="roleModal = true" class="inline-flex items-center px-5 py-3 bg-primary-600 rounded-2xl text-white text-sm font-bold shadow-lg shadow-primary-200 hover:bg-primary-700 transition-all">
                <svg class="w-5 h-5 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                {{ __('roles.create_role') }}
            </button>
        </div>
    </div>

    <!-- Active Roles Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        @foreach($roles as $role)
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 flex flex-col group hover:border-primary-200 transition-all duration-300">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-primary-50 flex items-center justify-center text-primary-600 border border-primary-100 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 capitalize tracking-tight">{{ $role->name }}</h3>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ $role->description ?? __('roles.description_hint') }}</p>
                    </div>
                </div>
                <div class="flex flex-col items-end">
                    <span class="text-[10px] font-black text-primary-600 bg-primary-50 px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ __('roles.permissions_count', ['count' => $role->permissions->count()]) }}
                    </span>
                </div>
            </div>

            <form action="{{ route('roles.update', $role) }}" method="POST" class="flex-1 flex flex-col">
                @csrf
                @method('PUT')

                <div class="flex-1 space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ __('roles.capabilities') }}</label>
                        <label class="flex items-center cursor-pointer">
                            <span class="me-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Full Branch Access</span>
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" name="has_full_branch_access" value="1" {{ $role->has_full_branch_access ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </div>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($permissions as $permission)
                        <label class="flex items-center p-4 bg-gray-50/50 rounded-2xl cursor-pointer hover:bg-white border border-transparent hover:border-primary-100 hover:shadow-sm transition-all group/item">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                       {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}
                                       class="w-5 h-5 rounded-lg border-gray-200 text-primary-600 focus:ring-primary-500 checked:bg-primary-600 transition-all">
                            </div>
                            <div class="ms-3 min-w-0">
                                <p class="text-sm font-bold text-gray-700 group-hover/item:text-primary-700 transition-colors truncate">{{ $permission->label }}</p>
                                <p class="text-[9px] text-gray-400 font-black uppercase tracking-tighter">{{ $permission->name }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-50">
                    <button type="submit" class="w-full py-4 bg-gray-900 rounded-2xl text-white text-xs font-black uppercase tracking-[0.2em] hover:bg-primary-600 hover:shadow-lg hover:shadow-primary-100 transition-all duration-300">
                        {{ __('roles.update_rights', ['name' => $role->name]) }}
                    </button>
                </div>
            </form>
        </div>
        @endforeach
    </div>

    <!-- Create Role Modal -->
    <div x-show="roleModal" 
         x-cloak
         class="fixed inset-0 z-[60] overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="roleModal" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="roleModal = false"
                 class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="roleModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-8">
                
                <div class="absolute top-0 right-0 pt-6 pr-6">
                    <button @click="roleModal = false" class="text-gray-400 hover:text-gray-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div>
                    <div class="w-14 h-14 bg-primary-100 rounded-2xl flex items-center justify-center text-primary-600 mb-6">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight mb-2">{{ __('roles.create_role') }}</h3>
                    <p class="text-sm text-gray-500 mb-8">{{ __('roles.subtitle') }}</p>
                </div>

                <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ms-1">{{ __('roles.role_id') }}</label>
                        <input type="text" name="name" required 
                               class="w-full bg-gray-50 border-0 rounded-2xl px-6 py-4 text-gray-900 focus:ring-2 focus:ring-primary-500 transition-all font-bold placeholder-gray-400"
                               placeholder="{{ __('roles.placeholder_name') }}">
                    </div>

                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ms-1">{{ __('roles.description') }}</label>
                        <textarea name="description" rows="3"
                                  class="w-full bg-gray-50 border-0 rounded-2xl px-6 py-4 text-gray-900 focus:ring-2 focus:ring-primary-500 transition-all font-medium placeholder-gray-400"
                                  placeholder="{{ __('roles.description_hint') }}"></textarea>
                    </div>

                    <div class="p-4 bg-primary-50 rounded-2xl flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-primary-900">Grant Full Branch Access</p>
                            <p class="text-xs text-primary-600">Allows access to all branch locations</p>
                        </div>
                        <input type="checkbox" name="has_full_branch_access" value="1" class="w-6 h-6 rounded-lg border-primary-200 text-primary-600 focus:ring-primary-500 transition-all">
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="roleModal = false" class="flex-1 py-4 bg-gray-50 rounded-2xl text-gray-500 text-xs font-black uppercase tracking-widest hover:bg-gray-100 transition-all">
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="submit" class="flex-2 px-8 py-4 bg-primary-600 rounded-2xl text-white text-xs font-black uppercase tracking-widest shadow-lg shadow-primary-100 hover:bg-primary-700 transition-all">
                            {{ __('roles.create_role') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
