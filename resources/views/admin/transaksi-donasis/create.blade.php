<x-app-layout>
    <h1 class="text-2xl font-semibold mb-6">Tambah Transaksi</h1>
    <form action="{{ route('admin.transaksi-donasis.store') }}" method="POST" class="bg-white shadow rounded p-6">
        @include('admin.transaksi-donasis.form', ['users' => $users, 'donasis' => $donasis])
    </form>
</x-app-layout>
