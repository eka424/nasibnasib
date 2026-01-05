<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css'])
  <title>Edit Transaksi</title>
</head>
<body class="bg-white text-slate-800">
  <div class="mx-auto max-w-4xl px-4 py-6 space-y-4">
    <div class="flex items-center justify-between">
      <div class="text-xl font-bold">Edit Transaksi</div>
      <a href="{{ route('admin.finance.transaksi.index') }}" class="text-emerald-700 font-semibold hover:underline">Kembali</a>
    </div>

    <form method="POST" action="{{ route('admin.finance.transaksi.update', $transaction) }}" enctype="multipart/form-data" class="rounded-2xl border p-4 space-y-4">
      @csrf
      @method('PUT')
      @include('admin.finance.transactions._form', ['transaction' => $transaction, 'accounts' => $accounts])
      <button class="rounded-xl bg-yellow-500 text-white px-4 py-2 font-semibold hover:bg-yellow-600">Update</button>
    </form>
  </div>
</body>
</html>
