@php
  $fmt = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
@endphp

<x-app-layout>
  <div class="py-8 space-y-6">

    <div class="rounded-2xl bg-white/90 border border-black/10 p-5">
      <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
          <h1 class="text-xl font-extrabold text-[#13392f]">Admin Keuangan • Saldo Awal</h1>
          <p class="text-sm text-[#13392f]/70">
            Isi sisa kas awal tahun. Setelah itu saldo otomatis dari transaksi.
          </p>
        </div>

        <a href="{{ route('admin.finance.transaksi.index') }}"
           class="rounded-xl bg-[#13392f] text-white px-4 py-2 text-sm font-bold border border-black/10 hover:brightness-105">
          ← Kembali ke Transaksi
        </a>
      </div>
    </div>

    @if(session('success'))
      <div class="rounded-2xl bg-emerald-100 text-emerald-900 border border-emerald-200 px-4 py-3 font-semibold">
        {{ session('success') }}
      </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

      <div class="lg:col-span-2 rounded-2xl bg-white/90 border border-black/10 p-5">
        <div class="font-extrabold text-[#13392f] mb-1">Input Sisa Kas Sebelumnya</div>
        <div class="text-sm text-[#13392f]/70 mb-4">
          Saldo awal (per 31 Des tahun sebelumnya) untuk tahun yang dipilih.
        </div>

        <form method="GET" action="{{ route('admin.finance.year-balance.index') }}" class="flex gap-2 flex-wrap mb-4">
          <input type="number" min="2000" max="2100" name="year" value="{{ $year }}"
                 class="w-28 rounded-xl bg-white text-[#13392f] border border-black/10 px-3 py-2 text-sm font-semibold">
          <button class="rounded-xl bg-[#E7B14B] text-[#13392f] px-4 py-2 text-sm font-bold border border-black/10 hover:brightness-105">
            Pilih Tahun
          </button>
        </form>

        <form method="POST" action="{{ route('admin.finance.year-balance.store') }}" class="space-y-4">
          @csrf
          <input type="hidden" name="year" value="{{ $year }}">

          <div>
            <label class="block text-sm font-bold text-[#13392f] mb-2">Saldo Awal Tahun {{ $year }}</label>
            <input type="number" min="0" step="1" name="opening_balance"
                   value="{{ old('opening_balance', $row->opening_balance) }}"
                   class="w-full rounded-xl bg-white text-[#13392f] border border-black/10 px-4 py-3 font-semibold">
            @error('opening_balance')
              <div class="mt-1 text-sm text-red-600 font-semibold">{{ $message }}</div>
            @enderror
          </div>

          <button class="rounded-xl bg-emerald-600 text-white px-5 py-3 text-sm font-extrabold border border-black/10 hover:brightness-105">
            Simpan Saldo Awal
          </button>
        </form>
      </div>

      <div class="rounded-2xl bg-[#13392f] text-white border border-white/10 p-5">
        <div class="text-sm text-white/70 font-semibold">Ringkasan</div>

        <div class="mt-3 space-y-2">
          <div class="flex items-center justify-between">
            <span class="text-white/80">Tahun</span>
            <span class="font-extrabold">{{ $year }}</span>
          </div>
          <div class="flex items-center justify-between">
            <span class="text-white/80">Saldo awal</span>
            <span class="font-extrabold text-emerald-300">{{ $fmt($row->opening_balance) }}</span>
          </div>
        </div>

        <div class="mt-4">
          <a href="{{ route('public.finance') }}" target="_blank" rel="noopener"
             class="inline-flex rounded-xl bg-white/90 text-[#13392f] px-4 py-2 text-sm font-bold border border-black/10 hover:bg-white">
            Lihat Halaman Publik →
          </a>
        </div>
      </div>

    </div>
  </div>
</x-app-layout>
