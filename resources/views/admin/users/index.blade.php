<x-app-layout>
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Users</h1>
            <p class="text-sm text-gray-500">Kelola akun dan hak akses.</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
            class="rounded bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700">Tambah User</a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Role</th>
                    <th class="px-4 py-2 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $user->name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                        <td class="px-4 py-2 text-right space-x-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-sm text-blue-600">Detail</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-sm text-emerald-600">Edit</a>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Hapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-app-layout>
