@php
  $fmt = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
@endphp

<x-app-layout>
  <div class="py-8 space-y-6 bg-[#13392f] -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="rounded-2xl bg-white/10 border border-white/10 p-5">
      <div class="flex items-end justify-between gap-4 flex-wrap">
        <div>
          <h1 class="text-2xl font-bold text-white">Transparansi Keuangan Masjid</h1>
          <p class="text-white/70 text-sm">
            Saldo otomatis dari saldo awal + pemasukan - pengeluaran
          </p>
        </div>

        <div class="flex gap-2 flex-wrap items-center">
          <form class="flex gap-2 flex-wrap items-center">
            <select name="mode"
              class="rounded-xl bg-white/90 text-[#13392f] border border-black/10 px-3 py-2 text-sm font-semibold">
              <option value="day" @selected($mode==='day')>Harian</option>
              <option value="week" @selected($mode==='week')>Mingguan</option>
              <option value="month" @selected($mode==='month')>Bulanan</option>
              <option value="year" @selected($mode==='year')>Tahunan</option>
            </select>

            <input type="date" name="date" value="{{ $date }}"
              class="rounded-xl bg-white/90 text-[#13392f] border border-black/10 px-3 py-2 text-sm font-semibold">

            <button
              class="rounded-xl bg-[#E7B14B] text-[#13392f] px-4 py-2 text-sm font-bold border border-black/10 hover:brightness-105">
              Tampilkan
            </button>
          </form>

          <a href="{{ route('public.finance.yearly', ['year' => $year ?? now()->year]) }}"
             class="rounded-xl bg-white/90 text-[#13392f] px-4 py-2 text-sm font-bold border border-black/10 hover:bg-white">
            Rekap Tahunan →
          </a>
        </div>
      </div>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

      {{-- Sisa Kas (Hijau) --}}
      <div class="rounded-2xl bg-emerald-600 text-white border border-white/10 p-5 shadow-sm">
        <div class="text-sm opacity-90">Sisa Kas (Tahun {{ $year }})</div>
        <div class="mt-1 text-2xl font-extrabold">{{ $fmt($balanceYear) }}</div>
        <div class="mt-2 text-xs opacity-90">Saldo awal: {{ $fmt($opening) }}</div>
      </div>

      {{-- Kas Hari Ini (Hijau Muda) --}}
      <div class="rounded-2xl bg-emerald-300 text-[#13392f] border border-black/10 p-5 shadow-sm">
        <div class="text-sm font-semibold">Kas Hari Ini (Net)</div>
        <div class="mt-1 text-2xl font-extrabold">{{ $fmt($todayNet) }}</div>
        <div class="mt-2 text-xs text-[#13392f]/80">
          Masuk: {{ $fmt($todayIncome) }} • Keluar: {{ $fmt($todayExpense) }}
        </div>
      </div>

      {{-- Pengeluaran (Merah) --}}
      <div class="rounded-2xl bg-red-600 text-white border border-white/10 p-5 shadow-sm">
        <div class="text-sm opacity-90">Pengeluaran (Periode Dipilih)</div>
        <div class="mt-1 text-2xl font-extrabold">{{ $fmt($expenseRange) }}</div>
        <div class="mt-2 text-xs opacity-90">{{ $start->toDateString() }} s/d {{ $end->toDateString() }}</div>
      </div>

      {{-- Pemasukan (Biru) --}}
      <div class="rounded-2xl bg-blue-600 text-white border border-white/10 p-5 shadow-sm">
        <div class="text-sm opacity-90">Pemasukan (Periode Dipilih)</div>
        <div class="mt-1 text-2xl font-extrabold">{{ $fmt($incomeRange) }}</div>
        <div class="mt-2 text-xs opacity-90">{{ $start->toDateString() }} s/d {{ $end->toDateString() }}</div>
      </div>

    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <div class="rounded-2xl bg-white/90 text-[#13392f] border border-black/10 p-5">
        <div class="font-bold">Diagram Pemasukan vs Pengeluaran</div>
        <div class="text-sm text-[#13392f]/70 mb-3">
          {{ $mode === 'year' ? 'Per bulan (1 tahun)' : 'Per hari (periode dipilih)' }}
        </div>
        <canvas id="barChart" height="120"></canvas>
      </div>

      <div class="rounded-2xl bg-white/90 text-[#13392f] border border-black/10 p-5">
        <div class="font-bold">Diagram Pengeluaran per Bidang</div>
        <div class="text-sm text-[#13392f]/70 mb-3">Idarah • Imarah • Riayah • Lainnya</div>
        <canvas id="donutChart" height="120"></canvas>
      </div>
    </div>

    {{-- Detail transaksi (beli apa kelihatan) --}}
    <div class="rounded-2xl bg-white/90 text-[#13392f] border border-black/10 overflow-hidden">
      <div class="px-5 py-4 bg-[#E7B14B] text-[#13392f] flex items-center justify-between">
        <div class="font-bold">Detail Transaksi (Publik)</div>
        <div class="text-xs text-[#13392f]/70">Keluar “beli apa” terlihat</div>
      </div>

      <div class="p-5 overflow-auto">
        <table class="w-full text-sm">
          <thead class="text-left text-[#13392f]/70">
            <tr>
              <th class="py-2 pr-3">Tanggal</th>
              <th class="py-2 pr-3">Tipe</th>
              <th class="py-2 pr-3">Bidang</th>
              <th class="py-2 pr-3">Keterangan</th>
              <th class="py-2 pr-3 text-right">Nominal</th>
              <th class="py-2 pr-3">Bukti</th>
            </tr>
          </thead>
          <tbody>
            @forelse($transactions as $t)
              <tr class="border-t border-black/10">
                <td class="py-2 pr-3 whitespace-nowrap">{{ $t->date->toDateString() }}</td>

                <td class="py-2 pr-3 font-bold {{ $t->type==='income' ? 'text-blue-700' : 'text-red-700' }}">
                  {{ $t->type==='income' ? 'Pemasukan' : 'Pengeluaran' }}
                </td>

                <td class="py-2 pr-3 uppercase text-xs font-bold">
                  {{ $t->division ?? '-' }}
                </td>

                <td class="py-2 pr-3">
                  <div class="font-semibold">{{ $t->title }}</div>
                  @if($t->subcategory)
                    <div class="text-xs text-[#13392f]/60">{{ $t->subcategory }}</div>
                  @endif
                </td>

                <td class="py-2 pr-3 text-right font-extrabold">
                  {{ $fmt($t->amount) }}
                </td>

                <td class="py-2 pr-3">
                  @if($t->receipt_path)
                    <a class="text-[#13392f] font-bold hover:underline"
                       href="{{ route('public.finance.receipt', $t) }}" target="_blank" rel="noopener">
                      Lihat
                    </a>
                  @else
                    <span class="text-[#13392f]/40">-</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="py-4 text-[#13392f]/60">Belum ada transaksi di periode ini.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <script type="module">
    import Chart from 'chart.js/auto';

    const series = @json($series);
    const labels = series.map(x => x.label);
    const income = series.map(x => x.income);
    const expense = series.map(x => x.expense);

    new Chart(document.getElementById('barChart'), {
      type: 'bar',
      data: {
        labels,
        datasets: [
          { label: 'Pemasukan', data: income, backgroundColor: 'rgba(37, 99, 235, 0.7)' }, // biru
          { label: 'Pengeluaran', data: expense, backgroundColor: 'rgba(220, 38, 38, 0.7)' }, // merah
        ],
      },
      options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
      }
    });

    const byDivision = @json($byDivision);

    new Chart(document.getElementById('donutChart'), {
      type: 'doughnut',
      data: {
        labels: Object.keys(byDivision),
        datasets: [{
          data: Object.values(byDivision),
          backgroundColor: [
            'rgba(16, 185, 129, 0.8)', // emerald
            'rgba(231, 177, 75, 0.9)', // kuning
            'rgba(59, 130, 246, 0.7)', // biru
            'rgba(148, 163, 184, 0.7)' // slate (lainnya)
          ],
        }],
      },
      options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
  </script>
</x-app-layout>
