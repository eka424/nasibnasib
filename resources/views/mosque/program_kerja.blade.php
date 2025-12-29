@extends('layouts.app')

@section('content')
<style>
  :root{
    /* ✅ TOSCA sesuai gambar kamu */
    --tosca: #70978C;     /* ini yang kamu kirim */
    --tosca2:#86AEA3;     /* sedikit lebih terang */
    --tosca3:#5B8076;     /* sedikit lebih gelap */

    /* ✅ HIJAU GELAP buat konten */
    --dark1:#063b19;
    --dark2:#0a5a26;

    --line: rgba(255,255,255,.14);
    --text: #eaffea;
    --muted: rgba(234,255,234,.88);
  }

  .page{
    min-height: 100vh;
    padding: 18px 14px;

    /* ✅ Background tosca (dominant) */
    background:
      radial-gradient(900px 520px at 15% 25%, rgba(255,255,255,.16), transparent 62%),
      radial-gradient(800px 520px at 85% 20%, rgba(255,255,255,.10), transparent 60%),
      radial-gradient(900px 600px at 65% 90%, rgba(0,0,0,.16), transparent 62%),
      linear-gradient(120deg, var(--tosca3) 0%, var(--tosca) 45%, var(--tosca2) 100%);

    display:flex;
    justify-content:center;
    align-items:flex-start;
    font-family: system-ui, Segoe UI, Roboto, Arial;
  }

  .card{
    width:min(1200px, 98vw);
    border-radius:18px;
    overflow:hidden;
    border:1px solid rgba(255,255,255,.18);
    background: rgba(0,0,0,.08);
    backdrop-filter: blur(10px);
    box-shadow: 0 18px 48px rgba(0,0,0,.30);
    color: var(--text);
  }

  .head{
    padding:14px 18px 10px;
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:12px;

    /* ✅ header hijau gelap */
    background: linear-gradient(90deg, rgba(6,59,25,.88) 0%, rgba(10,90,38,.78) 60%, rgba(0,0,0,.16) 100%);
    border-bottom: 1px solid var(--line);
  }

  .head h1{
    margin:0;
    font-size:22px;
    font-weight:900;
    text-transform:uppercase;
    letter-spacing:.8px;
  }

  .head p{
    margin:6px 0 0;
    font-size:12px;
    font-weight:800;
    opacity:.95;
    text-transform:uppercase;
  }

  .btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:10px 12px;
    border-radius:12px;
    background: rgba(0,0,0,.22);
    color: var(--text);
    text-decoration:none;
    font-weight:800;
    border:1px solid rgba(255,255,255,.20);
    white-space:nowrap;
    transition:.15s ease;
  }
  .btn:hover{ transform: translateY(-1px); background: rgba(0,0,0,.30); }

  .body{ padding: 12px 14px 18px; }

  .section{
    border-radius:16px;
    overflow:hidden;
    border:1px solid rgba(255,255,255,.14);

    /* ✅ konten hijau gelap */
    background: linear-gradient(180deg, rgba(6,59,25,.72) 0%, rgba(6,59,25,.58) 100%);
    margin-bottom: 12px;
  }

  .section-title{
    padding: 10px 12px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing:.5px;
    background: rgba(0,0,0,.25);
    border-bottom: 1px solid rgba(255,255,255,.10);
  }

  .part{
    padding: 10px 12px;
    border-top: 1px solid rgba(255,255,255,.08);
    background: rgba(0,0,0,.10);
  }

  .part h4{
    margin:0 0 8px;
    font-size: 13px;
    font-weight: 900;
    text-transform: uppercase;
    color: var(--text);
  }

  .items{
    margin:0;
    padding-left: 18px;
    color: var(--muted);
    line-height: 1.6;
  }

  .items li{ margin: 6px 0; }
</style>


<div class="page">
  <div class="card">
    <div class="head">
      <div>
        <h1>PROGRAM KERJA</h1>
        <p>Dewan Kemakmuran Masjid — Masjid Agung Al-A’la Kabupaten Gianyar</p>
      </div>
      <a class="btn" href="{{ route('mosque.profile') }}">← Kembali</a>
    </div>

    <div class="body">
      @if($sections->isEmpty())
        <div class="section" style="padding:14px;">Belum ada data program kerja.</div>
      @else
        @foreach($sections as $sec)
          <div class="section">
            <div class="section-title">{{ $sec->nama }}</div>

            @foreach($sec->parts as $idx => $part)
              <div class="part">
                <h4>{{ chr(97+$idx) }}. {{ $part->judul }}</h4>
                <ol class="items">
                  @foreach($part->items as $it)
                    <li>{{ $it->teks }}</li>
                  @endforeach
                </ol>
              </div>
            @endforeach
          </div>
        @endforeach
      @endif
    </div>
  </div>
</div>
@endsection
