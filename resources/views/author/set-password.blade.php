<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set Your Password — University News</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23B71032'/><text x='50' y='50' font-family='sans-serif' font-weight='bold' font-size='70' fill='white' dominant-baseline='central' text-anchor='middle'>U</text></svg>">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Work Sans', sans-serif; }
        .font-heading { font-family: 'Montserrat', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-center items-center py-12 px-4 bg-[#fcf8f9] text-[#1b1b1c]">

    <!-- Brand Logo Header -->
    <div class="max-w-md w-full mb-6 flex items-center justify-center">
        <a href="{{ route('home') }}" class="inline-flex items-center space-x-2.5 group">
            <div class="bg-crimson text-white w-9 h-9 flex items-center justify-center font-heading font-bold text-xl rounded shadow transition-transform group-hover:scale-105">
                U
            </div>
            <span class="font-heading font-bold text-xl tracking-tight text-[#00081e]">
                University<span class="font-normal text-gray-500">News</span>
            </span>
        </a>
    </div>

    <div class="max-w-md w-full bg-white border border-[#c5c6cf] p-10 shadow-sm" x-data="setPasswordForm()">

        <!-- Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-14 h-14 rounded-full bg-[#00081e] flex items-center justify-center text-white">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                </svg>
            </div>
        </div>

        <div class="text-center mb-8">
            <h1 class="text-xl font-bold font-heading text-[#00081e]">Create Author Account Password</h1>
            <p class="text-xs text-gray-500 mt-2">Hello, <strong>{{ $user->name }}</strong>. Please create a password to access your CMS author portal.</p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 text-green-800 text-xs">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs">
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('author.set-password.store') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Password Baru <span class="text-[#8b1528]">*</span>
                </label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'"
                           name="password"
                           id="password"
                           x-model="password"
                           class="w-full bg-[#f8f9fa] border border-gray-300 px-3 py-2.5 pr-10 text-xs text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0"
                           placeholder="Minimal 8 karakter"
                           required>
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#00081e] focus:outline-none" aria-label="Toggle password visibility">
                        <svg x-show="!showPassword" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showPassword" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>

                <!-- Password Strength Indicator -->
                <div class="mt-3 flex items-center justify-between" x-show="password.length > 0" x-cloak>
                    <div class="text-xs font-semibold uppercase tracking-wider" :class="strengthColorText" x-text="strengthLabel"></div>
                    <div class="flex-1 ml-4 flex h-1.5 space-x-1">
                        <div class="flex-1 bg-gray-200"><div class="h-full transition-all duration-300" :class="strength >= 1 ? strengthColorBg : ''"></div></div>
                        <div class="flex-1 bg-gray-200"><div class="h-full transition-all duration-300" :class="strength >= 3 ? strengthColorBg : ''"></div></div>
                        <div class="flex-1 bg-gray-200"><div class="h-full transition-all duration-300" :class="strength >= 5 ? strengthColorBg : ''"></div></div>
                    </div>
                </div>
            </div>

            <!-- Password Requirements Checklist -->
            <div class="bg-gray-50 p-4 border border-[#c5c6cf]">
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Persyaratan Password</p>
                <ul class="space-y-1.5 text-xs">
                    <template x-for="req in requirements" :key="req.label">
                        <li class="flex items-center" :class="req.met ? 'text-green-600' : 'text-gray-500'">
                            <svg x-show="req.met" class="w-3.5 h-3.5 mr-1.5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            <svg x-show="!req.met" class="w-3.5 h-3.5 mr-1.5 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"></circle></svg>
                            <span x-text="req.label"></span>
                        </li>
                    </template>
                </ul>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Konfirmasi Password <span class="text-[#8b1528]">*</span>
                </label>
                <div class="relative">
                    <input :type="showConfirmPassword ? 'text' : 'password'"
                           name="password_confirmation"
                           id="password_confirmation"
                           x-model="passwordConfirmation"
                           class="w-full bg-[#f8f9fa] border border-gray-300 px-3 py-2.5 pr-10 text-xs text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0"
                           :class="{'border-[#8b1528] focus:border-[#8b1528]': confirmationError}"
                           placeholder="Ketik ulang password"
                           required>
                    <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-[#00081e] focus:outline-none" aria-label="Toggle password visibility">
                        <svg x-show="!showConfirmPassword" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showConfirmPassword" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                <p x-show="confirmationError" class="text-[#8b1528] text-[11px] mt-1" x-cloak>Password tidak cocok.</p>
            </div>

            <div class="pt-3">
                <button type="submit"
                        class="w-full py-3 text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-sm"
                        :class="isFormValid ? 'bg-[#00081e] hover:bg-[#8b1528]' : 'bg-gray-400 cursor-not-allowed'"
                        :disabled="!isFormValid">
                    Aktivasi Akun &amp; Buat Password
                </button>
            </div>
        </form>

        <p class="text-center text-[11px] text-gray-400 mt-6">
            Setelah password dibuat, kamu dapat login dengan email dan password ini,
            atau tetap menggunakan Google Sign-In.
        </p>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('setPasswordForm', () => ({
            password: '',
            passwordConfirmation: '',
            showPassword: false,
            showConfirmPassword: false,

            get requirements() {
                return [
                    { label: 'Minimal 8 karakter', met: this.password.length >= 8 },
                    { label: 'Mengandung huruf besar (A-Z)', met: /[A-Z]/.test(this.password) },
                    { label: 'Mengandung huruf kecil (a-z)', met: /[a-z]/.test(this.password) },
                    { label: 'Mengandung angka (0-9)', met: /[0-9]/.test(this.password) },
                    { label: 'Mengandung karakter spesial (!@#$%)', met: /[^A-Za-z0-9]/.test(this.password) }
                ];
            },

            get strength() {
                if (this.password.length === 0) return 0;
                return this.requirements.filter(r => r.met).length;
            },

            get strengthLabel() {
                if (this.strength === 0) return '';
                if (this.strength < 3) return 'Lemah';
                if (this.strength < 5) return 'Sedang';
                return 'Kuat';
            },

            get strengthColorText() {
                if (this.strength < 3) return 'text-[#8b1528]';
                if (this.strength < 5) return 'text-yellow-600';
                return 'text-green-600';
            },

            get strengthColorBg() {
                if (this.strength < 3) return 'bg-[#8b1528]';
                if (this.strength < 5) return 'bg-yellow-500';
                return 'bg-green-500';
            },

            get confirmationError() {
                return this.passwordConfirmation.length > 0 && this.password !== this.passwordConfirmation;
            },

            get isFormValid() {
                return this.strength === 5 && this.password === this.passwordConfirmation && this.passwordConfirmation.length > 0;
            }
        }));
    });
    </script>
</body>
</html>
