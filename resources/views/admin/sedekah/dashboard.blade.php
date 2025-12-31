<x-app-layout>
  <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">
    <div class="flex items-start justify-between gap-3">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Admin • Sedekah Masjid</h1>
        <p class="text-slate-500 text-sm">Monitoring transaksi Midtrans (sandbox/production sesuai env).</p>
      </div>
      <a href="{{ route('admin.sedekah.campaigns') }}"
         class="rounded-xl bg-slate-900 text-white px-4 py-2 text-sm font-semibold">
        Kelola Program
      </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
      <div class="bg-white border border-slate-100 rounded-2xl p-4">
        <p class="text-xs text-slate-500 font-semibold">Pending</p>
        <p class="text-xl font-extrabold text-slate-900">{{ number_format($stats['pending']) }}</p>
      </div>
      <div class="bg-white border border-slate-100 rounded-2xl p-4">
        <p class="text-xs text-slate-500 font-semibold">Berhasil</p>
        <p class="text-xl font-extrabold text-slate-900">{{ number_format($stats['success']) }}</p>
      </div>
      <div class="bg-white border border-slate-100 rounded-2xl p-4">
        <p class="text-xs text-slate-500 font-semibold">Gagal/Cancel/Expired</p>
        <p class="text-xl font-extrabold text-slate-900">{{ number_format($stats['failed']) }}</p>
      </div>
      <div class="bg-white border border-slate-100 rounded-2xl p-4">
        <p class="text-xs text-slate-500 font-semibold">Total Berhasil</p>
        <p class="text-xl font-extrabold text-slate-900">Rp {{ number_format($stats['sum_success'],0,',','.') }}</p>
      </div>
    </div>

    <div class="bg-white border border-slate-100 rounded-3xl p-5 space-y-4">
      <form class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div>
          <label class="text-xs font-semibold text-slate-600">Status</label>
          <select name="status" class="mt-2 w-full h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900">
            <option value="">Semua</option>
            @foreach (['pending','success','failed','expired','challenge','cancel'] as $s)
              <option value="{{ $s }}" @selected(request('status')===$s)>{{ strtoupper($s) }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="text-xs font-semibold text-slate-600">Program</label>
          <select name="campaign" class="mt-2 w-full h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900">
            <option value="">Semua</option>
            @foreach ($campaigns as $c)
              <option value="{{ $c->id }}" @selected((string)request('campaign')===(string)$c->id)>{{ $c->judul }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex items-end">
          <button class="w-full h-11 rounded-xl bg-slate-900 text-white text-sm font-semibold">Filter</button>
        </div>
      </form>

      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-slate-50">
            <tr class="text-left">
              <th class="px-4 py-3 font-semibold text-slate-600">Order</th>
              <th class="px-4 py-3 font-semibold text-slate-600">Program</th>
              <th class="px-4 py-3 font-semibold text-slate-600">Jumlah</th>
              <th class="px-4 py-3 font-semibold text-slate-600">Status</th>
              <th class="px-4 py-3 font-semibold text-slate-600">Payment</th>
              <th class="px-4 py-3 font-semibold text-slate-600">Waktu</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($transaksis as $t)
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
                <td class="px-4 py-3 font-mono text-xs text-slate-700">{{ $t->order_id }}</td>
                <td class="px-4 py-3 text-slate-800 font-semibold">{{ $t->campaign?->judul ?? 'Umum' }}</td>
                <td class="px-4 py-3 font-extrabold text-slate-900">Rp {{ number_format($t->jumlah,0,',','.') }}</td>
                <td class="px-4 py-3">
                  <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badge }}">{{ strtoupper($t->status) }}</span>
                </td>
                <td class="px-4 py-3 text-slate-600">{{ $t->payment_type ?: '-' }}</td>
                <td class="px-4 py-3 text-slate-600">{{ $t->created_at->format('Y-m-d H:i') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div>{{ $transaksis->links() }}</div>
    </div>
  </div>
</x-app-layout>
