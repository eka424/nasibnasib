<x-app-layout>
  <h1 class="text-2xl font-semibold mb-6">Media Galeri Baru</h1>

  @if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
      <div class="font-semibold mb-1">Gagal menyimpan:</div>
      <ul class="list-disc pl-5 text-sm">
        @foreach ($errors->all() as $e)
          <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.galeris.store') }}" method="POST" class="bg-white shadow rounded p-6">
    @csrf
    @include('admin.galeris.form')
  </form>
</x-app-layout>
