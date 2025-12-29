<x-app-layout>
    <h1 class="text-2xl font-semibold mb-6">Kampanye Donasi Baru</h1>
    <form action="{{ route('admin.donasis.store') }}" method="POST" class="bg-white shadow rounded p-6">
        @include('admin.donasis.form')
    </form>
</x-app-layout>
