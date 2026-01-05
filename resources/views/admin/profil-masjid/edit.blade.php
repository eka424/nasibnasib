@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Profil Masjid</h1>

    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profil.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold mb-3">Identitas</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input name="nama" value="{{ old('nama', $profile->nama) }}" class="mt-1 w-full rounded border-gray-300" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <input name="kategori" value="{{ old('kategori', $profile->kategori) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe</label>
                    <input name="tipe" value="{{ old('tipe', $profile->tipe) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. ID Masjid</label>
                    <input name="no_id_masjid" value="{{ old('no_id_masjid', $profile->no_id_masjid) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun Berdiri</label>
                    <input name="tahun_berdiri" value="{{ old('tahun_berdiri', $profile->tahun_berdiri) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold mb-3">Lokasi</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Alamat</label>
                    <textarea name="alamat" rows="3" class="mt-1 w-full rounded border-gray-300">{{ old('alamat', $profile->alamat) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kelurahan</label>
                    <input name="kelurahan" value="{{ old('kelurahan', $profile->kelurahan) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kecamatan</label>
                    <input name="kecamatan" value="{{ old('kecamatan', $profile->kecamatan) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kabupaten</label>
                    <input name="kabupaten" value="{{ old('kabupaten', $profile->kabupaten) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Provinsi</label>
                    <input name="provinsi" value="{{ old('provinsi', $profile->provinsi) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Pos</label>
                    <input name="kode_pos" value="{{ old('kode_pos', $profile->kode_pos) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lat</label>
                    <input name="lat" value="{{ old('lat', $profile->lat) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lng</label>
                    <input name="lng" value="{{ old('lng', $profile->lng) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold mb-3">Kontak & Link</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input name="email" value="{{ old('email', $profile->email) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Website (teks)</label>
                    <input name="website" value="{{ old('website', $profile->website) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">URL Website</label>
                    <input name="url_website" value="{{ old('url_website', $profile->url_website) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">URL Peta</label>
                    <input name="url_peta" value="{{ old('url_peta', $profile->url_peta) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">URL Petunjuk Arah</label>
                    <input name="url_petunjuk" value="{{ old('url_petunjuk', $profile->url_petunjuk) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold mb-3">Konten</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sejarah</label>
                    <textarea name="sejarah" rows="6" class="mt-1 w-full rounded border-gray-300">{{ old('sejarah', $profile->sejarah) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Visi</label>
                    <textarea name="visi" rows="3" class="mt-1 w-full rounded border-gray-300">{{ old('visi', $profile->visi) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Misi (1 baris = 1 poin)</label>
                    @php
    // ambil misi lama dari input
    $misiOld = old('misi_text');

    // ambil misi dari database
    $misiDb = $profile->misi ?? '';

    // kalau misi dari DB string → jadikan baris-baris
    if (is_string($misiDb)) {
        $misiDb = preg_split("/\r\n|\n|\r/", $misiDb);
    }

    // pastikan array
    if (!is_array($misiDb)) {
        $misiDb = [];
    }

    // isi textarea:
    // 1. pakai old() kalau ada
    // 2. kalau tidak, gabung misi DB
    $misiText = is_string($misiOld)
        ? $misiOld
        : implode("\n", array_filter(array_map('trim', $misiDb)));
@endphp

<textarea name="misi_text"
          rows="5"
          class="mt-1 w-full rounded border-gray-300">{{ $misiText }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold mb-3">Statistik SDM</h2>
            <div class="grid md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pengurus</label>
                    <input name="jumlah_pengurus" value="{{ old('jumlah_pengurus', $profile->jumlah_pengurus) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Imam</label>
                    <input name="jumlah_imam" value="{{ old('jumlah_imam', $profile->jumlah_imam) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Khatib</label>
                    <input name="jumlah_khatib" value="{{ old('jumlah_khatib', $profile->jumlah_khatib) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Muazin</label>
                    <input name="jumlah_muazin" value="{{ old('jumlah_muazin', $profile->jumlah_muazin) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Remaja</label>
                    <input name="jumlah_remaja" value="{{ old('jumlah_remaja', $profile->jumlah_remaja) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold mb-3">Data Fisik</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Luas Tanah (m²)</label>
                    <input name="luas_tanah_m2" value="{{ old('luas_tanah_m2', $profile->luas_tanah_m2) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Tanah</label>
                    <input name="status_tanah" value="{{ old('status_tanah', $profile->status_tanah) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Luas Bangunan (m²)</label>
                    <input name="luas_bangunan_m2" value="{{ old('luas_bangunan_m2', $profile->luas_bangunan_m2) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Daya Tampung</label>
                    <input name="daya_tampung" value="{{ old('daya_tampung', $profile->daya_tampung) }}" class="mt-1 w-full rounded border-gray-300" />
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded bg-emerald-600 px-5 py-2 text-white font-semibold hover:bg-emerald-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
