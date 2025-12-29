<x-app-layout>
    <h1 class="text-2xl font-semibold mb-6">Ubah Status Transaksi</h1>
    <form action="{{ route('admin.transaksi-donasis.update', $transaksi) }}" method="POST"
        class="bg-white shadow rounded p-6">
        @include('admin.transaksi-donasis.form', ['transaksi' => $transaksi])
    </form>
</x-app-layout>
