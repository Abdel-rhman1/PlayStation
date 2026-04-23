<footer class="bg-white border-t border-gray-100 py-6 mt-auto">
    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-sm text-gray-500">
            &copy; {{ date('Y') }} <span class="font-semibold text-gray-900">PlayStation Pro</span> SaaS. {{ __('messages.all_rights_reserved') }}
        </p>
        <div class="flex items-center gap-6">
            <a href="#" class="text-xs font-medium text-gray-400 hover:text-primary-600 transition-colors uppercase tracking-widest leading-none border-b-2 border-transparent hover:border-primary-200 pb-1">{{ __('messages.support') }}</a>
            <a href="#" class="text-xs font-medium text-gray-400 hover:text-primary-600 transition-colors uppercase tracking-widest leading-none border-b-2 border-transparent hover:border-primary-200 pb-1">{{ __('messages.privacy_policy') }}</a>
            <a href="#" class="text-xs font-medium text-gray-400 hover:text-primary-600 transition-colors uppercase tracking-widest leading-none border-b-2 border-transparent hover:border-primary-200 pb-1">{{ __('messages.documentation') }}</a>
        </div>
    </div>
</footer>
