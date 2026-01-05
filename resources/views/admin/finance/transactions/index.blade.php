@php
  $fmt = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
@endphp

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css'])
  <title>Admin - Transaksi Keuangan</title>
</head>

<body class="bg-slate-100 text-slate-800">
  <div class="mx-auto max-w-6xl px-4 py-6 space-y-4">

    {{-- Header --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <div class="text-xl font-extrabold text-slate-900">Transaksi Keuangan</div>
          <div class="text-sm text-slate-500">Input pemasukan/pengeluaran + bukti • transparansi publik</div>
        </div>

        <div class="flex flex-wrap gap-2">
          <a href="{{ route('admin.finance.year-balance.index') }}"
             class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 text-white px-4 py-2 text-sm font-bold hover:bg-emerald-600">
            {{-- icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Input Sisa Kas Sebelumnya
          </a>

          <a href="{{ route('admin.finance.transaksi.create') }}"
             class="inline-flex items-center gap-2 rounded-xl bg-[#E7B14B] text-[#13392f] px-4 py-2 text-sm font-extrabold border border-black/10 hover:brightness-105">
            {{-- icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Transaksi
          </a>
        </div>
      </div>
    </div>

    {{-- Flash --}}
    @if(session('status'))
      <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900 font-semibold">
        {{ session('status') }}
      </div>
    @endif

    {{-- Filter --}}
    <form class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-3">
      <div>
        <label class="text-xs font-bold text-slate-600">Tanggal</label>
        <input type="date" name="date" value="{{ request('date') }}"
               class="mt-1 w-full rounded-xl border-slate-200 bg-white">
      </div>

      <div>
        <label class="text-xs font-bold text-slate-600">Tipe</label>
        <select name="type" class="mt-1 w-full rounded-xl border-slate-200 bg-white">
          <option value="">Semua</option>
          <option value="income" @selected(request('type')==='income')>Pemasukan</option>
          <option value="expense" @selected(request('type')==='expense')>Pengeluaran</option>
        </select>
      </div>

      <div>
        <label class="text-xs font-bold text-slate-600">Bidang</label>
        <select name="division" class="mt-1 w-full rounded-xl border-slate-200 bg-white">
          <option value="">Semua</option>
          <option value="idarah" @selected(request('division')==='idarah')>Idarah</option>
          <option value="imarah" @selected(request('division')==='imarah')>Imarah</option>
          <option value="riayah" @selected(request('division')==='riayah')>Riayah</option>
          <option value="lainnya" @selected(request('division')==='lainnya')>Lainnya</option>
        </select>
      </div>

      <div class="flex items-end gap-2">
        <button class="w-full rounded-xl bg-[#13392f] text-white px-4 py-2 font-bold hover:brightness-105">
          Filter
        </button>

        <a href="{{ route('admin.finance.transaksi.index') }}"
           class="rounded-xl bg-slate-100 text-slate-800 px-4 py-2 font-bold border border-slate-200 hover:bg-slate-200">
          Reset
        </a>
      </div>
    </form>

    {{-- Table --}}
    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-[#13392f] text-white text-left">
            <tr>
              <th class="p-3 whitespace-nowrap">Tanggal</th>
              <th class="p-3 whitespace-nowrap">Tipe</th>
              <th class="p-3 whitespace-nowrap">Bidang</th>
              <th class="p-3">Keterangan</th>
              <th class="p-3 text-right whitespace-nowrap">Nominal</th>
              <th class="p-3 whitespace-nowrap">Publik</th>
              <th class="p-3 whitespace-nowrap">Aksi</th>
            </tr>
          </thead>

          <tbody>
            @forelse($transactions as $t)
              <tr class="border-t border-slate-100 hover:bg-slate-50">
                <td class="p-3 whitespace-nowrap">
                  {{ \Illuminate\Support\Carbon::parse($t->date)->toDateString() }}
                </td>

                <td class="p-3 font-extrabold">
                  @if($t->type === 'income')
                    <span class="inline-flex rounded-full bg-blue-100 text-blue-800 px-2 py-0.5 text-[11px]">IN</span>
                  @else
                    <span class="inline-flex rounded-full bg-red-100 text-red-800 px-2 py-0.5 text-[11px]">OUT</span>
                  @endif
                </td>

                <td class="p-3 uppercase text-xs font-bold text-slate-700">
                  {{ $t->division ?? '-' }}
                </td>

                <td class="p-3">
                  <div class="font-semibold text-slate-900">{{ $t->title }}</div>

                  <div class="mt-1 flex flex-wrap items-center gap-2 text-xs">
                    @if($t->receipt_path)
                      <span class="inline-flex rounded-full bg-emerald-100 text-emerald-800 px-2 py-0.5 font-semibold">Ada bukti</span>
                    @else
                      <span class="inline-flex rounded-full bg-slate-100 text-slate-600 px-2 py-0.5 font-semibold">Tanpa bukti</span>
                    @endif

                    @if(!empty($t->subcategory))
                      <span class="inline-flex rounded-full bg-slate-100 text-slate-700 px-2 py-0.5 font-semibold">
                        {{ $t->subcategory }}
                      </span>
                    @endif
                  </div>
                </td>

                <td class="p-3 text-right whitespace-nowrap font-extrabold text-slate-900">
                  {{ $fmt($t->amount) }}
                </td>

                <td class="p-3">
                  @if($t->is_public)
                    <span class="inline-flex rounded-full bg-emerald-100 text-emerald-800 px-2 py-0.5 text-[11px] font-bold">Ya</span>
                  @else
                    <span class="inline-flex rounded-full bg-slate-100 text-slate-600 px-2 py-0.5 text-[11px] font-bold">Tidak</span>
                  @endif
                </td>

                <td class="p-3">
                  <div class="flex items-center gap-3">
                    <a class="text-emerald-700 font-bold hover:underline"
                       href="{{ route('admin.finance.transaksi.edit', $t) }}">
                      Edit
                    </a>

                    <form method="POST"
                          action="{{ route('admin.finance.transaksi.destroy', $t) }}"
                          onsubmit="return confirm('Hapus transaksi ini?')">
                      @csrf
                      @method('DELETE')
                      <button class="text-red-600 font-bold hover:underline">Hapus</button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="p-8 text-center text-slate-500">
                  Belum ada transaksi.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Pagination --}}
    <div>
      {{ $transactions->links() }}
    </div>

  </div>
</body>
</html>
