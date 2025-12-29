@extends('layouts.app')

@section('content')
<style>
  .wp-card{ border-radius:14px; overflow:hidden; border:1px solid rgba(0,0,0,.08); }
  .wp-head{
    background: linear-gradient(90deg,#064a16 0%,#0f7a2a 50%,#2fb7a6 100%);
    color:#eaffea; padding:14px 16px;
  }
  .wp-head h3{ margin:0; font-weight:900; text-transform:uppercase; letter-spacing:.6px; }
  .wp-head p{ margin:6px 0 0; font-weight:700; opacity:.95; font-size:13px; }

  .sec-box{ border:1px solid rgba(0,0,0,.08); border-radius:14px; overflow:hidden; margin-bottom:14px; }
  .sec-title{
    display:flex; justify-content:space-between; align-items:center; gap:12px;
    background: rgba(15,122,42,.10);
    padding:10px 12px;
  }
  .sec-title .name{ font-weight:900; text-transform:uppercase; }
  .part-box{ border-top:1px solid rgba(0,0,0,.06); padding:10px 12px; background:#fff; }
  .part-label{ font-weight:900; text-transform:uppercase; font-size:13px; }
  .item-row{ display:flex; gap:8px; align-items:start; }
  .small-muted{ font-size:12px; color:#6c757d; }
</style>

<div class="container py-4">

  <div class="wp-card mb-4">
    <div class="wp-head">
      <h3>Admin • Program Kerja (All-in-One)</h3>
      <p>Tambah Seksi → Tambah Bagian (a,b,c) → Tambah Item (1,2,3)</p>
    </div>
    <div class="p-3 bg-white">
      @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger mb-3">
          <ul class="mb-0">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      {{-- FORM TAMBAH SEKSI --}}
      <form class="row g-2 align-items-end" method="POST" action="{{ route('admin.work_program.section.store') }}">
        @csrf
        <div class="col-md-6">
          <label class="form-label fw-bold">Tambah Seksi</label>
          <input name="nama" class="form-control" placeholder="Contoh: SEKSI PERIBADATAN" required>
        </div>
        <div class="col-md-2">
          <label class="form-label">Urutan</label>
          <input name="urutan" type="number" class="form-control" value="0" min="0">
        </div>
        <div class="col-md-2">
          <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
            <label class="form-check-label fw-bold">Aktif</label>
          </div>
        </div>
        <div class="col-md-2">
          <button class="btn btn-success w-100">Tambah</button>
        </div>
      </form>
    </div>
  </div>

  {{-- LIST SEKSI --}}
  @forelse($sections as $secIndex => $sec)
    <div class="sec-box">
      <div class="sec-title">
        <div>
          <div class="name">{{ $sec->nama }}</div>
          <div class="small-muted">
            Urutan: {{ $sec->urutan }} • Status:
            {!! $sec->is_active ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Off</span>' !!}
          </div>
        </div>

        <div style="min-width:420px;">
          {{-- UPDATE SEKSI --}}
          <form class="row g-2" method="POST" action="{{ route('admin.work_program.section.update', $sec) }}">
            @csrf
            @method('PUT')
            <div class="col-6">
              <input class="form-control form-control-sm" name="nama" value="{{ $sec->nama }}" required>
            </div>
            <div class="col-2">
              <input class="form-control form-control-sm" name="urutan" type="number" value="{{ $sec->urutan }}" min="0">
            </div>
            <div class="col-2 d-flex align-items-center">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $sec->is_active ? 'checked' : '' }}>
                <label class="form-check-label small">Aktif</label>
              </div>
            </div>
            <div class="col-2">
              <button class="btn btn-sm btn-primary w-100">Save</button>
            </div>
          </form>

          {{-- HAPUS SEKSI --}}
          <form method="POST" action="{{ route('admin.work_program.section.destroy', $sec) }}"
                onsubmit="return confirm('Hapus SEKSI ini? Semua bagian & item ikut terhapus.');" class="mt-2">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-danger w-100">Hapus Seksi</button>
          </form>
        </div>
      </div>

      {{-- FORM TAMBAH BAGIAN --}}
      <div class="part-box">
        <form class="row g-2 align-items-end" method="POST" action="{{ route('admin.work_program.part.store') }}">
          @csrf
          <input type="hidden" name="section_id" value="{{ $sec->id }}">
          <div class="col-md-8">
            <label class="form-label fw-bold">Tambah Bagian (a,b,c)</label>
            <input name="judul" class="form-control" placeholder="Contoh: MENYELENGGARAKAN KEGIATAN PERIBADATAN..." required>
          </div>
          <div class="col-md-2">
            <label class="form-label">Urutan</label>
            <input name="urutan" type="number" class="form-control" value="0" min="0">
          </div>
          <div class="col-md-2">
            <button class="btn btn-success w-100">Tambah</button>
          </div>
        </form>
      </div>

      {{-- LIST BAGIAN --}}
      @foreach($sec->parts as $partIndex => $part)
        <div class="part-box">
          <div class="d-flex justify-content-between gap-3 flex-wrap">
            <div style="flex:1;">
              <div class="part-label">{{ chr(97+$partIndex) }}. {{ $part->judul }}</div>
              <div class="small-muted">Urutan: {{ $part->urutan }}</div>
            </div>

            <div style="min-width:420px;">
              {{-- UPDATE BAGIAN --}}
              <form class="row g-2" method="POST" action="{{ route('admin.work_program.part.update', $part) }}">
                @csrf
                @method('PUT')
                <div class="col-8">
                  <input class="form-control form-control-sm" name="judul" value="{{ $part->judul }}" required>
                </div>
                <div class="col-2">
                  <input class="form-control form-control-sm" name="urutan" type="number" value="{{ $part->urutan }}" min="0">
                </div>
                <div class="col-2">
                  <button class="btn btn-sm btn-primary w-100">Save</button>
                </div>
              </form>

              {{-- HAPUS BAGIAN --}}
              <form method="POST" action="{{ route('admin.work_program.part.destroy', $part) }}"
                    onsubmit="return confirm('Hapus BAGIAN ini? Item di dalamnya ikut terhapus.');" class="mt-2">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger w-100">Hapus Bagian</button>
              </form>
            </div>
          </div>

          {{-- FORM TAMBAH ITEM --}}
          <div class="mt-3">
            <form class="row g-2 align-items-end" method="POST" action="{{ route('admin.work_program.item.store') }}">
              @csrf
              <input type="hidden" name="part_id" value="{{ $part->id }}">
              <div class="col-md-8">
                <label class="form-label fw-bold">Tambah Item (1,2,3)</label>
                <input name="teks" class="form-control" placeholder="Contoh: Pelaksanaan Sholat Maktubah berjama’ah" required>
              </div>
              <div class="col-md-2">
                <label class="form-label">Urutan</label>
                <input name="urutan" type="number" class="form-control" value="0" min="0">
              </div>
              <div class="col-md-2">
                <button class="btn btn-success w-100">Tambah</button>
              </div>
            </form>
          </div>

          {{-- LIST ITEM --}}
          <div class="mt-3">
            <ol class="mb-0">
              @foreach($part->items as $it)
                <li class="mb-2">
                  <div class="item-row">
                    <form class="d-flex gap-2 flex-wrap" method="POST" action="{{ route('admin.work_program.item.update', $it) }}">
                      @csrf
                      @method('PUT')
                      <input class="form-control form-control-sm" style="min-width:420px;" name="teks" value="{{ $it->teks }}" required>
                      <input class="form-control form-control-sm" style="width:90px;" name="urutan" type="number" value="{{ $it->urutan }}" min="0">
                      <button class="btn btn-sm btn-primary">Save</button>
                    </form>

                    <form method="POST" action="{{ route('admin.work_program.item.destroy', $it) }}"
                          onsubmit="return confirm('Hapus item ini?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">Hapus</button>
                    </form>
                  </div>
                </li>
              @endforeach
            </ol>
          </div>

        </div>
      @endforeach

    </div>
  @empty
    <div class="alert alert-info">Belum ada seksi. Tambahkan dulu di form atas.</div>
  @endforelse

</div>
@endsection
