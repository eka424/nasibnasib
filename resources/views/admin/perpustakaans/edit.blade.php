<x-app-layout>
    <h1 class="text-2xl font-semibold mb-6">Edit Ebook</h1>
    <form action="{{ route('admin.perpustakaans.update', $perpustakaan) }}" method="POST"
        enctype="multipart/form-data" class="bg-white shadow rounded p-6">
        @include('admin.perpustakaans.form', ['perpustakaan' => $perpustakaan])
    </form>
</x-app-layout>
