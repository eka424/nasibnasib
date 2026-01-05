<x-app-layout>
  <h1 class="text-2xl font-semibold text-gray-800 mb-6">Tambah Kegiatan</h1>

  <form action="{{ route('admin.kegiatans.store') }}" method="POST"
        class="bg-white shadow rounded p-6">
    @include('admin.kegiatans.form')
  </form>
</x-app-layout>
