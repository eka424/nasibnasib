<x-app-layout>
  <div class="max-w-7xl mx-auto px-4 py-8 space-y-6">
    <div class="flex items-start justify-between">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Kelola Program Sedekah</h1>
        <p class="text-sm text-slate-500">Tambah program baru atau ubah program yang ada.</p>
      </div>
      <a href="{{ route('admin.sedekah.dashboard') }}" class="text-sm font-semibold text-slate-700">Kembali</a>
    </div>

    @if (session('ok'))
      <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl p-4 text-sm font-semibold">
        {{ session('ok') }}
      </div>
    @endif

    <div class="bg-white border border-slate-100 rounded-3xl p-5">
      <h2 class="text-sm font-bold text-slate-900">Tambah Program</h2>

      <form class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3" method="POST" action="{{ route('admin.sedekah.campaigns.store') }}">
        @csrf
        <input name="judul" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900" placeholder="Judul program" required>
        <input name="poster" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900" placeholder="URL poster (opsional)">
        <input name="target_dana" type="number" min="0" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900" placeholder="Target dana (0 jika fleksibel)">
        <input name="tanggal_selesai" type="date" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900">
        <textarea name="deskripsi" rows="3" class="sm:col-span-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900" placeholder="Deskripsi program (opsional)"></textarea>

        <label class="inline-flex items-center gap-2 text-sm text-slate-700 font-semibold">
          <input type="checkbox" name="is_active" value="1" checked> Aktif
        </label>

        <button class="h-11 rounded-xl bg-slate-900 text-white text-sm font-semibold sm:col-span-2">Simpan Program</button>
      </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @foreach ($campaigns as $c)
        <div class="bg-white border border-slate-100 rounded-3xl p-5 space-y-3">
          <div>
            <p class="text-sm font-extrabold text-slate-900">{{ $c->judul }}</p>
            <p class="text-xs text-slate-500">Terkumpul: Rp {{ number_format($c->dana_terkumpul,0,',','.') }} • Target: Rp {{ number_format($c->target_dana,0,',','.') }}</p>
          </div>

          <form method="POST" action="{{ route('admin.sedekah.campaigns.update', $c) }}" class="grid grid-cols-1 gap-2">
            @csrf @method('PUT')
            <input name="judul" value="{{ $c->judul }}" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900" required>
            <input name="poster" value="{{ $c->poster }}" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900" placeholder="URL poster">
            <input name="target_dana" type="number" min="0" value="{{ $c->target_dana }}" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900">
            <input name="tanggal_selesai" type="date" value="{{ optional($c->tanggal_selesai)->format('Y-m-d') }}" class="h-11 rounded-xl border border-slate-200 bg-white px-3 text-sm text-slate-900">
            <textarea name="deskripsi" rows="3" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900">{{ $c->deskripsi }}</textarea>

            <label class="inline-flex items-center gap-2 text-sm text-slate-700 font-semibold">
              <input type="checkbox" name="is_active" value="1" @checked($c->is_active)> Aktif
            </label>

            <button class="h-11 rounded-xl bg-slate-900 text-white text-sm font-semibold">Update</button>
          </form>
        </div>
      @endforeach
    </div>

    <div>{{ $campaigns->links() }}</div>
  </div>
</x-app-layout>
