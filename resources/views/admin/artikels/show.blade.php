<x-app-layout>
    @php
        $thumb = $artikel->thumbnail ?? null;

        $thumbUrl = null;
        if ($thumb) {
            if (preg_match('~^https?://~i', $thumb)) {
                $thumbUrl = $thumb;
            } else {
                $path = ltrim($thumb, '/');
                if (str_starts_with($path, 'storage/')) {
                    $path = substr($path, strlen('storage/'));
                }
                $thumbUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
            }
        }
    @endphp

    <article class="bg-white shadow rounded p-6 space-y-4">
        <header>
            <h1 class="text-3xl font-semibold text-gray-800">{{ $artikel->title }}</h1>
            <p class="text-sm text-gray-500">
                Oleh {{ $artikel->user->name }} • {{ $artikel->created_at->format('d M Y') }} •
                <span class="capitalize">{{ $artikel->status }}</span>
            </p>
        </header>

        @if($thumbUrl)
            <img src="{{ $thumbUrl }}" alt="{{ $artikel->title }}"
                class="w-full rounded" loading="lazy"
                onerror="this.style.display='none'">
        @endif

        <div class="prose max-w-none">
            {!! nl2br(e($artikel->content)) !!}
        </div>
    </article>
</x-app-layout>
