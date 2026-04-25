<x-admin-layout>
    <div class="max-w-4xl mx-auto py-10 space-y-10">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.plans') }}" class="w-12 h-12 rounded-2xl bg-white shadow-sm border border-gray-100 flex items-center justify-center text-gray-400 hover:text-primary-600 transition-all">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-4xl font-heading font-black tracking-tighter text-gray-900">{{ __('admin.edit_plan') }}</h1>
                <p class="text-gray-400 font-medium">Update parameters for {{ $plan->name }}.</p>
            </div>
        </div>

        <form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="bg-white rounded-[3rem] p-10 shadow-sm ring-1 ring-black/5 space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Plan Name</label>
                    <input type="text" name="name" value="{{ $plan->name }}" required class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-primary-500 font-bold text-gray-900">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">{{ __('admin.monthly_price') }}</label>
                    <input type="number" step="0.01" name="price" value="{{ $plan->price }}" required class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-primary-500 font-bold text-gray-900">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">{{ __('admin.device_limit') }}</label>
                    <input type="number" name="device_limit" value="{{ $plan->device_limit }}" required class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-primary-500 font-bold text-gray-900">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">{{ __('admin.billing_cycle') }}</label>
                    <input type="number" name="billing_cycle_days" value="{{ $plan->billing_cycle_days }}" required class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-primary-500 font-bold text-gray-900">
                </div>

                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-1">Description</label>
                    <textarea name="description" rows="4" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none focus:ring-2 focus:ring-primary-500 font-bold text-gray-900">{{ $plan->description }}</textarea>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-50 flex justify-end">
                <button type="submit" class="px-10 py-4 rounded-2xl bg-gray-900 font-bold text-white hover:bg-black transition-all shadow-lg shadow-gray-200">
                    {{ __('admin.update_plan') }}
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
