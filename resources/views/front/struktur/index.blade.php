<x-front-layout>
@php
  $gold = '#E7B14B';
@endphp

<div class="min-h-screen w-full overflow-x-hidden bg-[#13392f] text-white pb-24">
  <div class="mx-auto max-w-6xl px-4 pt-24 sm:px-6 lg:px-8">

    <div class="rounded-[28px] border border-white/15 bg-white/5 p-6 sm:p-8">
      <p class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Profil Masjid</p>
      <h1 class="mt-2 text-3xl font-extrabold">Struktur Pengurus</h1>

      @if($term)
        <div class="mt-4 rounded-2xl border border-white/15 bg-white/5 p-4">
          <div class="text-sm font-semibold text-white">{{ $term->decision_title ?? $term->title }}</div>

          <div class="mt-1 text-sm text-white/70">
            Nomor: <span class="font-semibold text-white">{{ $term->decision_number ?? '-' }}</span>
            <span class="mx-2 text-white/20">•</span>
            Masa Khidmat: <span class="font-semibold text-white">{{ $term->period_label ?? '-' }}</span>
          </div>

          <div class="mt-1 text-xs text-white/60">
            Ditetapkan di: {{ $term->location ?? '-' }},
            {{ $term->decision_date_hijri ?? '-' }}
            /
            {{ optional($term->decision_date_masehi)->format('d-m-Y') ?? '-' }}
          </div>
        </div>
      @else
        <div class="mt-4 rounded-2xl border border-white/15 bg-white/5 p-4 text-sm text-white/70">
          Struktur pengurus belum dipublish.
        </div>
      @endif
    </div>

    <div class="mt-6 space-y-5">
      @forelse($units as $u)
        <section class="rounded-[28px] border border-white/15 bg-white/5 p-6">
          <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl font-extrabold">{{ $u->name }}</h2>

            {{-- Tailwind aman: pakai style inline --}}
            <span style="background: {{ $gold }};"
                  class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold text-[#13392f]">
              {{ strtoupper($u->type ?? '-') }}
            </span>
          </div>

          {{-- positions --}}
          @if(($u->positions?->count() ?? 0) > 0)
            <div class="mt-4 space-y-3">
              @foreach($u->positions as $p)
                <div class="rounded-2xl border border-white/10 bg-black/25 p-4">
                  <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="font-semibold">
                      {{ $p->title }}
                      @if($p->notes)
                        <span class="text-white/60 font-medium">— {{ $p->notes }}</span>
                      @endif
                    </div>
                  </div>

                  @if(($p->members?->count() ?? 0) > 0)
                    <ol class="mt-3 list-decimal pl-5 text-sm text-white/85 space-y-1">
                      @foreach($p->members as $m)
                        <li>{{ $m->name }}</li>
                      @endforeach
                    </ol>
                  @endif
                </div>
              @endforeach
            </div>
          @endif

          {{-- children sections --}}
          @if(($u->children?->count() ?? 0) > 0)
            <div class="mt-5 space-y-4">
              @foreach($u->children as $c)
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                  <h3 class="text-lg font-extrabold">— {{ $c->name }}</h3>

                  @if(($c->positions?->count() ?? 0) > 0)
                    <div class="mt-3 space-y-3">
                      @foreach($c->positions as $p)
                        <div class="rounded-2xl border border-white/10 bg-black/25 p-4">
                          <div class="font-semibold">
                            {{ $p->title }}
                            @if($p->notes)
                              <span class="text-white/60 font-medium">— {{ $p->notes }}</span>
                            @endif
                          </div>

                          @if(($p->members?->count() ?? 0) > 0)
                            <ol class="mt-3 list-decimal pl-5 text-sm text-white/85 space-y-1">
                              @foreach($p->members as $m)
                                <li>{{ $m->name }}</li>
                              @endforeach
                            </ol>
                          @endif
                        </div>
                      @endforeach
                    </div>
                  @endif
                </div>
              @endforeach
            </div>
          @endif

        </section>
      @empty
        <div class="mt-6 rounded-2xl border border-white/15 bg-white/5 p-4 text-sm text-white/70">
          Belum ada unit struktur.
        </div>
      @endforelse
    </div>

  </div>
</div>
</x-front-layout>
