@extends('layouts.app')

@section('content')
@php
  // helper kecil biar class input konsisten
  $input = "mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder:text-slate-500
            focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20";
  $textarea = "mt-1 w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-slate-900 placeholder:text-slate-500
               focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20";
  $label = "block text-sm font-semibold text-slate-700";
  $card = "rounded-2xl border border-slate-200 bg-white p-5 shadow-sm";
@endphp

<div class="mx-auto w-full max-w-5xl">
    {{-- PAGE HEADER --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            <h1 class="text-2xl font-bold text-white tracking-tight text-slate-900">Profil Masjid</h1>
            <p class="mt-1 text-sm text-slate-500">
                Lengkapi data identitas, lokasi, kontak, konten, dan statistik masjid.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}"
               class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Kembali
            </a>
            <button form="profilMasjidForm" type="submit"
                    class="inline-flex items-center rounded-xl bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-600">
                Simpan
            </button>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">
            <div class="font-semibold">Berhasil</div>
            <div class="text-sm">{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-rose-800">
            <div class="font-semibold">Periksa kembali</div>
            <ul class="mt-2 list-disc pl-5 text-sm">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="profilMasjidForm" method="POST" action="{{ route('admin.profil.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- IDENTITAS --}}
        <section class="{{ $card }}">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Identitas</h2>
                    <p class="mt-1 text-sm text-slate-500">Informasi dasar masjid.</p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $label }}">Nama</label>
                    <input name="nama" value="{{ old('nama', $profile->nama) }}" class="{{ $input }}" required />
                </div>
                <div>
                    <label class="{{ $label }}">Kategori</label>
                    <input name="kategori" value="{{ old('kategori', $profile->kategori) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Tipe</label>
                    <input name="tipe" value="{{ old('tipe', $profile->tipe) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">No. ID Masjid</label>
                    <input name="no_id_masjid" value="{{ old('no_id_masjid', $profile->no_id_masjid) }}" class="{{ $input }}" />
                </div>
                <div class="sm:col-span-2">
                    <label class="{{ $label }}">Tahun Berdiri</label>
                    <input name="tahun_berdiri" value="{{ old('tahun_berdiri', $profile->tahun_berdiri) }}" class="{{ $input }}" />
                </div>
            </div>
        </section>

        {{-- LOKASI --}}
        <section class="{{ $card }}">
            <div class="mb-4">
                <h2 class="text-base font-bold text-slate-900">Lokasi</h2>
                <p class="mt-1 text-sm text-slate-500">Alamat dan koordinat.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="{{ $label }}">Alamat</label>
                    <textarea name="alamat" rows="3" class="{{ $textarea }}">{{ old('alamat', $profile->alamat) }}</textarea>
                </div>

                <div>
                    <label class="{{ $label }}">Kelurahan</label>
                    <input name="kelurahan" value="{{ old('kelurahan', $profile->kelurahan) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Kecamatan</label>
                    <input name="kecamatan" value="{{ old('kecamatan', $profile->kecamatan) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Kabupaten</label>
                    <input name="kabupaten" value="{{ old('kabupaten', $profile->kabupaten) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Provinsi</label>
                    <input name="provinsi" value="{{ old('provinsi', $profile->provinsi) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Kode Pos</label>
                    <input name="kode_pos" value="{{ old('kode_pos', $profile->kode_pos) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Lat</label>
                    <input name="lat" value="{{ old('lat', $profile->lat) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Lng</label>
                    <input name="lng" value="{{ old('lng', $profile->lng) }}" class="{{ $input }}" />
                </div>
            </div>
        </section>

        {{-- KONTAK --}}
        <section class="{{ $card }}">
            <div class="mb-4">
                <h2 class="text-base font-bold text-slate-900">Kontak & Link</h2>
                <p class="mt-1 text-sm text-slate-500">Info publik yang bisa diakses jamaah.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $label }}">Email</label>
                    <input name="email" value="{{ old('email', $profile->email) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Website (teks)</label>
                    <input name="website" value="{{ old('website', $profile->website) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">URL Website</label>
                    <input name="url_website" value="{{ old('url_website', $profile->url_website) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">URL Peta</label>
                    <input name="url_peta" value="{{ old('url_peta', $profile->url_peta) }}" class="{{ $input }}" />
                </div>
                <div class="sm:col-span-2">
                    <label class="{{ $label }}">URL Petunjuk Arah</label>
                    <input name="url_petunjuk" value="{{ old('url_petunjuk', $profile->url_petunjuk) }}" class="{{ $input }}" />
                </div>
            </div>
        </section>

        {{-- KONTEN --}}
        <section class="{{ $card }}">
            <div class="mb-4">
                <h2 class="text-base font-bold text-slate-900">Konten</h2>
                <p class="mt-1 text-sm text-slate-500">Sejarah & profil yang akan tampil di halaman publik.</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="{{ $label }}">Sejarah</label>
                    <textarea name="sejarah" rows="6" class="{{ $textarea }}">{{ old('sejarah', $profile->sejarah) }}</textarea>
                </div>

                <div>
                    <label class="{{ $label }}">Visi</label>
                    <textarea name="visi" rows="3" class="{{ $textarea }}">{{ old('visi', $profile->visi) }}</textarea>
                </div>

                <div>
                    <label class="{{ $label }}">Misi (1 baris = 1 poin)</label>
                    @php
                        $misiOld = old('misi_text');
                        $misiDb = $profile->misi ?? '';

                        if (is_string($misiDb)) {
                            $misiDb = preg_split("/\r\n|\n|\r/", $misiDb);
                        }
                        if (!is_array($misiDb)) {
                            $misiDb = [];
                        }
                        $misiText = is_string($misiOld)
                            ? $misiOld
                            : implode("\n", array_filter(array_map('trim', $misiDb)));
                    @endphp

                    <textarea name="misi_text" rows="5" class="{{ $textarea }}">{{ $misiText }}</textarea>
                    <p class="mt-2 text-xs text-slate-500">Tips: tekan Enter untuk menambah poin baru.</p>
                </div>
            </div>
        </section>

        {{-- SDM --}}
        <section class="{{ $card }}">
            <div class="mb-4">
                <h2 class="text-base font-bold text-slate-900">Statistik SDM</h2>
                <p class="mt-1 text-sm text-slate-500">Perkiraan jumlah personel.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <div>
                    <label class="{{ $label }}">Pengurus</label>
                    <input name="jumlah_pengurus" value="{{ old('jumlah_pengurus', $profile->jumlah_pengurus) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Imam</label>
                    <input name="jumlah_imam" value="{{ old('jumlah_imam', $profile->jumlah_imam) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Khatib</label>
                    <input name="jumlah_khatib" value="{{ old('jumlah_khatib', $profile->jumlah_khatib) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Muazin</label>
                    <input name="jumlah_muazin" value="{{ old('jumlah_muazin', $profile->jumlah_muazin) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Remaja</label>
                    <input name="jumlah_remaja" value="{{ old('jumlah_remaja', $profile->jumlah_remaja) }}" class="{{ $input }}" />
                </div>
            </div>
        </section>

        {{-- FISIK --}}
        <section class="{{ $card }}">
            <div class="mb-4">
                <h2 class="text-base font-bold text-slate-900">Data Fisik</h2>
                <p class="mt-1 text-sm text-slate-500">Informasi lahan dan bangunan.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="{{ $label }}">Luas Tanah (m²)</label>
                    <input name="luas_tanah_m2" value="{{ old('luas_tanah_m2', $profile->luas_tanah_m2) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Status Tanah</label>
                    <input name="status_tanah" value="{{ old('status_tanah', $profile->status_tanah) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Luas Bangunan (m²)</label>
                    <input name="luas_bangunan_m2" value="{{ old('luas_bangunan_m2', $profile->luas_bangunan_m2) }}" class="{{ $input }}" />
                </div>
                <div>
                    <label class="{{ $label }}">Daya Tampung</label>
                    <input name="daya_tampung" value="{{ old('daya_tampung', $profile->daya_tampung) }}" class="{{ $input }}" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit"
                        class="inline-flex items-center rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-600">
                    Simpan Perubahan
                </button>
            </div>
        </section>

    </form>
</div>
@endsection
