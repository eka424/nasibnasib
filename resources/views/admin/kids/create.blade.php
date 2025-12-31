@extends('layouts.app')

@section('content')
<div class="-m-6">
  <div class="flex min-h-screen w-full flex-col bg-slate-100">
    <div class="flex flex-col sm:gap-4 sm:py-4">

      {{-- HEADER --}}
      <header class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-white px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
        <div class="flex w-full flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Konten Ramah Anak</h1>
            <p class="text-gray-600 text-sm">
              Video YouTube • Dongeng PDF • Kata-kata Islami
            </p>
          </div>

          <a href="{{ route('admin.kids.index') }}"
             class="inline-flex items-center rounded-md border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
            ← Kembali
          </a>
        </div>
      </header>

      {{-- FORM --}}
      <main class="flex-1 p-4 sm:px-6 sm:py-0">
        <div class="max-w-3xl rounded-xl border border-slate-200 bg-white shadow-sm">

          <form method="POST" action="{{ route('admin.kids.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            {{-- TYPE --}}
<select name="type" required
        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900/10">
  <option value="">-- Pilih --</option>
  <option value="video">Video YouTube</option>
  <option value="story">Dongeng (PDF)</option>
  <option value="quote">Kata-kata Islami</option>
</select>


            {{-- TITLE --}}
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Judul
              </label>
              <input type="text" name="title"
                     class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900/10"
                     placeholder="Contoh: Kisah Nabi Ibrahim">
            </div>

            {{-- DESCRIPTION --}}
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Deskripsi
              </label>
              <textarea name="description" rows="3"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900/10"
                        placeholder="Deskripsi singkat (opsional)"></textarea>
            </div>

            {{-- YOUTUBE --}}
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                YouTube URL / ID <span class="text-xs text-slate-500">(untuk video)</span>
              </label>
              <input type="text" name="youtube_url"
                     class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900/10"
                     placeholder="https://youtube.com/watch?v=xxxx atau xxxx">
            </div>

            {{-- PDF --}}
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Upload PDF Dongeng
              </label>
              <input type="file" name="file_pdf" accept="application/pdf"
                     class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
              <p class="text-xs text-slate-500 mt-1">
                Khusus untuk tipe Dongeng
              </p>
            </div>

            {{-- QUOTE --}}
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">
                Kata-kata Islami <span class="text-xs text-slate-500">(untuk tipe kata)</span>
              </label>
              <textarea name="quote_text" rows="3"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-slate-900/10"
                        placeholder="Contoh: Allah menyukai anak yang jujur."></textarea>
            </div>

            {{-- PUBLISH --}}
            <div class="flex items-center gap-2">
              <input type="checkbox" name="is_published" value="1" checked
                     class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
              <label class="text-sm text-slate-700">Publish sekarang</label>
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-2 pt-4 border-t">
              <a href="{{ route('admin.kids.index') }}"
                 class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                Batal
              </a>
              <button type="submit"
                      class="rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                Simpan
              </button>
            </div>

          </form>
        </div>
      </main>

    </div>
  </div>
</div>
@endsection
