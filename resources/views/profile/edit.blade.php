<x-app-layout>
    <div class="space-y-8">
        <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-2">
                    <p class="text-sm uppercase tracking-[0.25em] text-gray-500 font-semibold">Akun</p>
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">Pengaturan Profil</h1>
                    <p class="text-sm text-gray-600 max-w-2xl">
                        Kelola identitas, kata sandi, dan keamanan akun Anda dalam satu tempat. Perubahan disinkronkan otomatis ke seluruh fitur.
                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Nama lengkap</p>
                        <p class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
