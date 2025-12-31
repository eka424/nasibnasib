@extends('layouts.app')

@section('content')
<div class="-m-6">
  <div class="flex min-h-screen w-full flex-col bg-slate-100">
    <div class="flex flex-col sm:gap-4 sm:py-4">

      <header class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-white px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
        <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Ramah Anak</h1>
            <p class="text-gray-600">Kelola konten: Video, Dongeng PDF, dan Kata Islami</p>
          </div>

          <a href="{{ route('admin.kids.create') }}"
             class="inline-flex items-center gap-2 rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white shadow hover:bg-slate-800">
            + Tambah Konten
          </a>
        </div>
      </header>

      <main class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
          <div class="p-6 pb-2">
            <h2 class="text-base font-semibold text-slate-900">Filter</h2>
            <p class="text-sm text-slate-500">Cari konten dan filter berdasarkan tipe.</p>
          </div>

          <form method="GET" class="flex flex-col gap-3 p-6 pt-0 md:flex-row md:items-end">
            <div class="flex-1">
              <input type="search" name="q" value="{{ $q }}"
                     placeholder="Cari judul / deskripsi / url youtube / quote..."
                     class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-900/10">
            </div>

            <div class="md:w-[220px]">
              <select name="type"
                      class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-900/10">
                <option value="">Semua Tipe</option>
                <option value="video" @selected($type==='video')>Video</option>
                <option value="dongeng" @selected($type==='dongeng')>Dongeng (PDF)</option>
                <option value="kata" @selected($type==='kata')>Kata Islami</option>
              </select>
            </div>

            <div class="flex gap-2">
              <button class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Terapkan
              </button>
              <a href="{{ route('admin.kids.index') }}"
                 class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                Reset
              </a>
            </div>
          </form>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
          <div class="p-6 pb-2">
            <h2 class="text-base font-semibold text-slate-900">Daftar Konten</h2>
            <p class="text-sm text-slate-500">Total: {{ $items->total() }} konten</p>
          </div>

          <div class="overflow-x-auto p-4 pt-0">
            <table class="w-full text-sm">
              <thead class="[&_tr]:border-b">
                <tr class="border-b">
                  <th class="h-10 px-2 text-left font-medium text-slate-500">Judul</th>
                  <th class="h-10 px-2 text-left font-medium text-slate-500">Tipe</th>
                  <th class="h-10 px-2 text-left font-medium text-slate-500">Publish</th>
                  <th class="h-10 px-2 text-right font-medium text-slate-500">Aksi</th>
                </tr>
              </thead>
              <tbody class="[&_tr:last-child]:border-0">
                @forelse($items as $row)
                  <tr class="border-b">
                    <td class="p-2">
                      <div class="font-medium text-slate-900">{{ $row->title ?? '-' }}</div>
                      <div class="text-xs text-slate-500 line-clamp-1">{{ $row->description ?? '' }}</div>
                    </td>
                    <td class="p-2">
                      <span class="inline-flex rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">
                        {{ strtoupper($row->type ?? '-') }}
                      </span>
                    </td>
                    <td class="p-2">
                      <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $row->is_published ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-700' }}">
                        {{ $row->is_published ? 'Published' : 'Draft' }}
                      </span>
                    </td>
                    <td class="p-2 text-right">
                      <a href="{{ route('admin.kids.edit', $row) }}"
                         class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                        Edit
                      </a>

                      <form action="{{ route('admin.kids.destroy', $row) }}" method="POST" class="inline"
                            onsubmit="return confirm('Hapus konten ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="inline-flex items-center rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-slate-50">
                          Hapus
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="p-6 text-center text-slate-500">
                      Belum ada konten.
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>

          <div class="border-t border-slate-200 px-6 py-4">
            {{ $items->links() }}
          </div>
        </div>
      </main>
    </div>
  </div>
</div>
@endsection
