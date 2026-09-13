<section class="bg-white border border-[#C5C6CF] p-6 sm:p-8 rounded-lg shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-gray-100">
        <div>
            <h2 class="font-heading font-bold text-lg text-[#00081E] flex items-center gap-2">
                <svg class="w-5 h-5 text-[#8b1528]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profile Information
            </h2>
            <p class="font-sans text-xs sm:text-sm text-gray-500 mt-1">Update your account's public name and primary email address.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-gray-100 text-gray-700 border border-gray-200">
                Personal Info
            </span>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-6 items-center pb-6 border-b border-gray-100"
             x-data="{
                 previewUrl: '{{ $user->avatar_url }}',
                 hasAvatar: {{ $user->hasAvatar() ? 'true' : 'false' }},
                 removeAvatar: false,
                 errorMessage: '',
                 validateAndPreview(e) {
                     const file = e.target.files[0];
                     if (!file) return;

                     this.errorMessage = '';

                     // Format validation (PNG or JPG)
                     const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                     const fileExt = file.name.split('.').pop().toLowerCase();
                     const validExts = ['jpg', 'jpeg', 'png'];

                     if (!validTypes.includes(file.type) && !validExts.includes(fileExt)) {
                         const msg = 'Invalid format! Only PNG or JPG photos are allowed.';
                         this.errorMessage = msg;
                         if (window.showWarningAlert) {
                             window.showWarningAlert('Format Warning', msg);
                         } else {
                             alert(msg);
                         }
                         e.target.value = '';
                         return;
                     }

                     // Maximum size: 2MB (2 * 1024 * 1024 = 2,097,152 bytes)
                     if (file.size > 2 * 1024 * 1024) {
                         const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                         const msg = 'Photo exceeds the 2MB size limit (' + sizeMB + ' MB). Please choose a smaller file.';
                         this.errorMessage = msg;
                         if (window.showWarningAlert) {
                             window.showWarningAlert('File Size Warning', msg);
                         } else {
                             alert(msg);
                         }
                         e.target.value = '';
                         return;
                     }

                     this.removeAvatar = false;
                     this.previewUrl = URL.createObjectURL(file);
                 },
                 removePhoto() {
                     this.previewUrl = '';
                     this.removeAvatar = true;
                     this.errorMessage = '';
                     const input = document.getElementById('profile_avatar_input');
                     if (input) input.value = '';
                 }
             }">
            <label class="font-sans font-bold text-xs text-[#00081E] uppercase tracking-wider">
                Profile Photo
            </label>
            <div class="md:col-span-2 flex flex-col sm:flex-row sm:items-center gap-5">
                <!-- Avatar Circle -->
                <div class="relative w-20 h-20 rounded-full border-2 border-gray-200 overflow-hidden bg-slate-100 flex-shrink-0 shadow-sm flex items-center justify-center">
                    <template x-if="previewUrl">
                        <img :src="previewUrl" alt="Profile Photo" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!previewUrl">
                        <span class="font-heading font-bold text-xl text-navy uppercase">
                            {{ substr($user->name, 0, 2) }}
                        </span>
                    </template>
                </div>

                <!-- Action buttons and notes -->
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <input type="file"
                               id="profile_avatar_input"
                               name="avatar"
                               accept="image/png,image/jpeg"
                               class="hidden"
                               @change="validateAndPreview($event)">
                        <input type="hidden" name="remove_avatar" :value="removeAvatar ? '1' : '0'">

                        <button type="button"
                                @click="document.getElementById('profile_avatar_input').click()"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-gray-300 hover:border-navy hover:text-navy text-xs font-bold uppercase tracking-wider rounded-md text-gray-700 shadow-xs transition-colors">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Upload Photo</span>
                        </button>

                        <button type="button"
                                x-show="previewUrl"
                                @click="removePhoto()"
                                class="px-3 py-2 text-xs font-semibold text-red-600 hover:text-red-800 transition-colors">
                            Remove
                        </button>
                    </div>

                    <p class="font-sans text-xs text-gray-500">
                        PNG or JPG only (max. <strong>2MB</strong>). Automatically compressed before saving.
                    </p>

                    <template x-if="errorMessage">
                        <p class="font-sans text-xs text-red-600 font-semibold" x-text="errorMessage"></p>
                    </template>
                    <x-input-error class="mt-1" :messages="$errors->get('avatar')" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-6 items-start">
            <label for="name" class="font-sans font-bold text-xs text-[#00081E] uppercase tracking-wider pt-2.5">
                Full Name
            </label>
            <div class="md:col-span-2">
                <input id="name" name="name" type="text" class="block w-full rounded-md border border-gray-300 focus:border-[#8b1528] focus:ring-1 focus:ring-[#8b1528] px-4 py-2.5 font-sans text-sm text-gray-900 transition-colors" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-6 items-start">
            <label for="email" class="font-sans font-bold text-xs text-[#00081E] uppercase tracking-wider pt-2.5">
                Email Address
            </label>
            <div class="md:col-span-2">
                <input id="email" name="email" type="email" class="block w-full rounded-md border border-gray-300 focus:border-[#8b1528] focus:ring-1 focus:ring-[#8b1528] px-4 py-2.5 font-sans text-sm text-gray-900 transition-colors" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-md">
                        <p class="text-xs text-amber-800">
                            {{ __('Your email address is unverified.') }}
                            <button form="send-verification" class="underline font-semibold ml-1 hover:text-amber-900">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-xs text-green-700">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="flex justify-end items-center gap-4 pt-4 border-t border-gray-100">
            @if (session('status') === 'profile-updated')
                <span
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="font-sans text-xs font-semibold text-green-600 flex items-center gap-1"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Saved successfully.
                </span>
            @endif
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-navy hover:bg-gray-800 text-white font-sans font-bold text-xs uppercase tracking-wider rounded-md transition-colors shadow-sm">
                Save Changes
            </button>
        </div>
    </form>
</section>
