<x-app-layout>
    <h1 class="text-2xl font-semibold mb-6">Media Galeri Baru</h1>
    <form action="{{ route('admin.galeris.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-white shadow rounded p-6">
        @include('admin.galeris.form')
    </form>
</x-app-layout>
