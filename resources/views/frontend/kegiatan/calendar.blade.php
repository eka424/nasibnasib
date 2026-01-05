<x-front-layout>
@php
  $tz = $tz ?? 'Asia/Makassar';

  // $base, $days, $byDate, $events dikirim dari controller
  $current = $base ?? \Carbon\Carbon::now($tz)->startOfMonth();

  $prevMonth = $current->copy()->subMonth()->format('Y-m');
  $nextMonth = $current->copy()->addMonth()->format('Y-m');

  $dayNames = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
@endphp

<div class="min-h-screen bg-[#13392f] text-white">
  <div class="mx-auto max-w-6xl px-4 py-10 space-y-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-end justify-between gap-3">
      <div>
        <h1 class="text-2xl font-extrabold">Kalender Kegiatan</h1>
        <p class="text-white/70 text-sm">Titik emas = ada kegiatan</p>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route('kegiatan.calendar', ['month' => $prevMonth]) }}"
           class="rounded-xl bg-white/10 hover:bg-white/15 border border-white/15 px-3 py-2 text-sm font-semibold">
          ←
        </a>

        <div class="rounded-xl bg-white/10 border border-white/15 px-4 py-2 text-sm font-semibold">
          {{ $current->locale('id')->translatedFormat('F Y') }}
        </div>

        <a href="{{ route('kegiatan.calendar', ['month' => $nextMonth]) }}"
           class="rounded-xl bg-white/10 hover:bg-white/15 border border-white/15 px-3 py-2 text-sm font-semibold">
          →
        </a>

        <a href="{{ route('kegiatan.index') }}"
           class="rounded-xl bg-white text-[#13392f] px-4 py-2 text-sm font-bold hover:brightness-105">
          Lihat daftar kegiatan
        </a>
      </div>
    </div>

    {{-- Kalender --}}
    <div class="rounded-3xl border border-white/15 bg-white/5 overflow-hidden">
      <div class="grid grid-cols-7 bg-white/10 border-b border-white/15">
        @foreach($dayNames as $dn)
          <div class="px-3 py-3 text-xs font-bold text-white/80">{{ $dn }}</div>
        @endforeach
      </div>

      <div class="grid grid-cols-7">
        @foreach($days as $d)
          @php
            /** @var \Carbon\Carbon $d */
            $dateKey = $d->copy()->timezone($tz)->toDateString();
            $inMonth = $d->month === $current->month;
            $hasEvents = isset($byDate[$dateKey]) && count($byDate[$dateKey]) > 0;
            $isToday = $d->isToday();
          @endphp

          <div class="min-h-[92px] border-b border-white/10 border-r border-white/10 p-2">
            <div class="flex items-center justify-between">
              <div class="text-xs font-semibold {{ $inMonth ? 'text-white' : 'text-white/35' }}">
                {{ $d->day }}
              </div>

              @if($hasEvents)
                <span class="h-2.5 w-2.5 rounded-full bg-[#E7B14B]" title="Ada kegiatan"></span>
              @endif
            </div>

            @if($isToday)
              <div class="mt-1 inline-flex rounded-full bg-white/10 border border-white/15 px-2 py-0.5 text-[10px] font-semibold text-white/85">
                Hari ini
              </div>
            @endif

            @if($hasEvents)
              @php $first = $byDate[$dateKey][0]; @endphp
              <a href="{{ route('kegiatan.show', $first) }}"
                 class="mt-2 block rounded-xl bg-white/10 hover:bg-white/15 border border-white/10 px-2 py-1 text-[11px] font-semibold text-white line-clamp-2">
                {{ $first->nama_kegiatan ?? 'Kegiatan' }}
              </a>

              @if(count($byDate[$dateKey]) > 1)
                <div class="mt-1 text-[10px] text-white/60">
                  +{{ count($byDate[$dateKey]) - 1 }} kegiatan lain
                </div>
              @endif
            @endif
          </div>
        @endforeach
      </div>
    </div>

    {{-- List kegiatan (harus sama dengan mark karena source-nya $events yang sama) --}}
    <div class="rounded-3xl border border-white/15 bg-white/5 p-5">
      <div>
        <h2 class="text-lg font-extrabold">Daftar Kegiatan (Range Kalender)</h2>
        <p class="text-sm text-white/70">Ini diambil dari query yang sama dengan kalender</p>
      </div>

      <div class="mt-4 grid gap-3 md:grid-cols-2">
        @forelse(($events ?? []) as $k)
          <a href="{{ route('kegiatan.show', $k) }}"
             class="rounded-2xl border border-white/15 bg-white/5 hover:bg-white/10 p-4">
            <div class="text-xs text-white/60">
              {{ optional($k->tanggal_mulai)->timezone($tz)->locale('id')->translatedFormat('d M Y • H:i') ?? '-' }} WITA
            </div>
            <div class="mt-1 font-bold">{{ $k->nama_kegiatan ?? 'Kegiatan' }}</div>
            <div class="mt-1 text-sm text-white/70 line-clamp-2">
              {{ \Illuminate\Support\Str::limit(strip_tags($k->deskripsi ?? ''), 120) }}
            </div>
          </a>
        @empty
          <div class="text-white/70 text-sm">
            Belum ada kegiatan di range ini.
          </div>
        @endforelse
      </div>
    </div>

  </div>
</div>
</x-front-layout>
