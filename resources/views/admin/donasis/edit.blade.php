<x-app-layout>
    <h1 class="text-2xl font-semibold mb-6">Edit Donasi</h1>
    <form action="{{ route('admin.donasis.update', $donasi) }}" method="POST" class="bg-white shadow rounded p-6">
        @include('admin.donasis.form', ['donasi' => $donasi])
    </form>
</x-app-layout>
