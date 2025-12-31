<x-front-layout>
  <section class="max-w-6xl mx-auto px-4 py-12 space-y-6">
    <div class="text-center space-y-2">
      <p class="text-sm uppercase tracking-[0.3rem] text-emerald-600 font-semibold">Riwayat Sedekah</p>
      <h1 class="text-3xl font-semibold text-slate-900">Kontribusi Terbaik Anda</h1>
      <p class="text-slate-500">Catatan sedekah akan ter-update otomatis setelah pembayaran terverifikasi.</p>
    </div>

    {{-- Desktop table --}}
    <div class="hidden md:block bg-white rounded-3xl shadow overflow-hidden border border-slate-100">
      <table class="w-full text-sm">
        <thead class="bg-slate-50">
          <tr class="text-left">
            <th class="px-5 py-3 font-semibold text-slate-600">Program</th>
            <th class="px-5 py-3 font-semibold text-slate-600">Jumlah</th>
            <th class="px-5 py-3 font-semibold text-slate-600">Status</th>
            <th class="px-5 py-3 font-semibold text-slate-600">Tanggal</th>
            <th class="px-5 py-3 font-semibold text-slate-600">Order ID</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($transaksis as $t)
            @php
              $badge = match($t->status) {
                'success' => 'bg-emerald-100 text-emerald-700',
                'pending' => 'bg-amber-100 text-amber-700',
                'challenge' => 'bg-indigo-100 text-indigo-700',
                'expired' => 'bg-slate-100 text-slate-700',
                'cancel','failed' => 'bg-rose-100 text-rose-700',
                default => 'bg-slate-100 text-slate-700'
              };
            @endphp
            <tr class="border-t border-slate-100">
              <td class="px-5 py-3">
                <p class="font-semibold text-slate-800">{{ $t->campaign?->judul ?? 'Sedekah Umum Masjid' }}</p>
                <p class="text-xs text-slate-500">{{ $t->nama ?: 'Hamba Allah' }}</p>
              </td>
              <td class="px-5 py-3 font-semibold text-slate-900">Rp {{ number_format($t->jumlah,0,',','.') }}</td>
              <td class="px-5 py-3">
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
                  {{ strtoupper($t->status) }}
                </span>
              </td>
              <td class="px-5 py-3 text-slate-700">{{ $t->created_at->translatedFormat('d M Y') }}</td>
              <td class="px-5 py-3 text-slate-600 font-mono text-xs">{{ $t->order_id }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-10 text-center text-slate-500">
                Belum ada riwayat sedekah.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Mobile cards --}}
    <div class="md:hidden space-y-3">
      @forelse ($transaksis as $t)
        @php
          $badge = match($t->status) {
            'success' => 'bg-emerald-100 text-emerald-700',
            'pending' => 'bg-amber-100 text-amber-700',
            'challenge' => 'bg-indigo-100 text-indigo-700',
            'expired' => 'bg-slate-100 text-slate-700',
            'cancel','failed' => 'bg-rose-100 text-rose-700',
            default => 'bg-slate-100 text-slate-700'
          };
        @endphp
        <div class="bg-white rounded-3xl shadow border border-slate-100 p-5">
          <div class="flex items-start justify-between gap-3">
            <div class="min-w-0">
              <p class="font-semibold text-slate-900 truncate">{{ $t->campaign?->judul ?? 'Sedekah Umum Masjid' }}</p>
              <p class="text-xs text-slate-500">{{ $t->created_at->translatedFormat('d M Y') }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">
              {{ strtoupper($t->status) }}
            </span>
          </div>

          <div class="mt-4 flex items-center justify-between">
            <span class="text-sm text-slate-600">Jumlah</span>
            <span class="text-sm font-extrabold text-slate-900">Rp {{ number_format($t->jumlah,0,',','.') }}</span>
          </div>

          <div class="mt-2 text-xs text-slate-500 font-mono break-all">
            {{ $t->order_id }}
          </div>
        </div>
      @empty
        <div class="bg-white rounded-3xl shadow border border-slate-100 p-6 text-center text-slate-500">
          Belum ada riwayat sedekah.
        </div>
      @endforelse
    </div>

    <div>
      {{ $transaksis->links() }}
    </div>
  </section>
</x-front-layout>
