@csrf
@if(isset($transaksi) && $transaksi->exists)
    @method('PUT')
@endif

<div class="space-y-4">
    @isset($users)
        <div>
            <label class="block text-sm font-medium text-gray-700">Jamaah</label>
            <select name="user_id" class="mt-1 w-full rounded border-gray-300" required>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id', $transaksi->user_id ?? '') == $user->id)>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Donasi</label>
            <select name="donasi_id" class="mt-1 w-full rounded border-gray-300" required>
                @foreach ($donasis as $donasi)
                    <option value="{{ $donasi->id }}" @selected(old('donasi_id', $transaksi->donasi_id ?? '') == $donasi->id)>
                        {{ $donasi->judul }}
                    </option>
                @endforeach
            </select>
        </div>
    @endisset
    <div>
        <label class="block text-sm font-medium text-gray-700">Jumlah</label>
        <input type="number" name="jumlah" value="{{ old('jumlah', $transaksi->jumlah ?? '') }}"
            class="mt-1 w-full rounded border-gray-300" min="10000" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700">Status</label>
        <select name="status_pembayaran" class="mt-1 w-full rounded border-gray-300" required>
            @foreach (['pending', 'berhasil', 'gagal'] as $status)
                <option value="{{ $status }}" @selected(old('status_pembayaran', $transaksi->status_pembayaran ?? 'pending') === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="rounded bg-emerald-600 px-4 py-2 text-white">Simpan</button>
</div>
