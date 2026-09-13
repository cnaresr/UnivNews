<section class="bg-white border border-[#C5C6CF] p-6 sm:p-8 rounded-lg shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-gray-100">
        <div>
            <h2 class="font-heading font-bold text-lg text-[#00081E] flex items-center gap-2">
                <svg class="w-5 h-5 text-[#8b1528]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Security & Password
            </h2>
            <p class="font-sans text-xs sm:text-sm text-gray-500 mt-1">Ensure your account is using a long, random password to stay secure.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-gray-100 text-gray-700 border border-gray-200">
                Credentials
            </span>
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-6 items-start">
            <label for="update_password_current_password" class="font-sans font-bold text-xs text-[#00081E] uppercase tracking-wider pt-2.5">
                Current Password
            </label>
            <div class="md:col-span-2">
                <input id="update_password_current_password" name="current_password" type="password" class="block w-full rounded-md border border-gray-300 focus:border-[#8b1528] focus:ring-1 focus:ring-[#8b1528] px-4 py-2.5 font-sans text-sm text-gray-900 transition-colors" autocomplete="current-password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-6 items-start">
            <label for="update_password_password" class="font-sans font-bold text-xs text-[#00081E] uppercase tracking-wider pt-2.5">
                New Password
            </label>
            <div class="md:col-span-2">
                <input id="update_password_password" name="password" type="password" class="block w-full rounded-md border border-gray-300 focus:border-[#8b1528] focus:ring-1 focus:ring-[#8b1528] px-4 py-2.5 font-sans text-sm text-gray-900 transition-colors" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-6 items-start">
            <label for="update_password_password_confirmation" class="font-sans font-bold text-xs text-[#00081E] uppercase tracking-wider pt-2.5">
                Confirm Password
            </label>
            <div class="md:col-span-2">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full rounded-md border border-gray-300 focus:border-[#8b1528] focus:ring-1 focus:ring-[#8b1528] px-4 py-2.5 font-sans text-sm text-gray-900 transition-colors" autocomplete="new-password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex justify-end items-center gap-4 pt-4 border-t border-gray-100">
            @if (session('status') === 'password-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="font-sans text-xs font-semibold text-green-600 flex items-center gap-1"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Password updated successfully.
                </span>
            @endif
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-navy hover:bg-gray-800 text-white font-sans font-bold text-xs uppercase tracking-wider rounded-md transition-colors shadow-sm">
                Update Password
            </button>
        </div>
    </form>
</section>
