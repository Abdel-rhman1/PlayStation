@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block w-full px-4 py-4 bg-white/5 border border-white/10 rounded-2xl text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:border-primary-500 transition-all duration-300 shadow-inner']) }}>
