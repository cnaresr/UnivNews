<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Approved — University News</title>
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
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeInUp 0.5s ease-out forwards; }
    </style>
</head>
<body class="h-full flex flex-col justify-center items-center py-12 px-4 bg-[#fcf8f9] text-[#1b1b1c]">

    <!-- Brand Logo Header -->
    <div class="w-full max-w-lg mb-6 flex items-center justify-center">
        <a href="{{ route('home') }}" class="inline-flex items-center space-x-2.5 group">
            <div class="bg-crimson text-white w-9 h-9 flex items-center justify-center font-heading font-bold text-xl rounded shadow transition-transform group-hover:scale-105">
                U
            </div>
            <span class="font-heading font-bold text-xl tracking-tight text-[#00081e]">
                University<span class="font-normal text-gray-500">News</span>
            </span>
        </a>
    </div>

    <div class="max-w-lg w-full bg-white border border-[#c5c6cf] p-10 shadow-sm fade-up">

        <!-- Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                <svg class="w-9 h-9 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold font-heading text-[#00081e] mb-2">Application Approved! 🎉</h1>
            <div class="w-10 h-0.5 bg-[#8b1528] mx-auto"></div>
        </div>

        <!-- Message -->
        <div class="space-y-4 text-sm text-gray-600 leading-relaxed text-center">
            <p>
                Congratulations, <strong class="text-[#00081e]">{{ $user->name }}</strong>!
                Your application to become an Author has been <strong class="text-green-600">approved</strong> by our editorial team.
            </p>
            <p>
                To start writing and access your author dashboard, please set up your account password using the link we sent to your email: 
                <br>
                <strong class="text-[#00081e]">{{ $user->email }}</strong>
            </p>
            
            <div class="pt-4">
                @php
                    $gmailUrl = "https://accounts.google.com/AccountChooser?Email=" . urlencode($user->email) . "&continue=https://mail.google.com/mail/";
                @endphp
                <a href="{{ str_contains($user->email, '@gmail.com') ? $gmailUrl : 'https://mail.google.com' }}" target="_blank" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Open Email Now
                </a>
            </div>

            <p class="text-xs text-gray-500 bg-gray-50 p-3 border border-gray-200 mt-4 text-left">
                📌 Please check your <strong>spam / promotions</strong> folder if the activation email does not appear in your inbox.
            </p>
        </div>

        <!-- Status Badge -->
        <div class="mt-6 flex items-center gap-2 p-3 bg-green-50 border border-green-200 justify-center">
            <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
            <span class="text-xs font-semibold text-green-800 font-heading uppercase tracking-wider">Status: Approved</span>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100">
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-1.5 text-xs text-gray-600 hover:text-[#8b1528] font-bold uppercase tracking-wider transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Back to Portal
            </a>
            
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 text-xs font-bold text-red-600 hover:text-white hover:bg-red-600 uppercase tracking-wider rounded transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Log Out
                </button>
            </form>
        </div>
    </div>
</body>
</html>
