<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css'])
  <title>Tambah Transaksi</title>
</head>

<body class="bg-slate-100 text-slate-800">
  <div class="mx-auto max-w-4xl px-4 py-6 space-y-4">

    {{-- Header --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <div class="text-xl font-extrabold text-slate-900">Tambah Transaksi</div>
          <div class="text-sm text-slate-500">Pastikan judul jelas: “beli apa / sumber pemasukan”.</div>
        </div>

        <a href="{{ route('admin.finance.transaksi.index') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-slate-100 text-slate-800 px-4 py-2 text-sm font-bold border border-slate-200 hover:bg-slate-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
          </svg>
          Kembali
        </a>
      </div>
    </div>

    {{-- Form --}}
    <form method="POST"
          action="{{ route('admin.finance.transaksi.store') }}"
          enctype="multipart/form-data"
          class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm space-y-4">
      @csrf

      {{-- Error summary (biar kelihatan kalau gagal validasi) --}}
      @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
          <div class="font-extrabold mb-1">Ada data yang belum benar:</div>
          <ul class="list-disc ml-5 text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      {{-- Form fields partial --}}
      @include('admin.finance.transactions._form', ['accounts' => $accounts])

      <div class="flex flex-col sm:flex-row gap-2 sm:justify-end pt-2">
        <a href="{{ route('admin.finance.transaksi.index') }}"
           class="inline-flex justify-center rounded-xl bg-white text-slate-800 px-4 py-2 text-sm font-bold border border-slate-200 hover:bg-slate-50">
          Batal
        </a>

        <button class="inline-flex justify-center rounded-xl bg-[#E7B14B] text-[#13392f] px-5 py-2 text-sm font-extrabold border border-black/10 hover:brightness-105">
          Simpan Transaksi
        </button>
      </div>
    </form>

  </div>
</body>
</html>
