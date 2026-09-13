<section class="bg-white border border-red-200 p-6 sm:p-8 rounded-lg shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-red-100">
        <div>
            <h2 class="font-heading font-bold text-lg text-crimson flex items-center gap-2">
                <svg class="w-5 h-5 text-crimson" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Danger Zone
            </h2>
            <p class="font-sans text-xs sm:text-sm text-gray-500 mt-1">
                Irreversible account actions and permanent data removal.
            </p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-red-50 text-red-700 border border-red-200">
                Destructive Area
            </span>
        </div>
    </div>

    <div class="mt-5 space-y-4">
        <p class="font-sans text-sm text-gray-600 leading-relaxed">
            Once your account is deleted, all of its resources, published articles, and account data will be permanently deleted. This action cannot be undone.
        </p>

        <div class="flex flex-wrap items-center gap-3 pt-2">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 hover:text-navy hover:border-navy text-xs font-bold uppercase tracking-wider rounded-md transition-colors shadow-sm">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Log Out
                </button>
            </form>

            <button
                type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-crimson hover:bg-red-800 text-white text-xs font-bold uppercase tracking-wider rounded-md transition-colors shadow-sm"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Permanently Delete Account
            </button>
        </div>
    </div>

    <!-- Centered Alert Dialog Template for Delete Account -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" maxWidth="md" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-7">
            @csrf
            @method('delete')

            <!-- Media Badge (Icon container) destructive crimson style matching alert dialog -->
            <div class="flex h-12 w-12 items-center justify-center rounded-[16px] bg-crimson/10 text-crimson mb-4 border border-crimson/15 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18"/>
                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                    <line x1="10" x2="10" y1="11" y2="17"/>
                    <line x1="14" x2="14" y1="11" y2="17"/>
                </svg>
            </div>

            <!-- Title -->
            <h2 class="font-heading text-lg sm:text-xl font-bold text-[#00081E] tracking-tight">
                {{ __('Permanently delete account?') }}
            </h2>

            <!-- Description -->
            <p class="mt-2 font-sans text-sm text-gray-600 leading-relaxed">
                {{ __('Once your account is deleted, all of its resources, articles, and data will be permanently deleted. Please enter your password to confirm.') }}
            </p>

            <!-- Password Input -->
            <div class="mt-5">
                <label for="password" class="sr-only">{{ __('Password') }}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-full rounded-[12px] border border-gray-300 focus:border-crimson focus:ring-1 focus:ring-crimson px-4 py-2.5 text-sm font-sans text-gray-900 transition-colors"
                    placeholder="{{ __('Enter your password to confirm') }}"
                    required
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <!-- Buttons matching alert dialog layout -->
            <div class="mt-6 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-2.5">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="w-full sm:w-auto px-4 py-2.5 rounded-[12px] border border-gray-200 text-sm font-semibold font-sans text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors focus:outline-none focus:ring-2 focus:ring-gray-200"
                >
                    {{ __('Cancel') }}
                </button>

                <button
                    type="submit"
                    class="w-full sm:w-auto px-5 py-2.5 rounded-[12px] bg-crimson hover:bg-red-800 text-white text-sm font-semibold font-sans shadow-md shadow-crimson/25 transition-all focus:outline-none focus:ring-2 focus:ring-crimson/40 active:scale-[0.98] flex items-center justify-center gap-2"
                >
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
