{{-- resources/views/mosque/struktur.blade.php --}}
@extends('layouts.app')

@section('content')
@php
$findByJabatan = function ($node, string $needle, string $mode = 'exact') use (&$findByJabatan) {
    if (!$node) return null;

    $jab = trim((string) ($node->jabatan ?? ''));
    $ok = $mode === 'contains'
        ? (stripos($jab, $needle) !== false)
        : (strcasecmp($jab, $needle) === 0);

    if ($ok) return $node;

    foreach (($node->children ?? []) as $ch) {
        $hit = $findByJabatan($ch, $needle, $mode);
        if ($hit) return $hit;
    }
    return null;
};

$wrapLines = function (?string $text, int $maxLen = 22, int $maxLines = 3) {
    $text = trim((string) $text);
    if ($text === '') return [];
    $text = preg_replace('/\s+/', ' ', $text);

    $seps = [' & ', ', ', ' - ', ' dan ', ' DAN '];
    foreach ($seps as $sep) {
        if (stripos($text, $sep) !== false) {
            $parts = array_values(array_filter(array_map('trim', explode($sep, $text))));
            if (count($parts) > 1) return array_slice($parts, 0, $maxLines);
        }
    }

    $words = preg_split('/\s+/', $text) ?: [];
    $lines = [];
    $cur = '';
    foreach ($words as $w) {
        $try = $cur === '' ? $w : ($cur.' '.$w);
        if (mb_strlen($try) <= $maxLen) $cur = $try;
        else {
            if ($cur !== '') $lines[] = $cur;
            $cur = $w;
            if (count($lines) >= $maxLines - 1) break;
        }
    }
    if ($cur !== '' && count($lines) < $maxLines) $lines[] = $cur;
    return $lines;
};

// DATA
$rootTitle = $root->jabatan ?? 'Dewan Kemakmuran Masjid';
$rootSubtitle = $root->nama ?? '';

$mustasyar = $findByJabatan($root, 'Mustasyar', 'exact');
$dewanSyuro = $findByJabatan($root, 'Dewan Syuro', 'exact');
$ketuaUmum  = $dewanSyuro ? $findByJabatan($dewanSyuro, 'Ketua Umum', 'exact') : null;
$wakilKetua = $ketuaUmum ? $findByJabatan($ketuaUmum, 'Wakil Ketua Umum', 'exact') : null;
$sekum      = $wakilKetua ? $findByJabatan($wakilKetua, 'Sekretaris Umum', 'exact') : null;
$bendum     = $wakilKetua ? $findByJabatan($wakilKetua, 'Bendahara Umum', 'exact') : null;

$bidIdarah = $sekum ? $findByJabatan($sekum, 'Ketua Bid. Idarah', 'contains') : null;
$bidImarah = $sekum ? $findByJabatan($sekum, 'Ketua Bid. Imarah', 'contains') : null;
$bidRiayah = $sekum ? $findByJabatan($sekum, 'Ketua Bid. Riayah', 'contains') : null;

$sekIdarah  = $bidIdarah ? $findByJabatan($bidIdarah, 'Sek. Bid. Idarah', 'contains') : null;
$bendIdarah = $bidIdarah ? $findByJabatan($bidIdarah, 'Bend. Bid. Idarah', 'contains') : null;

$sekImarah  = $bidImarah ? $findByJabatan($bidImarah, 'Sek. Bid. Imarah', 'contains') : null;
$bendImarah = $bidImarah ? $findByJabatan($bidImarah, 'Bend. Bid. Imarah', 'contains') : null;

$sekRiayah  = $bidRiayah
    ? ($findByJabatan($bidRiayah, "Sek. Bid. Ri'ayah", 'contains') ?? $findByJabatan($bidRiayah, "Sek. Bid. Riayah", 'contains'))
    : null;

$getSeksi = function ($bidang) {
    if (!$bidang) return collect();
    return collect($bidang->children ?? [])
        ->filter(fn($n) => stripos($n->jabatan ?? '', 'Ketua Seksi') === 0)
        ->values();
};

$seksiIdarah = $getSeksi($bidIdarah)->take(2);
$seksiImarah = $getSeksi($bidImarah)->take(5);
$seksiRiayah = $getSeksi($bidRiayah)->take(2);

$lembaga = $findByJabatan($root, 'Lembaga', 'exact');
$banom   = $findByJabatan($root, 'Banom', 'exact');
$itemsLembaga = collect($lembaga?->children ?? [])->take(3);
$itemsBanom   = collect($banom?->children ?? [])->take(7);

