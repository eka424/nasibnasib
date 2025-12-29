<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('home') }}" class="text-lg font-semibold text-emerald-700">
                {{ config('app.name', 'Web Masjid') }}
            </a>
        </div>

        <div class="flex items-center gap-4">
            @auth
                <span class="text-sm text-gray-600">{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="text-sm text-white bg-emerald-600 hover:bg-emerald-700 px-3 py-1 rounded">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm text-emerald-700 font-semibold">Login</a>
                <a href="{{ route('register') }}"
                    class="text-sm text-white bg-emerald-600 hover:bg-emerald-700 px-3 py-1 rounded">Register</a>
            @endauth
        </div>
    </div>
</header>
