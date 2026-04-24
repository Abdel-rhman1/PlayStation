<button {{ $attributes->merge(['type' => 'submit', 'class' => 'w-full relative group inline-flex items-center justify-center']) }}>
    <div class="absolute -inset-1 bg-gradient-to-r from-primary-600 to-indigo-600 rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
    <div class="relative w-full flex items-center justify-center px-8 py-4 bg-primary-600 rounded-2xl leading-none transition duration-200 group-hover:bg-primary-500 text-sm font-black text-white uppercase tracking-[0.2em]">
        {{ $slot }}
    </div>
</button>