$backUrl = route('mosque.profile');
@endphp

<style>
  :root{
    --g1:#064a16;
    --g2:#0f7a2a;
    --t1:#2fb7a6;
  }

  .org-page{
    min-height: 100vh;
    padding: 18px 14px;
    background:
      radial-gradient(900px 520px at 15% 25%, rgba(255,255,255,.10), transparent 62%),
      radial-gradient(700px 420px at 80% 20%, rgba(255,255,255,.08), transparent 60%),
      radial-gradient(800px 520px at 65% 85%, rgba(0,0,0,.20), transparent 60%),
      linear-gradient(90deg, var(--g1) 0%, var(--g2) 50%, var(--t1) 100%);
    display:flex;
    justify-content:center;
    align-items:flex-start;
  }

  .org-card{
    width: min(1250px, 98vw);
    border-radius: 18px;
    overflow:hidden;
    border: 1px solid rgba(255,255,255,.18);
    background: rgba(255,255,255,.06);
    backdrop-filter: blur(10px);
    box-shadow: 0 18px 48px rgba(0,0,0,.30);
  }

  .org-head{
    padding: 14px 18px 10px;
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap: 12px;
    color:#eaffea;
    font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial;
  }
  .org-head h1{
    margin:0;
    font-size: 22px;
    font-weight: 900;
    letter-spacing:.8px;
    text-transform: uppercase;
  }
  .org-head h2{
    margin:6px 0 0;
    font-size: 12px;
    font-weight: 800;
    opacity:.95;
    text-transform: uppercase;
  }

  .back-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:10px 12px;
    border-radius:12px;
    background: rgba(0,0,0,.20);
    color:#ecffec;
    text-decoration:none;
    font-weight:800;
    border:1px solid rgba(255,255,255,.20);
    white-space:nowrap;
    transition:.15s ease;
  }
  .back-btn:hover{ transform: translateY(-1px); background: rgba(0,0,0,.28); }

  .org-body{ padding: 12px 14px 16px; }

  .content-grid{
    display:grid;
    grid-template-columns: 1fr 260px;
    gap: 12px;
    align-items:start;
  }
  @media (max-width: 980px){
    .content-grid{ grid-template-columns: 1fr; }
  }

  .svg-stage{
    border-radius: 16px;
    background: rgba(0,0,0,.12);
    border: 1px solid rgba(255,255,255,.12);
    padding: 12px;
  }
  .svg-fit{
    display:block;
    width: 100%;
    height: auto;
  }

  .legend{
    border-radius: 16px;
    background: rgba(0,0,0,.12);
    border: 1px solid rgba(255,255,255,.12);
    padding: 12px;
    color:#eaffea;
    font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial;
  }
  .legend h3{
    margin: 0 0 10px;
    font-size: 13px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing:.6px;
  }
  .legend-item{
    display:flex;
    align-items:center;
    gap:10px;
    padding: 8px 0;
    border-bottom: 1px solid rgba(255,255,255,.10);
  }
  .legend-item:last-child{ border-bottom:none; }
  .legend-item span{
    font-size: 12px;
    font-weight: 800;
    opacity:.95;
    text-transform: uppercase;
  }
  .line-sample{
    width: 64px;
    height: 0;
    border-top: 4px solid #0c0c0c;
    border-radius: 999px;
    flex: 0 0 auto;
  }
  .line-dashed{ border-top-style: dashed; }
  .line-red{ border-top-color:#d21c1c; }
  .line-blue{ border-top-color:#1d49ff; }
</style>

<div class="org-page">
  <div class="org-card">
    <div class="org-head">
      <div>
        <h1>STRUKTUR ORGANISASI</h1>
        <h2>{{ $rootTitle }} @if($rootSubtitle) — {{ $rootSubtitle }} @endif</h2>
      </div>
      <a class="back-btn" href="{{ $backUrl }}">← Kembali</a>
    </div>

    <div class="org-body">
      <div class="content-grid">
        {{-- BAGAN --}}
        <div class="svg-stage">
          <svg class="svg-fit" viewBox="0 0 1280 760" preserveAspectRatio="xMidYMid meet" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <filter id="shadow" x="-30%" y="-30%" width="160%" height="160%">
                <feDropShadow dx="0" dy="2" stdDeviation="2" flood-color="#000" flood-opacity=".35"/>
              </filter>

              <style>
                .box { filter:url(#shadow); }
                .white { fill:#ffffff; stroke:#111; stroke-width:2; rx:10; ry:10; }
                .greenPanel { fill:#0f6b17; stroke:#083c0d; stroke-width:2; rx:12; ry:12; }
                .darkGreen { fill:#0c5a12; stroke:#083c0d; stroke-width:2; rx:10; ry:10; }

                .titleText { font: 900 20px ui-sans-serif,system-ui,Segoe UI,Roboto,Arial; fill:#111; text-transform:uppercase; letter-spacing:.6px;}
                .subText { font: 900 12px ui-sans-serif,system-ui,Segoe UI,Roboto,Arial; fill:#111; text-transform:uppercase; }
                .panelText { font: 900 18px ui-sans-serif,system-ui,Segoe UI,Roboto,Arial; fill:#dfffdf; text-transform:uppercase; letter-spacing:.8px;}
                .panelItem { font: 900 15px ui-sans-serif,system-ui,Segoe UI,Roboto,Arial; fill:#0c240f; text-transform:uppercase;}
                .seksiText { font: 900 12px ui-sans-serif,system-ui,Segoe UI,Roboto,Arial; fill:#dfffdf; text-transform:uppercase; }
                .jamaahText{ font: 900 16px ui-sans-serif,system-ui,Segoe UI,Roboto,Arial; fill:#111; text-transform:uppercase; letter-spacing:.6px;}

                .cmd { stroke:#0c0c0c; stroke-width:4; fill:none; }
                .coord { stroke:#0c0c0c; stroke-width:4; stroke-dasharray:10 10; fill:none; }
                .secLine { stroke:#d21c1c; stroke-width:4; fill:none; }
                .bendLine{ stroke:#1d49ff; stroke-width:4; fill:none; }
              </style>
            </defs>

            {{-- LOGO placeholder --}}
            <g transform="translate(55,95)">
              <rect class="white box" x="0" y="0" width="120" height="120" rx="18" ry="18"/>
              <text x="60" y="70" text-anchor="middle" class="subText">LOGO</text>
            </g>

            {{-- MUSTASYAR & DEWAN SYURO --}}
            <g class="box">
              <rect class="white" x="410" y="90" width="190" height="44"/>
              <text class="titleText" x="505" y="119" text-anchor="middle">{{ $mustasyar->jabatan ?? 'MUSTASYAR' }}</text>

              <rect class="darkGreen" x="620" y="90" width="190" height="44"/>
              <text class="panelText" x="715" y="119" text-anchor="middle">{{ $dewanSyuro->jabatan ?? 'DEWAN SYURO' }}</text>
            </g>
            <path class="coord" d="M600 112 H620"/>

            {{-- KETUA UMUM + WAKIL --}}
            <g class="box">
              <rect class="white" x="545" y="150" width="190" height="48"/>
              <text class="titleText" x="640" y="175" text-anchor="middle">{{ $ketuaUmum->jabatan ?? 'KETUA UMUM' }}</text>
              @if(!empty($ketuaUmum?->nama))
                <text class="subText" x="640" y="192" text-anchor="middle">{{ $ketuaUmum->nama }}</text>
              @endif

              <rect class="white" x="545" y="208" width="190" height="42"/>
              <text class="subText" x="640" y="235" text-anchor="middle">{{ $wakilKetua->jabatan ?? 'WAKIL KETUA UMUM' }}</text>
            </g>
            <path class="cmd" d="M640 198 V208"/>
            <path class="cmd" d="M640 250 V272"/>

            {{-- SEKUM & BENDUM --}}
            <g class="box">
              <rect class="white" x="385" y="272" width="260" height="44"/>
              <text class="titleText" x="515" y="301" text-anchor="middle">{{ $sekum->jabatan ?? 'SEKRETARIS UMUM' }}</text>

              <rect class="white" x="675" y="272" width="260" height="44"/>
              <text class="titleText" x="805" y="301" text-anchor="middle">{{ $bendum->jabatan ?? 'BENDAHARA UMUM' }}</text>
            </g>

            <path class="cmd" d="M250 345 H1030"/>
            <path class="secLine" d="M340 272 V345 H640"/>
            <path class="bendLine" d="M940 272 V345 H640"/>

            {{-- LEMBAGA --}}
            <g class="box">
              <rect class="greenPanel" x="90" y="342" width="180" height="58"/>
              <text class="panelText" x="180" y="378" text-anchor="middle">{{ $lembaga->jabatan ?? 'LEMBAGA' }}</text>

              @foreach($itemsLembaga as $i => $item)
                <rect class="darkGreen box" x="90" y="{{ 412 + ($i*55) }}" width="180" height="44" rx="10" ry="10"/>
                <text class="panelItem" x="180" y="{{ 440 + ($i*55) }}" text-anchor="middle">{{ $item->jabatan }}</text>
              @endforeach
            </g>
            <path class="coord" d="M270 371 H385"/>

            {{-- BANOM --}}
            <g class="box">
              <rect class="greenPanel" x="1030" y="342" width="180" height="58"/>
              <text class="panelText" x="1120" y="378" text-anchor="middle">{{ $banom->jabatan ?? 'BANOM' }}</text>

              @foreach($itemsBanom as $i => $item)
                <rect class="darkGreen box" x="1030" y="{{ 412 + ($i*48) }}" width="180" height="40" rx="10" ry="10"/>
                <text class="panelItem" x="1120" y="{{ 438 + ($i*48) }}" text-anchor="middle">{{ $item->jabatan }}</text>
              @endforeach
            </g>
            <path class="coord" d="M935 371 H1030"/>

            {{-- 3 BIDANG (FIX: tidak overlap) --}}
            @php
              $idX = 300;  $idW = 230;  $idC = $idX + ($idW/2); // 415
              $imX = 540;  $imW = 230;  $imC = $imX + ($imW/2); // 655
              $riX = 780;  $riW = 230;  $riC = $riX + ($riW/2); // 895
            @endphp

            <g class="box">
              <rect class="white" x="{{ $idX }}" y="360" width="{{ $idW }}" height="44"/>
              <text class="subText" x="{{ $idC }}" y="387" text-anchor="middle">{{ $bidIdarah->jabatan ?? 'KETUA BID. IDARAH' }}</text>

              <rect class="white" x="{{ $idX }}" y="410" width="{{ $idW }}" height="40"/>
              <text class="subText" x="{{ $idC }}" y="435" text-anchor="middle">{{ $sekIdarah->jabatan ?? 'SEK. BID. IDARAH' }}</text>

              <rect class="white" x="{{ $idX }}" y="458" width="{{ $idW }}" height="40"/>
              <text class="subText" x="{{ $idC }}" y="483" text-anchor="middle">{{ $bendIdarah->jabatan ?? 'BEND. BID. IDARAH' }}</text>
            </g>

            <g class="box">
              <rect class="white" x="{{ $imX }}" y="360" width="{{ $imW }}" height="44"/>
              <text class="subText" x="{{ $imC }}" y="387" text-anchor="middle">{{ $bidImarah->jabatan ?? 'KETUA BID. IMARAH' }}</text>

              <rect class="white" x="{{ $imX }}" y="410" width="{{ $imW }}" height="40"/>
              <text class="subText" x="{{ $imC }}" y="435" text-anchor="middle">{{ $sekImarah->jabatan ?? 'SEK. BID. IMARAH' }}</text>

              <rect class="white" x="{{ $imX }}" y="458" width="{{ $imW }}" height="40"/>
              <text class="subText" x="{{ $imC }}" y="483" text-anchor="middle">{{ $bendImarah->jabatan ?? 'BEND. BID. IMARAH' }}</text>
            </g>

            <g class="box">
              <rect class="white" x="{{ $riX }}" y="360" width="{{ $riW }}" height="44"/>
              <text class="subText" x="{{ $riC }}" y="387" text-anchor="middle">{{ $bidRiayah->jabatan ?? "KETUA BID. RI’AYAH" }}</text>

              <rect class="white" x="{{ $riX }}" y="410" width="{{ $riW }}" height="40"/>
              <text class="subText" x="{{ $riC }}" y="435" text-anchor="middle">{{ $sekRiayah->jabatan ?? "SEK. BID. RI’AYAH" }}</text>
            </g>

            <path class="cmd" d="M{{ $idC }} 345 V360"/>
            <path class="cmd" d="M{{ $imC }} 345 V360"/>
            <path class="cmd" d="M{{ $riC }} 345 V360"/>

            <path class="coord" d="M{{ $idX + $idW }} 382 H{{ $riX }}"/>

            {{-- SEKSI (FIX: ikut center bidang, biar nggak nabrak & lebih rapi) --}}

            {{-- IDARAH: center = $idC --}}
            <g class="box">
              @foreach($seksiIdarah as $i => $sx)
                @php
                  $label = trim(str_ireplace('Ketua Seksi', '', $sx->jabatan));
                  $lines = $wrapLines($label, 24, 3);
                  $y = 535 + ($i*66);
                @endphp
                <rect class="darkGreen" x="250" y="{{ $y }}" width="330" height="56"/>
                <text class="seksiText" x="{{ $idC }}" y="{{ $y + 22 }}" text-anchor="middle">KETUA SEKSI</text>
                <text class="seksiText" x="{{ $idC }}" y="{{ $y + 40 }}" text-anchor="middle">
                  @foreach($lines as $li => $ln)
                    <tspan x="{{ $idC }}" dy="{{ $li === 0 ? 0 : 14 }}">{{ $ln }}</tspan>
                  @endforeach
                </text>
              @endforeach
            </g>

            {{-- IMARAH: center = $imC --}}
            <g class="box">
              @foreach($seksiImarah as $i => $sx)
                @php
                  $label = trim(str_ireplace('Ketua Seksi', '', $sx->jabatan));
                  $lines = $wrapLines($label, 22, 2);
                  $y = 535 + ($i*50);
                @endphp
                <rect class="darkGreen" x="535" y="{{ $y }}" width="240" height="44"/>
                <text class="seksiText" x="{{ $imC }}" y="{{ $y + 18 }}" text-anchor="middle">KETUA SEKSI</text>
                <text class="seksiText" x="{{ $imC }}" y="{{ $y + 34 }}" text-anchor="middle">
                  @foreach($lines as $li => $ln)
                    <tspan x="{{ $imC }}" dy="{{ $li === 0 ? 0 : 14 }}">{{ $ln }}</tspan>
                  @endforeach
                </text>
              @endforeach
            </g>

            {{-- RIAYAH: center = $riC --}}
            <g class="box">
              @foreach($seksiRiayah as $i => $sx)
                @php
                  $label = trim(str_ireplace('Ketua Seksi', '', $sx->jabatan));
                  $lines = $wrapLines($label, 20, 3);
                  $y = 535 + ($i*66);
                @endphp
                <rect class="darkGreen" x="775" y="{{ $y }}" width="240" height="56"/>
                <text class="seksiText" x="{{ $riC }}" y="{{ $y + 22 }}" text-anchor="middle">KETUA SEKSI</text>
                <text class="seksiText" x="{{ $riC }}" y="{{ $y + 40 }}" text-anchor="middle">
                  @foreach($lines as $li => $ln)
                    <tspan x="{{ $riC }}" dy="{{ $li === 0 ? 0 : 14 }}">{{ $ln }}</tspan>
                  @endforeach
                </text>
              @endforeach
            </g>

            {{-- garis turun ke seksi (pakai center bidang baru) --}}
            <path class="cmd" d="M{{ $idC }} 498 V535"/>
            <path class="cmd" d="M{{ $imC }} 498 V535"/>
            <path class="cmd" d="M{{ $riC }} 450 V535"/>

            {{-- JAMAAH --}}
            <g class="box">
              <rect class="white" x="330" y="715" width="620" height="40" rx="12" ry="12"/>
              <text class="jamaahText" x="640" y="742" text-anchor="middle">
                JAMAA’AH {{ $rootSubtitle ? $rootSubtitle : 'MASJID' }}
              </text>
            </g>

            <path class="cmd" d="M250 345 V732 H330"/>
            <path class="cmd" d="M1030 345 V732 H950"/>
          </svg>
        </div>

        {{-- SIDEBAR KETERANGAN --}}
        <aside class="legend">
          <h3>Keterangan</h3>

          <div class="legend-item">
            <div class="line-sample"></div>
            <span>Garis Komando</span>
          </div>

          <div class="legend-item">
            <div class="line-sample line-dashed"></div>
            <span>Garis Koordinasi</span>
          </div>

          <div class="legend-item">
            <div class="line-sample line-red"></div>
            <span>Komando Sekretaris</span>
          </div>

          <div class="legend-item">
            <div class="line-sample line-blue"></div>
            <span>Komando Bendahara</span>
          </div>
        </aside>
      </div>
    </div>
  </div>
</div>
@endsection
