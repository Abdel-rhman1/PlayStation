@props(['name' => 'player_count'])

<div class="space-y-2">
    <label for="{{ $name }}" class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">
        {{ __('sessions.player_mode') ?? 'Player Mode' }}
    </label>
    <div class="relative">
        <select name="{{ $name }}" id="{{ $name }}" 
                class="w-full bg-gray-50 border-transparent rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-primary-500 transition-all font-bold text-gray-900 appearance-none shadow-inner text-sm">
            <option value="">{{ __('sessions.default_mode') ?? 'Default Mode' }}</option>
            <option value="2">{{ __('sessions.single_mode') ?? '2 Players (Single)' }}</option>
            <option value="4">{{ __('sessions.multi_mode') ?? '4 Players (Multi)' }}</option>
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </div>
    </div>
</div>
