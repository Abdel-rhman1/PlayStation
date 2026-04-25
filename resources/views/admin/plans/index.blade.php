<x-app-layout>
    <div class="space-y-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-heading font-black tracking-tighter text-gray-900">{{ __('admin.pricing_plans') }}</h1>
                <p class="text-gray-400 font-medium mt-2">{{ __('admin.pricing_plans_desc') }}</p>
            </div>
            <a href="{{ route('admin.plans.create') }}" class="px-6 py-4 rounded-2xl bg-gray-900 font-bold text-white hover:bg-black transition-all shadow-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                {{ __('admin.add_plan') }}
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

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($plans as $plan)
            <div class="bg-white rounded-[2.5rem] p-10 shadow-sm ring-1 ring-black/5 hover:shadow-2xl hover:-translate-y-2 transition-all relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8 flex gap-2">
                    <a href="{{ route('admin.plans.edit', $plan) }}" class="text-gray-300 hover:text-primary-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </a>
                    <form x-data="{}" x-ref="deleteForm{{ $plan->id }}" action="{{ route('admin.plans.delete', $plan) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" @click="askConfirm('Remove Plan?', 'Deleting {{ $plan->name }} will prevent new shops from subscribing to it. Active subscribers will remain unaffected.', () => $refs.deleteForm{{ $plan->id }}.submit())" class="text-gray-200 hover:text-red-500 transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </form>
                </div>
                
                <div class="w-14 h-14 bg-primary-50 rounded-2xl flex items-center justify-center text-primary-600 mb-8 group-hover:bg-primary-600 group-hover:text-white transition-all duration-500">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>

                <h3 class="text-2xl font-black text-gray-900 mb-2">{{ $plan->name }}</h3>
                <p class="text-gray-400 text-sm font-medium mb-8 leading-relaxed">{{ $plan->description ?? 'Configured for maximum utility and growth for gaming lounge owners.' }}</p>
                
                <div class="space-y-4 mb-10">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('admin.monthly_price') }}</span>
                        <span class="text-lg font-black text-gray-900">${{ number_format($plan->price, 2) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-widest">{{ __('admin.device_limit') }}</span>
                        <span class="text-lg font-black text-gray-900">{{ $plan->device_limit }} {{ __('admin.units') }}</span>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-50 flex items-center justify-between">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ __('admin.last_updated', ['time' => '2d ago']) }}</p>
                    <div class="flex gap-2">
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                        <span class="text-[10px] font-black text-gray-900 uppercase tracking-widest">{{ __('admin.plan_active') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
