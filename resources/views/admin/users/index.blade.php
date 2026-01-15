@extends('layouts.app')

@section('content')
<div class="mx-auto w-full max-w-7xl">

  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-xl font-bold text-slate-900">Users</h1>
      <p class="mt-1 text-sm text-slate-500">Kelola data pengguna.</p>
    </div>

    @if (\Illuminate\Support\Facades\Route::has('admin.users.create'))
      <a href="{{ route('admin.users.create') }}"
         class="inline-flex items-center rounded-xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
        + Tambah User
      </a>
    @endif
  </div>

  <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-4">
      <div class="text-sm font-semibold text-slate-900">Daftar User</div>
      <div class="text-xs text-slate-500">Berikut data pengguna yang terdaftar.</div>
    </div>

    <div class="-mx-4 overflow-x-auto sm:mx-0">
      <div class="min-w-full px-4 sm:px-0">
        <table class="w-full min-w-[700px] text-sm">
          <thead class="bg-slate-50">
            <tr class="border-b border-slate-200 text-left">
              <th class="px-4 py-3 text-xs font-semibold text-slate-600">Nama</th>
              <th class="px-4 py-3 text-xs font-semibold text-slate-600">Email</th>
              <th class="px-4 py-3 text-xs font-semibold text-slate-600">Role</th>
              <th class="px-4 py-3 text-xs font-semibold text-slate-600 text-right">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @forelse(($users ?? []) as $u)
              <tr class="border-t border-slate-100">
                <td class="px-4 py-3 font-medium text-slate-900 whitespace-nowrap">
                  {{ $u->name ?? '-' }}
                </td>

                <td class="px-4 py-3 text-slate-600">
                  <div class="max-w-[340px] truncate">{{ $u->email ?? '-' }}</div>
                </td>

                <td class="px-4 py-3">
                  <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700">
                    {{ $u->role ?? '-' }}
                  </span>
                </td>

                <td class="px-4 py-3 text-right whitespace-nowrap">
                  @if (\Illuminate\Support\Facades\Route::has('admin.users.edit'))
                    <a href="{{ route('admin.users.edit', $u->id) }}"
                       class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                      Edit
                    </a>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-10 text-center text-slate-500">
                  Tidak ada data user.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    @if(isset($users) && method_exists($users, 'links'))
      <div class="border-t border-slate-200 px-5 py-4">
        {{ $users->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
