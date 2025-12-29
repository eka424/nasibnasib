<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Kegiatan</h1>
    <form action="{{ route('admin.kegiatans.update', $kegiatan) }}" method="POST" enctype="multipart/form-data"
        class="bg-white shadow rounded p-6">
        @include('admin.kegiatans.form', ['kegiatan' => $kegiatan])
    </form>
</x-app-layout>
