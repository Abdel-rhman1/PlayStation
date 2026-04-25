<x-app-layout>
    <div class="space-y-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-heading font-black tracking-tighter text-gray-900">{{ __('admin.manage_users') }}</h1>
                <p class="text-gray-400 font-medium mt-2">{{ __('admin.manage_users_desc') }}</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="px-6 py-4 rounded-2xl bg-primary-600 font-bold text-white hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                {{ __('admin.add_user') }}
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl font-bold text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-black/5 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-50 bg-gray-50/30">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.user_name') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.user_email') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.tenant') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.created_at') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary-600 flex items-center justify-center text-white font-black">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="text-sm font-bold text-gray-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 font-medium text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-8 py-6">
                            @if($user->tenant)
                                <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] font-black uppercase tracking-widest border border-black/5">
                                    {{ $user->tenant->name }}
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-primary-50 text-primary-600 text-[10px] font-black uppercase tracking-widest border border-primary-100">
                                    {{ __('admin.system_admin') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-sm text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-gray-400 hover:text-primary-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form x-data="{}" x-ref="deleteForm{{ $user->id }}" action="{{ route('admin.users.delete', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="askConfirm('Delete User?', 'Are you sure you want to remove {{ $user->name }}? Access to the dashboard will be revoked immediately.', () => $refs.deleteForm{{ $user->id }}.submit())" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-6 text-start">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
