<x-app-layout>
    <div class="bg-white shadow rounded p-6 space-y-4">
        <div>
            <h1 class="text-2xl font-semibold">Jawab Pertanyaan</h1>
            <p class="text-sm text-gray-500">Dari {{ $pertanyaan->penanya->name }}</p>
        </div>
        <p>{{ $pertanyaan->pertanyaan }}</p>
        <form action="{{ route('ustadz.pertanyaan.update', $pertanyaan) }}" method="POST" class="space-y-3">
            @csrf
            @method('PUT')
            <textarea name="jawaban" rows="6" class="w-full rounded border-gray-300" required>{{ old('jawaban', $pertanyaan->jawaban) }}</textarea>
            <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">Simpan Jawaban</button>
        </form>
    </div>
</x-app-layout>
