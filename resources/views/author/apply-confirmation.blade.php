<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Submitted — University News</title>
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
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
                <svg class="w-9 h-9 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold font-heading text-[#00081e] mb-2">Application Submitted</h1>
            <div class="w-10 h-0.5 bg-[#8b1528] mx-auto"></div>
        </div>

        <!-- Message -->
        <div class="space-y-4 text-sm text-gray-600 leading-relaxed">
            <p>
                Thank you, <strong class="text-[#00081e]">{{ $user->name }}</strong>.
                Your application to become an Author has been successfully received by our system
                and is currently under review by our admin team.
            </p>
            <p>
                We will review your submitted credentials and writing samples. This process typically
                takes <strong>1–3 business days</strong>. The outcome of the review — whether approved
                or requiring revision — will be sent to your email at
                <strong class="text-[#00081e]">{{ $user->email }}</strong>.
            </p>
            <p class="text-xs text-gray-500 bg-gray-50 p-3 border border-gray-200">
                📌 Please check your <strong>spam / promotions</strong> folder if you do not receive an email within the estimated timeframe.
            </p>
            <p class="text-xs text-gray-500">
                If you have any further questions, please reach out to our team at
                <a href="mailto:{{ config('mail.from.address') }}" class="text-[#8b1528] hover:underline">
                    {{ config('mail.from.address') }}
                </a>.
            </p>
        </div>

        <!-- Status Badge -->
        <div class="mt-6 flex items-center gap-2 p-3 bg-yellow-50 border border-yellow-200">
            <span class="w-2 h-2 rounded-full bg-yellow-500 flex-shrink-0 animate-pulse"></span>
            <span class="text-xs font-semibold text-yellow-800 font-heading uppercase tracking-wider">Status: Pending Review</span>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center gap-1.5 text-xs text-gray-600 hover:text-[#8b1528] font-bold uppercase tracking-wider transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Back to Portal
                </a>
                <a href="{{ route('author.apply') }}"
                   class="text-xs text-gray-500 hover:text-gray-800 underline transition-colors">
                    View Status
                </a>
            </div>

            @auth
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 text-xs font-bold text-red-600 hover:text-white hover:bg-red-600 uppercase tracking-wider rounded transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Log Out
                </button>
            </form>
            @endauth
        </div>
    </div>
</body>
</html>
