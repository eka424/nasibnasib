<x-app-layout>
    <div class="bg-white shadow rounded p-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">{{ $user->name }}</h1>
        <dl class="space-y-2 text-sm">
            <div>
                <dt class="font-semibold text-gray-600">Email</dt>
                <dd>{{ $user->email }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-600">Role</dt>
                <dd class="capitalize">{{ $user->role }}</dd>
            </div>
            <div>
                <dt class="font-semibold text-gray-600">Dibuat</dt>
                <dd>{{ $user->created_at->format('d M Y') }}</dd>
            </div>
        </dl>
        <div class="mt-6 space-x-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="text-emerald-600">Edit</a>
            <a href="{{ route('admin.users.index') }}" class="text-gray-600">Kembali</a>
        </div>
    </div>
</x-app-layout>
