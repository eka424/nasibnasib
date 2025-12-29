<x-app-layout>
    <article class="bg-white shadow rounded p-6 space-y-4">
        <header>
            <h1 class="text-3xl font-semibold text-gray-800">{{ $artikel->title }}</h1>
            <p class="text-sm text-gray-500">
                Oleh {{ $artikel->user->name }} • {{ $artikel->created_at->format('d M Y') }} •
                <span class="capitalize">{{ $artikel->status }}</span>
            </p>
        </header>

        @if($artikel->thumbnail)
            <img src="{{ Storage::url($artikel->thumbnail) }}" alt="{{ $artikel->title }}"
                class="w-full rounded" loading="lazy">
        @endif

        <div class="prose max-w-none">
            {!! nl2br(e($artikel->content)) !!}
        </div>
    </article>
</x-app-layout>
