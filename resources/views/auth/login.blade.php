@section('title', 'Login')
<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Username --}}
        <div>
            <x-input-label for="username" value="Username" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        {{-- Password --}}
<div class="mt-4 relative">
    <x-input-label for="password" value="Password" />

    <div class="relative">
        <x-text-input
            id="password"
            class="block mt-1 w-full pr-10"
            type="password"
            name="password"
            required
            autocomplete="current-password"
        />

        {{-- Eye Icon --}}
        <button
            type="button"
            id="togglePassword"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 focus:outline-none"
        >
            {{-- Eye (hidden) --}}
            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                      -1.274 4.057-5.064 7-9.542 7
                      -4.477 0-8.268-2.943-9.542-7z" />
            </svg>

            {{-- Eye Off (default) --}}
            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M13.875 18.825A10.05 10.05 0 0112 19
                      c-4.478 0-8.268-2.943-9.542-7
                      a9.956 9.956 0 012.042-3.368" />
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6.223 6.223A9.956 9.956 0 0112 5
                      c4.478 0 8.268 2.943 9.542 7
                      a9.978 9.978 0 01-4.132 5.411" />
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3 3l18 18" />
            </svg>
        </button>
    </div>

    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<div class="flex flex-col gap-3 mt-6 sm:flex-row sm:items-center sm:justify-between">
    
    {{-- Hubungi Admin --}}
    <a
        href="https://wa.me/6281337669467?text=Halo%20Admin,%20saya%20mengalami%20kendala%20saat%20login."
        target="_blank"
        class="inline-flex items-center justify-center gap-2 rounded-md border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400"
    >
        {{-- WhatsApp Icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 24 24" fill="currentColor">
            <path
                d="M20.52 3.48A11.82 11.82 0 0 0 12.04 0C5.43 0 .05 5.38.05 12a11.9 11.9 0 0 0 1.65 6L0 24l6.17-1.62a11.93 11.93 0 0 0 5.87 1.5h.01c6.62 0 12-5.38 12-12a11.86 11.86 0 0 0-3.53-8.4ZM12.05 21.9a9.87 9.87 0 0 1-5.03-1.38l-.36-.21-3.66.96.98-3.56-.23-.37A9.86 9.86 0 1 1 12.05 21.9Zm5.42-7.37c-.3-.15-1.77-.87-2.05-.97-.28-.1-.48-.15-.68.15-.2.3-.78.97-.96 1.17-.18.2-.35.22-.65.07-.3-.15-1.25-.46-2.38-1.47-.88-.79-1.47-1.76-1.64-2.06-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.68-1.64-.93-2.24-.24-.58-.49-.5-.68-.51h-.58c-.2 0-.52.07-.8.37-.28.3-1.05 1.03-1.05 2.5s1.08 2.9 1.23 3.1c.15.2 2.12 3.24 5.14 4.55.72.31 1.28.5 1.72.64.72.23 1.38.2 1.9.12.58-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35Z"
            />
        </svg>

        Hubungi Admin
    </a>

    {{-- Login --}}
    <x-primary-button class="w-full sm:w-auto">
        Log in
    </x-primary-button>

</div>

    </form>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        toggle.addEventListener('click', () => {
            const isPassword = password.type === 'password';
            password.type = isPassword ? 'text' : 'password';

            eyeOpen.classList.toggle('hidden', !isPassword);
            eyeClosed.classList.toggle('hidden', isPassword);
        });
    });
</script>

</x-guest-layout>
