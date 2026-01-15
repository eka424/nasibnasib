<header class="bg-white border-b border-slate-200">
    <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <a href="{{ route('home') }}"
                   class="truncate text-base font-semibold text-emerald-700 hover:text-emerald-800">
                    {{ config('app.name', 'Web Masjid') }}
                </a>
                <div class="mt-0.5 text-xs text-slate-500">
                    Admin area
                </div>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <div class="hidden sm:block text-sm text-slate-600">
                        <span class="font-semibold text-slate-900">{{ auth()->user()->name }}</span>
                        <span class="text-slate-500">({{ auth()->user()->role }})</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center rounded-xl bg-emerald-700 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center rounded-xl bg-emerald-700 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>
