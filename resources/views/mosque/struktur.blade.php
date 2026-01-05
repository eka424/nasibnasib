@extends('layouts.app')

@section('content')
<style>
  .page{
    min-height: 100vh;
    padding: 18px 14px;
    background: #0b5a18;
    display:flex;
    justify-content:center;
    align-items:flex-start;
  }
  .card{
    width: min(1250px, 98vw);
    border-radius: 18px;
    overflow:hidden;
    border: 1px solid rgba(255,255,255,.18);
    background: rgba(255,255,255,.06);
    backdrop-filter: blur(10px);
    box-shadow: 0 18px 48px rgba(0,0,0,.30);
  }
  .head{
    padding: 14px 18px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    color:#eaffea;
    font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial;
  }
  .imgwrap{ padding: 12px; }
  .imgwrap img{
    width: 100%;
    height: auto;
    display:block;
    border-radius: 14px;
    background: rgba(0,0,0,.12);
    border: 1px solid rgba(255,255,255,.12);
  }
  .btn{
    color:#ecffec;
    font-weight:800;
    text-decoration:none;
    padding:10px 12px;
    border-radius:12px;
    background: rgba(0,0,0,.20);
    border:1px solid rgba(255,255,255,.20);
  }
</style>

<div class="page">
  <div class="card">
    <div class="head">
      <div>
        <div style="font-size:22px;font-weight:900;letter-spacing:.8px;text-transform:uppercase;">
          Struktur Organisasi
        </div>
        <div style="font-size:12px;font-weight:800;opacity:.95;text-transform:uppercase;margin-top:6px;">
          Dewan Kemakmuran Masjid Agung Al-A’la Kabupaten Gianyar
        </div>
      </div>

      <a class="btn" href="{{ route('mosque.profile') }}">← Kembali</a>
    </div>

    <div class="imgwrap">
      <img src="{{ asset('images/struktur.jpg') }}" alt="Struktur Organisasi Masjid">
    </div>
  </div>
</div>
@endsection
