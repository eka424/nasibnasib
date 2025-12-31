<x-front-layout>
  @php $bg='#13392f'; $accent='#E7B14B'; @endphp

  <div class="min-h-[70vh] text-white flex items-center justify-center px-4" style="background: {{ $bg }};">
    <div class="w-full max-w-lg rounded-[28px] border border-white/14 bg-white/10 p-6 text-center backdrop-blur">
      <h1 class="text-xl font-extrabold">Terima Kasih</h1>
      <p class="mt-2 text-sm text-white/75">
        Semoga Allah menerima sedekah ini dan menjadikannya pemberat kebaikan.
      </p>

      <div class="mt-5 rounded-2xl border border-white/10 bg-white/10 p-4 text-left">
        <div class="flex justify-between text-sm text-white/80">
          <span>Order ID</span><span class="font-bold text-white">{{ $trx->order_id }}</span>
        </div>
        <div class="mt-2 flex justify-between text-sm text-white/80">
          <span>Nominal</span><span class="font-extrabold text-white">Rp {{ number_format($trx->jumlah,0,',','.') }}</span>
        </div>
        <div class="mt-2 flex justify-between text-sm text-white/80">
          <span>Status Saat Ini</span>
          <span class="font-extrabold text-white">{{ strtoupper($trx->status) }}</span>
        </div>
      </div>

      <div class="mt-6 grid grid-cols-1 gap-2 sm:grid-cols-2">
        <a href="{{ route('sedekah.index') }}"
           class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-sm font-semibold hover:bg-white/15">
          Kembali
        </a>
        @auth
          <a href="{{ route('sedekah.riwayat') }}"
             class="rounded-2xl px-4 py-3 text-sm font-semibold text-[#13392f] hover:brightness-110"
             style="background: {{ $accent }};">
            Lihat Riwayat
          </a>
        @endauth
      </div>

      <p class="mt-3 text-xs text-white/65">
        Jika status belum berubah, biasanya notifikasi masuk beberapa saat. Refresh halaman ini untuk cek ulang.
      </p>
    </div>
  </div>
</x-front-layout>
