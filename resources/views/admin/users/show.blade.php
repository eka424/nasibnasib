<x-app-layout>
  <div class="bg-white shadow rounded p-6">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">{{ $user->name }}</h1>

    @php
      $rawPhone = (string) ($user->phone ?? '');
      $waPhone = preg_replace('/[^0-9]/', '', $rawPhone);
      if (strlen($waPhone) > 0 && str_starts_with($waPhone, '0')) {
        $waPhone = '62' . substr($waPhone, 1);
      }
    @endphp

    <dl class="space-y-3 text-sm">
      <div>
        <dt class="font-semibold text-gray-600">Email</dt>
        <dd>{{ $user->email }}</dd>
      </div>

      <div>
        <dt class="font-semibold text-gray-600">No. HP</dt>
        <dd class="flex items-center gap-2">
          @if(!empty($rawPhone))
            <span>{{ $rawPhone }}</span>

            @if(!empty($waPhone))
              <a
                href="https://wa.me/{{ $waPhone }}"
                target="_blank"
                class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-100"
                title="Chat WhatsApp"
              >
                WA
              </a>
            @endif
          @else
            <span class="text-gray-400">-</span>
          @endif
        </dd>
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
