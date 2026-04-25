@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-in fade-in duration-700">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-heading font-bold text-gray-900">{{ __('users.title') }}</h2>
            <p class="text-gray-500 text-sm">{{ __('users.subtitle') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-xl text-sm font-semibold text-white shadow-md shadow-primary-100 hover:bg-primary-700 transition-all">
                <svg class="w-4 h-4 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                {{ __('users.add_user') }}
            </a>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('users.name') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('users.role') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">{{ __('users.joined') }}</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-end">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center text-primary-700 font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-black uppercase tracking-tighter">
                            {{ $user->role->name ?? 'No Role' }}
                        </span>
                    </td>
                    <td class="px-6 py-5 text-gray-500 text-sm">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-5 text-end">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="p-2 text-gray-400 hover:text-primary-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" 
                                  @submit.prevent="askConfirm('{{ __('messages.delete') }}', '{{ __('users.delete_confirm_title') }}', () => $el.submit())">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 bg-gray-50/50">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
