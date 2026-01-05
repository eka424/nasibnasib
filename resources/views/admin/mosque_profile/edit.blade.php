@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Profil Masjid</h1>

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
                    <label class="text-sm font-semibold">Nama</label>
                    <input name="nama" value="{{ old('nama', $profile->nama) }}" class="w-full border rounded p-2" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Kategori</label>
                    <input name="kategori" value="{{ old('kategori', $profile->kategori) }}" class="w-full border rounded p-2" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Tipe</label>
                    <input name="tipe" value="{{ old('tipe', $profile->tipe) }}" class="w-full border rounded p-2" />
                </div>
                <div>
                    <label class="text-sm font-semibold">No. ID Masjid</label>
                    <input name="no_id_masjid" value="{{ old('no_id_masjid', $profile->no_id_masjid) }}" class="w-full border rounded p-2" />
                </div>
                <div>
                    <label class="text-sm font-semibold">Tahun Berdiri</label>
                    <input name="tahun_berdiri" value="{{ old('tahun_berdiri', $profile->tahun_berdiri) }}" class="w-full border rounded p-2" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold mb-3">Lokasi</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="text-sm font-semibold">Alamat</label>
                    <textarea name="alamat" rows="3" class="w-full border rounded p-2">{{ old('alamat', $profile->alamat) }}</textarea>
                </div>

                <div><label class="text-sm font-semibold">Kelurahan</label><input name="kelurahan" value="{{ old('kelurahan', $profile->kelurahan) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Kecamatan</label><input name="kecamatan" value="{{ old('kecamatan', $profile->kecamatan) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Kabupaten</label><input name="kabupaten" value="{{ old('kabupaten', $profile->kabupaten) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Provinsi</label><input name="provinsi" value="{{ old('provinsi', $profile->provinsi) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Kode Pos</label><input name="kode_pos" value="{{ old('kode_pos', $profile->kode_pos) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Lat</label><input name="lat" value="{{ old('lat', $profile->lat) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Lng</label><input name="lng" value="{{ old('lng', $profile->lng) }}" class="w-full border rounded p-2" /></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold mb-3">Kontak & Link</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="text-sm font-semibold">Email</label><input name="email" value="{{ old('email', $profile->email) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Website (teks)</label><input name="website" value="{{ old('website', $profile->website) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">URL Website</label><input name="url_website" value="{{ old('url_website', $profile->url_website) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">URL Peta</label><input name="url_peta" value="{{ old('url_peta', $profile->url_peta) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">URL Petunjuk Arah</label><input name="url_petunjuk" value="{{ old('url_petunjuk', $profile->url_petunjuk) }}" class="w-full border rounded p-2" /></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold mb-3">Konten (Sejarah/Visi/Misi)</h2>

            <div class="space-y-4">
                <div>
                    <label class="text-sm font-semibold">Sejarah</label>
                    <textarea name="sejarah" rows="6" class="w-full border rounded p-2">{{ old('sejarah', $profile->sejarah) }}</textarea>
                </div>

                <div>
                    <label class="text-sm font-semibold">Visi</label>
                    <textarea name="visi" rows="3" class="w-full border rounded p-2">{{ old('visi', $profile->visi) }}</textarea>
                </div>

                <div>
                    <label class="text-sm font-semibold">Misi (1 baris = 1 poin)</label>
                    <textarea name="misi_text" rows="5" class="w-full border rounded p-2">@foreach(old('misi_text') ? explode("\n", old('misi_text')) : ($profile->misi ?? []) as $m){{ trim($m) }}
@endforeach</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold mb-3">Statistik SDM</h2>
            <div class="grid md:grid-cols-5 gap-4">
                <div><label class="text-sm font-semibold">Pengurus</label><input name="jumlah_pengurus" value="{{ old('jumlah_pengurus', $profile->jumlah_pengurus) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Imam</label><input name="jumlah_imam" value="{{ old('jumlah_imam', $profile->jumlah_imam) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Khatib</label><input name="jumlah_khatib" value="{{ old('jumlah_khatib', $profile->jumlah_khatib) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Muazin</label><input name="jumlah_muazin" value="{{ old('jumlah_muazin', $profile->jumlah_muazin) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Remaja</label><input name="jumlah_remaja" value="{{ old('jumlah_remaja', $profile->jumlah_remaja) }}" class="w-full border rounded p-2" /></div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold mb-3">Data Fisik</h2>
            <div class="grid md:grid-cols-2 gap-4">
                <div><label class="text-sm font-semibold">Luas Tanah (m²)</label><input name="luas_tanah_m2" value="{{ old('luas_tanah_m2', $profile->luas_tanah_m2) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Status Tanah</label><input name="status_tanah" value="{{ old('status_tanah', $profile->status_tanah) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Luas Bangunan (m²)</label><input name="luas_bangunan_m2" value="{{ old('luas_bangunan_m2', $profile->luas_bangunan_m2) }}" class="w-full border rounded p-2" /></div>
                <div><label class="text-sm font-semibold">Daya Tampung</label><input name="daya_tampung" value="{{ old('daya_tampung', $profile->daya_tampung) }}" class="w-full border rounded p-2" /></div>
            </div>
        </div>

        <div class="flex justify-end">
            <button class="px-5 py-2 rounded bg-emerald-600 text-white font-semibold hover:bg-emerald-700">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
