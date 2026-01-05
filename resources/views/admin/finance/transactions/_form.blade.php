@php
  $isEdit = isset($transaction);
  $old = fn($key, $default=null) => old($key, $isEdit ? data_get($transaction, $key) : $default);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="text-xs font-semibold text-slate-600">Tanggal</label>
    <input type="date" name="date" value="{{ $old('date', now()->toDateString()) }}" class="mt-1 w-full rounded-xl border-slate-200" required>
    @error('date') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
  </div>

  <div>
    <label class="text-xs font-semibold text-slate-600">Jam (opsional)</label>
    <input type="time" name="time" value="{{ $old('time') }}" class="mt-1 w-full rounded-xl border-slate-200">
  </div>

  <div>
    <label class="text-xs font-semibold text-slate-600">Tipe</label>
    <select name="type" id="type" class="mt-1 w-full rounded-xl border-slate-200" required>
      <option value="income" @selected($old('type')==='income')>Pemasukan</option>
      <option value="expense" @selected($old('type')==='expense')>Pengeluaran</option>
    </select>
    @error('type') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
  </div>

  <div id="divisionWrap">
    <label class="text-xs font-semibold text-slate-600">Bidang (wajib untuk pengeluaran)</label>
    <select name="division" class="mt-1 w-full rounded-xl border-slate-200">
      <option value="">-</option>
      <option value="idarah" @selected($old('division')==='idarah')>Idarah</option>
      <option value="imarah" @selected($old('division')==='imarah')>Imarah</option>
      <option value="riayah" @selected($old('division')==='riayah')>Riayah</option>
    </select>
    @error('division') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
  </div>

  <div>
    <label class="text-xs font-semibold text-slate-600">Sub-bidang (opsional)</label>
    <input type="text" name="subcategory" value="{{ $old('subcategory') }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="contoh: listrik / konsumsi / ATK">
  </div>

  <div class="md:col-span-2">
    <label class="text-xs font-semibold text-slate-600">Judul (WAJIB jelas “beli apa” / “sumber pemasukan”)</label>
    <input type="text" name="title" value="{{ $old('title') }}" class="mt-1 w-full rounded-xl border-slate-200" placeholder="contoh: Beli sapu & pel / Infak Jumat" required>
    @error('title') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="text-xs font-semibold text-slate-600">Keterangan (opsional)</label>
    <textarea name="description" rows="3" class="mt-1 w-full rounded-xl border-slate-200" placeholder="detail tambahan">{{ $old('description') }}</textarea>
  </div>

  <div>
    <label class="text-xs font-semibold text-slate-600">Nominal (Rupiah)</label>
    <input type="number" name="amount" value="{{ $old('amount', 0) }}" class="mt-1 w-full rounded-xl border-slate-200" min="0" required>
    @error('amount') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
  </div>

  <div>
    <label class="text-xs font-semibold text-slate-600">Metode</label>
    <select name="payment_method" class="mt-1 w-full rounded-xl border-slate-200" required>
      <option value="cash" @selected($old('payment_method')==='cash')>Tunai</option>
      <option value="transfer" @selected($old('payment_method')==='transfer')>Transfer</option>
      <option value="qris" @selected($old('payment_method')==='qris')>QRIS</option>
      <option value="other" @selected($old('payment_method')==='other')>Lainnya</option>
    </select>
  </div>

  <div>
    <label class="text-xs font-semibold text-slate-600">Akun/Kas (opsional)</label>
    <select name="account_id" class="mt-1 w-full rounded-xl border-slate-200">
      <option value="">-</option>
      @foreach($accounts as $a)
        <option value="{{ $a->id }}" @selected((string)$old('account_id')===(string)$a->id)>{{ $a->name }}</option>
      @endforeach
    </select>
  </div>

  <div>
    <label class="text-xs font-semibold text-slate-600">Tampilkan ke publik?</label>
    <select name="is_public" class="mt-1 w-full rounded-xl border-slate-200" required>
      <option value="1" @selected((string)$old('is_public','1')==='1')>Ya</option>
      <option value="0" @selected((string)$old('is_public')==='0')>Tidak</option>
    </select>
  </div>

  <div class="md:col-span-2">
    <label class="text-xs font-semibold text-slate-600">Upload Bukti (jpg/png/pdf, max 5MB)</label>
    <input type="file" name="receipt" class="mt-1 w-full rounded-xl border-slate-200">
    @error('receipt') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror

    @if($isEdit && $transaction->receipt_path)
      <div class="mt-2 text-sm">
        Bukti saat ini:
        <a class="text-emerald-700 font-semibold hover:underline" target="_blank" href="{{ route('public.finance.receipt', $transaction) }}">Lihat</a>
      </div>
    @endif
  </div>
</div>

<script>
  function toggleDivision() {
    const type = document.getElementById('type').value;
    document.getElementById('divisionWrap').style.display = (type === 'expense') ? 'block' : 'none';
  }
  document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('type').addEventListener('change', toggleDivision);
    toggleDivision();
  });
</script>
