@csrf
@if(isset($user) && $user->exists)
    @method('PUT')
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nama</label>
        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
            class="mt-1 w-full rounded border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"
            class="mt-1 w-full rounded border-gray-300" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Role</label>
        <select name="role" class="mt-1 w-full rounded border-gray-300" required>
            @foreach ($roles as $role)
                <option value="{{ $role }}" @selected(old('role', $user->role ?? '') === $role)>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" class="mt-1 w-full rounded border-gray-300"
            @if(!isset($user) || !$user->exists) required @endif>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" class="mt-1 w-full rounded border-gray-300"
            @if(!isset($user) || !$user->exists) required @endif>
    </div>

    <div class="flex gap-2">
        <button type="submit"
            class="rounded bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700">Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="rounded bg-gray-200 px-4 py-2 text-gray-700">Batal</a>
    </div>
</div>
