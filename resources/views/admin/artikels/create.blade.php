<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Artikel Baru</h1>
    <form action="{{ route('admin.artikels.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-white shadow rounded p-6">
        @include('admin.artikels.form')
    </form>
</x-app-layout>
