<x-front>
    <div class="py-8 text-[#DAF0DC]">
        {{-- Header --}}
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between mb-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 backdrop-blur">
                    <span>🏛️</span>
                    <span class="text-sm font-semibold">Profil Masjid</span>
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold mt-3 leading-tight">
                    {{ $profile->nama }}
                </h1>

                <p class="text-[#DAF0DC]/80 mt-2">
                    {{ $profile->kategori ? $profile->kategori.' • ' : '' }} {{ $profile->tipe ?? 'Masjid' }}
                </p>
            </div>

            <div class="flex gap-2 flex-wrap">
                <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-sm">Transparan</span>
                <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-sm">Terverifikasi</span>
                <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-sm">Aktif</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            {{-- KIRI --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Box Identitas --}}
                <div class="rounded-3xl bg-white/10 border border-white/10 backdrop-blur p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-[#DAF0DC]/70">No. ID Masjid</div>
                            <div class="font-semibold">{{ $profile->no_id_masjid ?? '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[#DAF0DC]/70">Didirikan</div>
                            <div class="font-semibold">
                                {{ $profile->tahun_berdiri ? 'Tahun '.$profile->tahun_berdiri : '-' }}
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <div class="text-[#DAF0DC]/70">Alamat</div>
                            <div class="font-semibold text-[#DAF0DC]/90 leading-relaxed">
                                {{ $profile->alamat ?? '-' }}
                            </div>
                            <div class="text-[#DAF0DC]/80 mt-2">
                                {{ $profile->kelurahan ?? '' }}
                                {{ $profile->kabupaten ? ' , '.$profile->kabupaten : '' }}
                                {{ $profile->provinsi ? ' • '.$profile->provinsi : '' }}
                                {{ $profile->kode_pos ? ' • '.$profile->kode_pos : '' }}
                            </div>
                        </div>

                        <div class="flex gap-3 flex-wrap md:col-span-2 mt-2">
                            @if($profile->url_peta)
                                <a href="{{ $profile->url_peta }}" target="_blank"
                                   class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold bg-white text-emerald-900 hover:opacity-90">
                                    Lihat Peta
                                </a>
                            @endif

                            @if($profile->url_petunjuk)
                                <a href="{{ $profile->url_petunjuk }}" target="_blank"
                                   class="inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold bg-emerald-900/60 border border-white/10 hover:bg-emerald-900/80">
                                    Petunjuk Arah
                                </a>
                            @endif
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                                <div class="text-[#DAF0DC]/70 text-xs">Email</div>
                                <div class="font-semibold break-all">{{ $profile->email ?? '-' }}</div>
                            </div>
                            <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                                <div class="text-[#DAF0DC]/70 text-xs">Website</div>
                                @if($profile->url_website)
                                    <a class="font-semibold underline decoration-[#F6B443] break-all"
                                       href="{{ $profile->url_website }}" target="_blank">
                                        {{ $profile->website ?? $profile->url_website }}
                                    </a>
                                @else
                                    <div class="font-semibold break-all">{{ $profile->website ?? '-' }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sejarah (ringkas) --}}
                <div class="rounded-3xl bg-white/10 border border-white/10 backdrop-blur p-6">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <h2 class="text-xl font-bold">Sejarah Masjid</h2>

                        <a href="{{ route('mosque.sejarah') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 hover:bg-white/15 text-sm font-semibold">
                            Baca selengkapnya →
                        </a>
                    </div>

                    @php
                        $ringkas = \Illuminate\Support\Str::limit($profile->sejarah ?? '', 260);
                    @endphp

                    <p class="text-[#DAF0DC]/90 leading-relaxed whitespace-pre-line mt-3">
                        {{ $ringkas ?: 'Belum ada sejarah.' }}
                    </p>
                </div>

                {{-- Visi & Misi --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="rounded-3xl bg-white/10 border border-white/10 backdrop-blur p-6">
                        <h2 class="text-xl font-bold mb-3">Visi</h2>
                        <p class="text-[#DAF0DC]/90 leading-relaxed">
                            {{ $profile->visi ?? 'Belum ada visi.' }}
                        </p>
                    </div>

                    <div class="rounded-3xl bg-white/10 border border-white/10 backdrop-blur p-6">
                        <h2 class="text-xl font-bold mb-3">Misi</h2>
                        <ol class="list-decimal pl-5 space-y-2 text-[#DAF0DC]/90">
                            @forelse(($profile->misi ?? []) as $item)
                                <li class="leading-relaxed">{{ $item }}</li>
                            @empty
                                <li>Belum ada misi.</li>
                            @endforelse
                        </ol>
                    </div>
                </div>

                {{-- Statistik --}}
                <div class="rounded-3xl bg-white/10 border border-white/10 backdrop-blur p-6">
                    <h2 class="text-xl font-bold mb-4">Statistik SDM</h2>

                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @php
                            $stats = [
                                ['label' => 'Pengurus', 'val' => $profile->jumlah_pengurus],
                                ['label' => 'Imam', 'val' => $profile->jumlah_imam],
                                ['label' => 'Khatib', 'val' => $profile->jumlah_khatib],
                                ['label' => 'Muazin', 'val' => $profile->jumlah_muazin],
                                ['label' => 'Remaja', 'val' => $profile->jumlah_remaja],
                            ];
                        @endphp

                        @foreach($stats as $s)
                            <div class="rounded-2xl bg-white/10 border border-white/10 p-4 text-center">
                                <div class="text-2xl font-extrabold">{{ (int)($s['val'] ?? 0) }}</div>
                                <div class="text-xs text-[#DAF0DC]/70 mt-1">{{ $s['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Data Fisik --}}
                <div id="fisik" class="rounded-3xl bg-white/10 border border-white/10 backdrop-blur p-6">
                    <h2 class="text-xl font-bold mb-4">Data Fisik</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                            <div class="text-[#DAF0DC]/70 text-xs">Luas Tanah</div>
                            <div class="font-semibold">{{ $profile->luas_tanah_m2 !== null ? $profile->luas_tanah_m2.' m²' : '-' }}</div>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                            <div class="text-[#DAF0DC]/70 text-xs">Status Tanah</div>
                            <div class="font-semibold">{{ $profile->status_tanah ?? '-' }}</div>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                            <div class="text-[#DAF0DC]/70 text-xs">Luas Bangunan</div>
                            <div class="font-semibold">{{ $profile->luas_bangunan_m2 !== null ? $profile->luas_bangunan_m2.' m²' : '-' }}</div>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                            <div class="text-[#DAF0DC]/70 text-xs">Daya Tampung Jamaah</div>
                            <div class="font-semibold">{{ $profile->daya_tampung !== null ? number_format($profile->daya_tampung, 0, ',', '.') : '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

<a href="{{ route('mosque.struktur') }}"
   class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 hover:bg-white/15 text-sm font-semibold">
   Struktur Organisasi →
</a>

<a href="{{ route('mosque.work_program') }}" class="btn btn-outline-light">
  Program Kerja →
</a>

                <div class="rounded-3xl bg-white/10 border border-white/10 backdrop-blur p-6">
                    <h3 class="text-lg font-bold">Ingin bantu memakmurkan masjid?</h3>
                    <p class="text-sm text-[#DAF0DC]/80 mt-2 leading-relaxed">
                        Kamu bisa ikut kegiatan, berdonasi, atau kontribusi program sosial.
                    </p>

                    <div class="mt-4">
                        <a href="#"
                           class="w-full inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold bg-[#F6B443] text-emerald-900 hover:opacity-90">
                            Mulai Donasi 💛
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-front>
