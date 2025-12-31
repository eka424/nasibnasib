<x-front-layout>
  @php
    // samain tema dengan halaman donasi
    $bg = '#13392f';
    $accent = '#E7B14B';
  @endphp

  <style>
    :root{ --bg: {{ $bg }}; --accent: {{ $accent }}; }

    .glass{
      border: 1px solid rgba(255,255,255,.14);
      background: rgba(255,255,255,.08);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      box-shadow: 0 18px 60px -45px rgba(0,0,0,.55);
    }
  </style>

  <div class="min-h-screen text-white" style="background: var(--bg);">
    <section class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8 space-y-6">

      {{-- HEADER --}}
      <div class="text-center space-y-2">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/6 px-4 py-2 text-xs font-semibold text-white/90">
          <span class="inline-block h-2 w-2 rounded-full" style="background: var(--accent)"></span>
          RIWAYAT DONASI
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white">Kontribusi Terbaik Anda</h1>
        <p class="text-sm text-white/70">
          Catatan donasi akan muncul otomatis setelah transaksi tercatat.
        </p>
      </div>

      {{-- DESKTOP TABLE --}}
      <div class="glass overflow-hidden rounded-[28px] hidden md:block">
        <div class="border-b border-white/10 bg-white/6 px-6 py-4 flex items-center justify-between">
          <p class="text-sm font-extrabold text-white">Daftar Transaksi</p>
          <p class="text-xs text-white/60">Total: {{ $transaksis->total() }}</p>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="bg-white/6">
              <tr class="text-left">
                <th class="px-6 py-4 font-semibold text-white/70">Program</th>
                <th class="px-6 py-4 font-semibold text-white/70">Jumlah</th>
                <th class="px-6 py-4 font-semibold text-white/70">Status</th>
                <th class="px-6 py-4 font-semibold text-white/70">Tanggal</th>
              </tr>
            </thead>

            <tbody class="divide-y divide-white/10">
              @forelse ($transaksis as $transaksi)
                @php
                  $judul = optional($transaksi->donasi)->judul ?? 'Donasi Umum';

                  $status = strtolower($transaksi->status_pembayaran ?? '');
                  $isOk = $status === 'berhasil' || $status === 'paid' || $status === 'success';
                  $isFail = $status === 'gagal' || $status === 'failed' || $status === 'expire' || $status === 'expired';

                  $badgeClass = $isOk
                    ? 'bg-emerald-500/15 text-emerald-100 border-emerald-400/20'
                    : ($isFail
                      ? 'bg-red-500/15 text-red-100 border-red-400/20'
                      : 'bg-yellow-500/15 text-yellow-100 border-yellow-400/20');

                  $tanggal = optional($transaksi->created_at)->translatedFormat('d M Y') ?? '-';
                @endphp

                <tr class="hover:bg-white/5 transition">
                  <td class="px-6 py-4">
                    <p class="font-semibold text-white">{{ $judul }}</p>
                    <p class="text-xs text-white/55">ID: {{ $transaksi->id }}</p>
                  </td>

                  <td class="px-6 py-4 font-extrabold text-white">
                    Rp {{ number_format((int) $transaksi->jumlah, 0, ',', '.') }}
                  </td>

                  <td class="px-6 py-4">
                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                      {{ $transaksi->status_pembayaran }}
                    </span>
                  </td>

                  <td class="px-6 py-4 text-white/85">
                    {{ $tanggal }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="px-6 py-10 text-center text-white/65">
                    Belum ada riwayat donasi.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- MOBILE CARDS --}}
      <div class="space-y-3 md:hidden">
        @forelse ($transaksis as $transaksi)
          @php
            $judul = optional($transaksi->donasi)->judul ?? 'Donasi Umum';

            $status = strtolower($transaksi->status_pembayaran ?? '');
            $isOk = $status === 'berhasil' || $status === 'paid' || $status === 'success';
            $isFail = $status === 'gagal' || $status === 'failed' || $status === 'expire' || $status === 'expired';

            $badgeClass = $isOk
              ? 'bg-emerald-500/15 text-emerald-100 border-emerald-400/20'
              : ($isFail
                ? 'bg-red-500/15 text-red-100 border-red-400/20'
                : 'bg-yellow-500/15 text-yellow-100 border-yellow-400/20');

            $tanggal = optional($transaksi->created_at)->translatedFormat('d M Y') ?? '-';
          @endphp

          <div class="glass rounded-[24px] p-5">
            <div class="flex items-start justify-between gap-3">
              <div class="min-w-0">
                <p class="font-extrabold text-white truncate">{{ $judul }}</p>
                <p class="mt-1 text-xs text-white/60">Tanggal: {{ $tanggal }}</p>
              </div>
              <span class="shrink-0 inline-flex items-center rounded-full border px-3 py-1 text-[11px] font-semibold {{ $badgeClass }}">
                {{ $transaksi->status_pembayaran }}
              </span>
            </div>

            <div class="mt-4 flex items-center justify-between">
              <p class="text-xs text-white/60">Nominal</p>
              <p class="text-base font-extrabold text-white">
                Rp {{ number_format((int) $transaksi->jumlah, 0, ',', '.') }}
              </p>
            </div>

            <div class="mt-2 flex items-center justify-between">
              <p class="text-xs text-white/60">ID</p>
              <p class="text-xs font-semibold text-white/80">{{ $transaksi->id }}</p>
            </div>
          </div>
        @empty
          <div class="glass rounded-[24px] p-6 text-center text-white/70">
            Belum ada riwayat donasi.
          </div>
        @endforelse
      </div>

      {{-- PAGINATION --}}
      <div class="pt-2">
        {{-- biar pagination tetap kebaca di background gelap --}}
        <div class="[&_.pagination]:justify-center [&_.pagination]:gap-2
                    [&_.pagination>li>a]:rounded-xl [&_.pagination>li>a]:border [&_.pagination>li>a]:border-white/12
                    [&_.pagination>li>a]:bg-white/6 [&_.pagination>li>a]:px-4 [&_.pagination>li>a]:py-2
                    [&_.pagination>li>a]:text-sm [&_.pagination>li>a]:text-white/80
                    [&_.pagination>li>a:hover]:bg-white/10
                    [&_.pagination>li>span]:rounded-xl [&_.pagination>li>span]:border [&_.pagination>li>span]:border-white/12
                    [&_.pagination>li>span]:bg-white/6 [&_.pagination>li>span]:px-4 [&_.pagination>li>span]:py-2
                    [&_.pagination>li>span]:text-sm [&_.pagination>li>span]:text-white/80
                    [&_.pagination>.active>span]:bg-[rgba(231,177,75,0.95)] [&_.pagination>.active>span]:text-[#13392f] [&_.pagination>.active>span]:border-[rgba(231,177,75,0.4)]">
          {{ $transaksis->links() }}
        </div>
      </div>

    </section>
  </div>
</x-front-layout>
