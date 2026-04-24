@props(['value'])

<label {{ $attributes->merge(['class' => 'text-[10px] font-black text-white/40 uppercase tracking-[0.2em] ml-1 mb-2 block']) }}>
    {{ $value ?? $slot }}
</label>
