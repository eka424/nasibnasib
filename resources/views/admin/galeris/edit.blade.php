<x-app-layout>
    <h1 class="text-2xl font-semibold mb-6">Edit Media Galeri</h1>
    <form action="{{ route('admin.galeris.update', $galeri) }}" method="POST" enctype="multipart/form-data"
        class="bg-white shadow rounded p-6">
        @include('admin.galeris.form', ['galeri' => $galeri])
    </form>
</x-app-layout>
