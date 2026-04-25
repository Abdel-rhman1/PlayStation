<x-admin-layout>
    <div class="max-w-4xl mx-auto py-10 space-y-10">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.roles') }}" class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-gray-100 flex items-center justify-center text-gray-400 hover:text-red-600 transition-all">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-4xl font-heading font-black tracking-tighter text-gray-900">{{ __('admin.add_role') }}</h1>
                <p class="text-gray-400 font-medium">{{ __('admin.define_new_role') }}</p>
            </div>
        </div>

        <form action="{{ route('admin.roles.store') }}" method="POST" class="bg-white rounded-[3rem] p-10 shadow-sm ring-1 ring-black/5 space-y-8">
            @csrf
            
            <div class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">{{ __('admin.role_name') }}</label>
                        <input type="text" name="name" required class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-red-500 font-bold text-gray-900" placeholder="e.g. Branch Supervisor">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">{{ __('admin.tenant_context') }}</label>
                        <select name="tenant_id" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-red-500 font-bold text-gray-900 appearance-none">
                            <option value="">{{ __('admin.global_template') }}</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">{{ __('admin.short_description') }}</label>
                    <textarea name="description" rows="3" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-red-500 font-bold text-gray-900" placeholder="{{ __('admin.role_description_placeholder') }}"></textarea>
                </div>

                <div class="space-y-6">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">{{ __('admin.permissions') }}</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($permissions as $permission)
                        <label class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer group">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="w-5 h-5 rounded border-gray-300 text-red-600 focus:ring-red-500">
                            <span class="text-sm font-bold text-gray-700 group-hover:text-gray-900">{{ $permission->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-50 flex justify-end">
                <button type="submit" class="px-10 py-4 rounded-2xl bg-gray-900 font-bold text-white hover:bg-black transition-all shadow-lg shadow-gray-200">
                    {{ __('admin.add_role') }}
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
