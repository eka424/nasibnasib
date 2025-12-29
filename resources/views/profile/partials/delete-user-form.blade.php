<section class="space-y-4">
    <header class="flex flex-col gap-1">
        <p class="text-xs uppercase tracking-[0.3em] text-gray-400">{{ __('Bahaya') }}</p>
        <h2 class="text-2xl font-bold text-gray-900">
            {{ __('Hapus Akun') }}
        </h2>
        <p class="text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center rounded-2xl border border-gray-300 bg-white px-5 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50 transition">
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4 rounded-2xl border-gray-300 bg-white"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" x-on:click="$dispatch('close')"
                    class="inline-flex items-center rounded-2xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:border-gray-500 hover:text-gray-900">
                    {{ __('Cancel') }}
                </button>

                <button type="submit"
                    class="ms-3 inline-flex items-center rounded-2xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
