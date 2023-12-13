<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <div class="relative p-4">
            <span class="block absolute -top-1 -left-1 z-0 h-[35px] text-8xl font-sans text-gray-300 dark:text-gray-600">"</span>
            <p class="relative z-1">You can log in however you want, as long as it's with GitHub</p>
        </div>
        <div class="px-4 text-right"> - Henry Ford (probably)</div>

        <div class="w-full flex flex-row justify-center items-center p-4 mt-4 border-t border-solid border-neutral-800 dark:border-neutral-200">
            <a href="{{ url('/auth/redirect') }}" class="flex flex-row justify-center items-center font-semibold text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white p-4 rounded border border-solid border-neutral-800 dark:border-neutral-200 hover:bg-neutral-100 dark:hover:bg-gray-600 focus:outline focus:outline-2 focus:rounded-sm focus:outline-green-500">
                <x-icon-github class="mr-4" />{{ __('Login with GitHub') }}
            </a>
        </div>
    </x-authentication-card>
</x-guest-layout>
