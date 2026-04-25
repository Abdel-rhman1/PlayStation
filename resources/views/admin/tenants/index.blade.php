<x-admin-layout>
    <div class="space-y-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-heading font-black tracking-tighter text-gray-900">{{ __('admin.manage_tenancies') }}</h1>
                <p class="text-gray-400 font-medium mt-2">{{ __('admin.manage_tenancies_desc') }}</p>
            </div>
            {{-- For simplicity, we link to a registration-like flow or just show a message for now --}}
            <a href="{{ route('admin.tenants.create') }}" class="px-6 py-4 rounded-2xl bg-primary-600 font-bold text-white hover:bg-primary-700 transition-all shadow-lg shadow-primary-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                {{ __('admin.create_tenant') }}
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-black/5 overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-50 bg-gray-50/30">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.tenant_details') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.plan_billing') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ __('admin.status') }}</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($tenants as $tenant)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center font-black text-gray-400 text-lg border border-black/5">
                                    {{ strtoupper(substr($tenant->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-900 leading-none">{{ $tenant->name }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase mt-1 tracking-widest">{{ $tenant->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="space-y-1">
                                <span class="px-3 py-1 rounded-full bg-primary-50 text-primary-600 text-[10px] font-black uppercase tracking-widest">
                                    {{ $tenant->plan->name ?? 'No Plan' }}
                                </span class="block">
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Exp: {{ $tenant->subscription_ends_at?->format('M d, Y') ?? 'N/A' }}</p>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <form action="{{ route('admin.tenants.toggle', $tenant) }}" method="POST">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 group">
                                    <div class="w-10 h-5 rounded-full relative transition-colors duration-300 {{ $tenant->is_active ? 'bg-green-500' : 'bg-gray-200' }}">
                                        <div class="absolute top-1 left-1 w-3 h-3 bg-white rounded-full transition-transform duration-300 {{ $tenant->is_active ? 'translate-x-5' : '' }}"></div>
                                    </div>
                                    <span class="text-xs font-bold {{ $tenant->is_active ? 'text-gray-900' : 'text-gray-400' }}">
                                        {{ $tenant->is_active ? __('admin.active_account') : __('admin.suspended_account') }}
                                    </span>
                                </button>
                            </form>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.tenants.edit', $tenant) }}" class="p-2 text-gray-400 hover:text-primary-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form x-data="{}" x-ref="deleteForm{{ $tenant->id }}" action="{{ route('admin.tenants.delete', $tenant) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" @click="askConfirm('Danger Zone', 'This will permanently delete {{ $tenant->name }} and all its data. Recovery is impossible.', () => $refs.deleteForm{{ $tenant->id }}.submit())" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center text-gray-400 font-bold uppercase tracking-widest">{{ __('admin.no_tenants') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6 text-start">
            {{ $tenants->links() }}
        </div>
    </div>
</x-admin-layout>
