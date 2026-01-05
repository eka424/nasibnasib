<x-app-layout>
    <div class="flex min-h-screen w-full flex-col bg-slate-100 rounded-3xl">
        <div class="flex flex-col sm:gap-4 sm:py-4">
            <header class="sticky top-0 z-20 flex h-14 items-center gap-4 border-b bg-white px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
                <div class="flex w-full flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Artikel & Konten</h1>
                        <p class="text-gray-600">Manajemen artikel dan konten masjid</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.artikels.index') }}"
                            class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 19A9 9 0 0 1 19 5" />
                            </svg>
                            Refresh
                        </a>
                    
                        <a href="{{ route('admin.artikels.create') }}"
                            class="inline-flex items-center gap-2 rounded-md bg-slate-900 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-slate-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8M8 12h8" />
                            </svg>
                            Buat Artikel
                        </a>
                    </div>
                </div>
            </header>

            <main class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
                <div class="grid auto-rows-max items-start gap-4 md:gap-8 lg:col-span-2">
                    <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                            <div class="flex flex-row items-center justify-between px-6 pb-2 pt-6">
                                <p class="text-sm font-medium text-slate-700">Total Artikel</p>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h8l4 4v14H7z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 3v4h4" />
                                </svg>
                            </div>
                            <div class="px-6 pb-6">
                                <div class="text-2xl font-bold">{{ $totalArtikel }}</div>
                                <p class="text-xs text-slate-500">Total konten tersimpan</p>
                            </div>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                            <div class="flex flex-row items-center justify-between px-6 pb-2 pt-6">
                                <p class="text-sm font-medium text-slate-700">Diterbitkan</p>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="px-6 pb-6">
                                <div class="text-2xl font-bold">{{ $publishedArtikel }}</div>
                                <p class="text-xs text-slate-500">Artikel tayang publik</p>
                            </div>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                            <div class="flex flex-row items-center justify-between px-6 pb-2 pt-6">
                                <p class="text-sm font-medium text-slate-700">Draft</p>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5M12 16h.01" />
                                </svg>
                            </div>
                            <div class="px-6 pb-6">
                                <div class="text-2xl font-bold">{{ $draftArtikel }}</div>
                                <p class="text-xs text-slate-500">Menunggu review</p>
                            </div>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                            <div class="flex flex-row items-center justify-between px-6 pb-2 pt-6">
                                <p class="text-sm font-medium text-slate-700">Kontributor Aktif</p>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 17a6.5 6.5 0 0 1 13 0" />
                                    <circle cx="12" cy="9" r="3.5" />
                                </svg>
                            </div>
                            <div class="px-6 pb-6">
                                <div class="text-2xl font-bold">{{ $authorCount }}</div>
                                <p class="text-xs text-slate-500">Penulis terlibat</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                        <div class="px-6 pt-6">
                            <h2 class="text-base font-semibold text-slate-900">Filter Artikel</h2>
                            <p class="text-sm text-slate-500">Saring artikel berdasarkan kriteria tertentu.</p>
                        </div>
                        <form method="GET" class="flex flex-col gap-4 px-6 py-4 md:flex-row">
                            <div class="relative flex-1">
                                <span class="pointer-events-none absolute left-2.5 top-2.5 inline-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.64 5.64a7.5 7.5 0 0 0 11.01 11.01Z" />
                                    </svg>
                                </span>
                                <input type="search" name="q" value="{{ $search }}" placeholder="Cari judul atau konten..."
                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 pl-8 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10" />
                            </div>

                            <select name="status" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 md:w-[180px]">
                                <option value="">Semua Status</option>
                                <option value="published" @selected($statusFilter === 'published')>Diterbitkan</option>
                                <option value="draft" @selected($statusFilter === 'draft')>Draft</option>
                            </select>

                            <select name="author" class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10 md:w-[200px]">
                                <option value="">Semua Penulis</option>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}" @selected($authorFilter === $author->id)>{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-white text-slate-900 shadow-sm">
                        <div class="px-6 pt-6">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h8l4 4v14H7z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 3v4h4" />
                                </svg>
                                <h2 class="text-base font-semibold text-slate-900">Daftar Artikel</h2>
                            </div>
                            <p class="text-sm text-slate-500">
                                Menampilkan {{ $artikels->count() }} dari {{ $artikels->total() }} artikel.
                            </p>
                        </div>

                        <div class="px-6 py-4">
                            <div class="w-full overflow-x-auto">
                                <table class="w-full caption-bottom text-sm">
                                    <thead class="[&_tr]:border-b">
                                        <tr class="border-b">
                                            <th class="h-10 px-2 text-left text-xs font-medium text-slate-500">Judul</th>
                                            <th class="hidden h-10 px-2 text-left text-xs font-medium text-slate-500 md:table-cell">Penulis</th>
                                            <th class="h-10 px-2 text-left text-xs font-medium text-slate-500">Status</th>
                                            <th class="hidden h-10 px-2 text-left text-xs font-medium text-slate-500 lg:table-cell">Tanggal Publikasi</th>
                                            <th class="h-10 px-2 text-right text-xs font-medium text-slate-500">
                                                <span class="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="[&_tr:last-child]:border-0">
                                        @forelse ($artikels as $artikel)
                                            <tr class="border-b">
                                                <td class="p-2 align-middle">
                                                    <div class="font-medium text-slate-900">{{ $artikel->title }}</div>
                                                    <div class="text-xs text-slate-500 md:hidden">{{ $artikel->user->name }}</div>
                                                </td>
                                                <td class="hidden p-2 align-middle md:table-cell">
                                                    {{ $artikel->user->name }}
                                                </td>
                                                <td class="p-2 align-middle">
                                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold
                                                        {{ $artikel->status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                                        {{ ucfirst($artikel->status) }}
                                                    </span>
                                                </td>
                                                <td class="hidden p-2 align-middle lg:table-cell">
                                                    {{ optional($artikel->updated_at)->format('Y-m-d') }}
                                                </td>
                                                <td class="p-2 align-middle text-right">
                                                    <details class="relative inline-block text-left">
                                                        <summary
                                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 hover:bg-slate-100 cursor-pointer"
                                                            role="button"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <circle cx="5" cy="12" r="1.5" />
                                                                <circle cx="12" cy="12" r="1.5" />
                                                                <circle cx="19" cy="12" r="1.5" />
                                                            </svg>
                                                            <span class="sr-only">Toggle menu</span>
                                                        </summary>
                                                        <div class="absolute right-0 mt-2 w-36 rounded-md border border-slate-200 bg-white shadow-lg z-20 text-sm text-slate-700">
                                                            <div class="px-3 py-2 text-xs font-semibold text-slate-500">Actions</div>
                                                            <a href="{{ route('admin.artikels.show', $artikel) }}" class="flex w-full items-center px-3 py-1 hover:bg-slate-50">Lihat</a>
                                                            <a href="{{ route('admin.artikels.edit', $artikel) }}" class="flex w-full items-center px-3 py-1 hover:bg-slate-50">Edit</a>
                                                            <form action="{{ route('admin.artikels.destroy', $artikel) }}" method="POST"
                                                                onsubmit="return confirm('Hapus artikel ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="flex w-full items-center px-3 py-1 text-red-600 hover:bg-slate-50">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </details>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                                                    Belum ada artikel yang sesuai filter.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t border-slate-200 px-6 py-4 text-xs text-slate-500">
                            <div>
                                Menampilkan
                                <strong>{{ $artikels->firstItem() ?? 0 }}-{{ $artikels->lastItem() ?? 0 }}</strong>
                                dari <strong>{{ $artikels->total() }}</strong> artikel
                            </div>
                            {{ $artikels->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
