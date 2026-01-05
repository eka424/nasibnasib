@php
  $fmt = fn($n) => 'Rp ' . number_format((int)$n, 0, ',', '.');
@endphp

<x-app-layout>
  <div class="py-8 space-y-6 bg-[#13392f] -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="rounded-2xl bg-white/10 border border-white/10 p-5">
      <div class="flex items-end justify-between gap-4 flex-wrap">
        <div>
          <h1 class="text-2xl font-bold text-white">Rekap Tahunan Keuangan</h1>
          <p class="text-white/70 text-sm">Rekap per bulan + saldo berjalan (tahun {{ $year }})</p>
        </div>

        <div class="flex gap-2 flex-wrap items-center">
          <form class="flex gap-2 flex-wrap items-center">
            <input type="number" min="2000" max="2100" name="year" value="{{ $year }}"
              class="w-28 rounded-xl bg-white/90 text-[#13392f] border border-black/10 px-3 py-2 text-sm font-semibold">

            <button
              class="rounded-xl bg-[#E7B14B] text-[#13392f] px-4 py-2 text-sm font-bold border border-black/10 hover:brightness-105">
              Tampilkan
            </button>
          </form>

          <a href="{{ route('public.finance') }}"
            class="rounded-xl bg-white/90 text-[#13392f] px-4 py-2 text-sm font-bold border border-black/10 hover:bg-white">
            ← Kembali
          </a>
        </div>
      </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="rounded-2xl bg-white/90 text-[#13392f] border border-black/10 p-5 shadow-sm">
        <div class="text-sm text-[#13392f]/70 font-semibold">Saldo Awal Tahun</div>
        <div class="mt-1 text-xl font-extrabold text-emerald-700">{{ $fmt($opening) }}</div>
      </div>

      <div class="rounded-2xl bg-white/90 text-[#13392f] border border-black/10 p-5 shadow-sm">
        <div class="text-sm text-[#13392f]/70 font-semibold">Total Pemasukan</div>
        <div class="mt-1 text-xl font-extrabold text-blue-700">{{ $fmt($incomeYear) }}</div>
      </div>

      <div class="rounded-2xl bg-white/90 text-[#13392f] border border-black/10 p-5 shadow-sm">
        <div class="text-sm text-[#13392f]/70 font-semibold">Total Pengeluaran</div>
        <div class="mt-1 text-xl font-extrabold text-red-700">{{ $fmt($expenseYear) }}</div>
      </div>

      <div class="rounded-2xl bg-white/90 text-[#13392f] border border-black/10 p-5 shadow-sm">
        <div class="text-sm text-[#13392f]/70 font-semibold">Sisa Kas (Akhir Tahun)</div>
        <div class="mt-1 text-xl font-extrabold text-emerald-700">{{ $fmt($balanceYear) }}</div>
      </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <div class="rounded-2xl bg-white/90 text-[#13392f] border border-black/10 p-5">
        <div class="font-bold">Diagram Pemasukan vs Pengeluaran (per bulan)</div>
        <div class="text-sm text-[#13392f]/70 mb-3">Tahun {{ $year }}</div>
        <canvas id="barChart" height="120"></canvas>
      </div>

      <div class="rounded-2xl bg-white/90 text-[#13392f] border border-black/10 p-5">
        <div class="font-bold">Diagram Pengeluaran per Bidang (setahun)</div>
        <div class="text-sm text-[#13392f]/70 mb-3">Idarah • Imarah • Riayah • Lainnya</div>
        <canvas id="donutChart" height="120"></canvas>
      </div>
    </div>

    {{-- Table Rekap --}}
    <div class="rounded-2xl bg-white/90 text-[#13392f] border border-black/10 overflow-hidden">
      <div class="px-5 py-4 bg-[#13392f] text-white flex items-center justify-between">
        <div class="font-bold">Tabel Rekap Tahunan {{ $year }}</div>
        <div class="text-xs text-white/70">Saldo berjalan per bulan</div>
      </div>

      <div class="p-5 overflow-auto">
        <table class="w-full text-sm">
          <thead class="text-left text-[#13392f]/70">
            <tr>
              <th class="py-2 pr-3">Bulan</th>
              <th class="py-2 pr-3 text-right">Pemasukan</th>
              <th class="py-2 pr-3 text-right">Pengeluaran</th>
              <th class="py-2 pr-3 text-right">Saldo Berjalan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($yearRecap as $row)
              <tr class="border-t border-black/10">
                <td class="py-2 pr-3 font-semibold">{{ $row['month'] }}</td>
                <td class="py-2 pr-3 text-right font-extrabold text-blue-700">{{ $fmt($row['income']) }}</td>
                <td class="py-2 pr-3 text-right font-extrabold text-red-700">{{ $fmt($row['expense']) }}</td>
                <td class="py-2 pr-3 text-right font-extrabold text-emerald-700">{{ $fmt($row['balance']) }}</td>
              </tr>
            @endforeach
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
          { label: 'Pemasukan', data: income, backgroundColor: 'rgba(37, 99, 235, 0.75)' }, // biru
          { label: 'Pengeluaran', data: expense, backgroundColor: 'rgba(220, 38, 38, 0.75)' }, // merah
        ],
      },
      options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
      }
    });

    const byDivision = @json($byDivisionYear);

    new Chart(document.getElementById('donutChart'), {
      type: 'doughnut',
      data: {
        labels: Object.keys(byDivision),
        datasets: [{
          data: Object.values(byDivision),
          backgroundColor: [
            'rgba(16, 185, 129, 0.85)', // emerald
            'rgba(231, 177, 75, 0.95)', // kuning
            'rgba(59, 130, 246, 0.70)', // biru
            'rgba(148, 163, 184, 0.70)' // slate (lainnya)
          ],
        }],
      },
      options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
  </script>
</x-app-layout>
