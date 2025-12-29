<x-app-layout>
    {{-- resources/views/kelola-user.blade.php --}}

    <div class="flex min-h-screen w-full flex-col bg-slate-100">
      <div class="flex flex-col sm:gap-4 sm:py-4">
        <header class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b bg-white px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
          <h1 class="text-3xl font-bold tracking-tight">Kelola User</h1>
        </header>

        <main class="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
          <div class="grid auto-rows-max items-start gap-4 md:gap-8 lg:col-span-2">

            {{-- Kartu statistik / header --}}
            <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-2 xl:grid-cols-4">
              {{-- Card Manajemen User --}}
              <div class="sm:col-span-2 rounded-xl border bg-white text-slate-900 shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6">
                  <h2 class="text-xl font-semibold leading-none tracking-tight">
                    Manajemen User
                  </h2>
                  <p class="max-w-lg text-sm text-slate-500 leading-relaxed">
                    Kelola semua user yang terdaftar di sistem, mulai dari jamaah,
                    ustadz, hingga pengurus masjid.
                  </p>
                </div>
              </div>

              {{-- Card Total User --}}
              <div class="rounded-xl border bg-white text-slate-900 shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6 pb-3">
                  <p class="text-sm text-slate-500">Total User</p>
                  <p class="text-4xl font-bold tracking-tight">{{ $totalUsers }}</p>
                </div>
                <div class="p-6 pt-0">
                  <div class="text-xs text-slate-500">
                    {{-- Placeholder for percentage change, not implemented dynamically yet --}}
                    +5% dari bulan lalu
                  </div>
                </div>
                <div class="flex items-center p-6 pt-0">
                  <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full w-1/2 rounded-full bg-slate-900"></div>
                  </div>
                </div>
              </div>

              {{-- Card User Aktif --}}
              <div class="rounded-xl border bg-white text-slate-900 shadow-sm">
                <div class="flex flex-col space-y-1.5 p-6 pb-3">
                  <p class="text-sm text-slate-500">User Aktif</p>
                  <p class="text-4xl font-bold tracking-tight">{{ $activeUsers }}</p>
                </div>
                <div class="p-6 pt-0">
                  <div class="text-xs text-slate-500">
                    {{-- Placeholder for percentage change, not implemented dynamically yet --}}
                    +10% dari bulan lalu
                  </div>
                </div>
                <div class="flex items-center p-6 pt-0">
                  <div class="h-2 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full w-[70%] rounded-full bg-slate-900"></div>
                  </div>
                </div>
              </div>
            </div>

            {{-- Card tabel user --}}
            <div class="rounded-xl border bg-white text-slate-900 shadow-sm">
              <div class="flex items-center justify-between gap-4 border-b px-6 py-4">
                <h2 class="text-lg font-semibold tracking-tight">
                  Daftar User
                </h2>

                <div class="flex items-center gap-2">
                  {{-- Search --}}
                  <div class="relative flex-1 md:grow-0">
                    <span class="pointer-events-none absolute left-2.5 top-2.5 inline-flex">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 5.64 5.64a7.5 7.5 0 0 0 11.01 11.01Z" />
                      </svg>
                    </span>
                    <input
                      type="search"
                      placeholder="Cari user..."
                      class="w-full rounded-lg border border-slate-200 bg-white pl-8 pr-3 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10 md:w-[200px] lg:w-[336px]"
                    />
                  </div>

                  {{-- Dropdown filter (pakai <details> biar tanpa JS) --}}
                  <details class="relative inline-block text-left">
                    <summary class="list-none">
                      <button
                        type="button"
                        class="inline-flex h-10 items-center gap-1 rounded-md border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50"
                      >
                        <span class="inline-flex">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 5h18M6 12h12M10 19h4" stroke-linecap="round" stroke-linejoin="round" />
                          </svg>
                        </span>
                        <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">
                          Filter
                        </span>
                      </button>
                    </summary>
                    <div class="absolute right-0 mt-2 w-40 rounded-md border border-slate-200 bg-white shadow-lg z-20">
                      <div class="px-3 py-2 text-xs font-semibold text-slate-500">
                        Filter by
                      </div>
                      <div class="border-t border-slate-100"></div>
                      <div class="py-1 text-sm text-slate-700">
                        <label class="flex cursor-pointer items-center gap-2 px-3 py-1 hover:bg-slate-50">
                          <input type="checkbox" class="h-3 w-3 rounded border-slate-300 text-slate-900" checked>
                          <span>Aktif</span>
                        </label>
                        <label class="flex cursor-pointer items-center gap-2 px-3 py-1 hover:bg-slate-50">
                          <input type="checkbox" class="h-3 w-3 rounded border-slate-300 text-slate-900">
                          <span>Nonaktif</span>
                        </label>
                      </div>
                    </div>
                  </details>

                  {{-- Tombol tambah user --}}
                  <a
                    href="{{ route('admin.users.create') }}"
                    class="inline-flex h-10 items-center gap-1 rounded-md bg-slate-900 px-3 text-sm font-medium text-white shadow-sm hover:bg-slate-800"
                  >
                    <span class="inline-flex">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 8v8M8 12h8" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>
                    </span>
                    <span class="sr-only sm:not-sr-only sm:whitespace-nowrap">
                      Tambah User
                    </span>
                  </a>
                </div>
              </div>

              {{-- Tabel konten --}}
              <div class="px-6 py-4">
                <div class="w-full overflow-x-auto">
                  <table class="w-full caption-bottom text-sm">
                    <thead class="[&_tr]:border-b">
                      <tr class="border-b">
                        <th class="h-10 px-2 text-left align-middle text-xs font-medium text-slate-500">Nama</th>
                        <th class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 md:table-cell">
                          Email
                        </th>
                        <th class="h-10 px-2 text-left align-middle text-xs font-medium text-slate-500">Role</th>
                        <th class="h-10 px-2 text-left align-middle text-xs font-medium text-slate-500">Status</th>
                        <th class="hidden h-10 px-2 text-left align-middle text-xs font-medium text-slate-500 md:table-cell">
                          Tanggal Registrasi
                        </th>
                        <th class="h-10 px-2 text-right align-middle text-xs font-medium text-slate-500">
                          <span class="sr-only">Actions</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0">
                      @foreach ($users as $user)
                      <tr class="border-b">
                        <td class="p-2 align-middle">
                          <div class="font-medium text-slate-900">{{ $user->name }}</div>
                          <div class="hidden text-xs text-slate-500 md:inline">
                            {{ $user->email }}
                          </div>
                        </td>
                        <td class="hidden p-2 align-middle md:table-cell">
                          {{ $user->email }}
                        </td>
                        <td class="p-2 align-middle">
                          @php
                            $roleClass = '';
                            switch ($user->role) {
                                case 'jamaah':
                                    $roleClass = 'border-slate-200 text-slate-700';
                                    break;
                                case 'ustadz':
                                    $roleClass = 'border-yellow-200 bg-yellow-100 text-yellow-800';
                                    break;
                                case 'pengurus':
                                    $roleClass = 'border-blue-200 bg-blue-100 text-blue-800';
                                    break;
                                case 'admin':
                                    $roleClass = 'border-purple-200 bg-purple-100 text-purple-800';
                                    break;
                                default:
                                    $roleClass = 'border-slate-200 text-slate-700';
                                    break;
                            }
                          @endphp
                          <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $roleClass }}">
                            {{ ucfirst($user->role) }}
                          </span>
                        </td>
                        <td class="p-2 align-middle">
                          <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                            Aktif
                          </span>
                        </td>
                        <td class="hidden p-2 align-middle md:table-cell">
                          {{ $user->created_at->format('Y-m-d') }}
                        </td>
                        <td class="p-2 align-middle text-right">
                          <details class="relative inline-block text-left">
                            <summary
                              class="inline-flex h-8 w-8 items-center justify-center rounded-md text-slate-500 hover:bg-slate-100 cursor-pointer list-none"
                            >
                              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="5" cy="12" r="1.5" />
                                <circle cx="12" cy="12" r="1.5" />
                                <circle cx="19" cy="12" r="1.5" />
                              </svg>
                              <span class="sr-only">Toggle menu</span>
                            </summary>
                            <div class="absolute right-0 mt-2 w-32 rounded-md border border-slate-200 bg-white shadow-lg z-20 text-sm text-slate-700">
                              <div class="px-3 py-2 text-xs font-semibold text-slate-500">
                                Actions
                              </div>
                              <a href="{{ route('admin.users.edit', $user) }}" class="flex w-full items-center px-3 py-1 hover:bg-slate-50">
                                Edit
                              </a>
                              <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                onsubmit="return confirm('Hapus user ini?')">
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
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

              {{-- Footer: pagination --}}
              <div class="flex items-center justify-between border-t px-6 py-4">
                <div class="text-xs text-slate-500">
                  Menampilkan <strong>{{ $users->firstItem() }}-{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> user
                </div>

                <nav class="inline-flex items-center gap-1 text-sm" aria-label="Pagination">
                  @if ($users->onFirstPage())
                    <span class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs text-slate-400 cursor-not-allowed">
                      ‹ Previous
                    </span>
                  @else
                    <a
                      href="{{ $users->previousPageUrl() }}"
                      class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50"
                    >
                      ‹ Previous
                    </a>
                  @endif

                  @if ($users->hasMorePages())
                    <a
                      href="{{ $users->nextPageUrl() }}"
                      class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs text-slate-700 hover:bg-slate-50"
                    >
                      Next ›
                    </a>
                  @else
                    <span class="inline-flex items-center gap-1 rounded-md border border-slate-200 px-2 py-1 text-xs text-slate-400 cursor-not-allowed">
                      Next ›
                    </span>
                  @endif
                </nav>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
</x-app-layout>
