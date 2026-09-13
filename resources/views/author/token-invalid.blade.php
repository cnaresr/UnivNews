<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invalid Link — University News</title>
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

    <div class="max-w-md w-full bg-white border border-[#c5c6cf] p-10 shadow-sm text-center">

        <div class="flex justify-center mb-6">
            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
        </div>

        <h1 class="text-xl font-bold font-heading text-[#00081e] mb-2">Invalid Link</h1>
        <p class="text-sm text-gray-500 mb-6">
            The activation link you followed is invalid or has already been used.
        </p>

        <p class="text-xs text-gray-400 mb-6">
            If you have already set up your password, please log in directly. If not, contact the administrator to request a new link.
        </p>

        <div class="flex flex-col gap-3">
            <a href="{{ route('login') }}"
               class="w-full py-2.5 bg-[#00081e] hover:bg-[#8b1528] text-white text-xs font-bold uppercase tracking-wider transition-colors">
                Go to Login Page
            </a>
            <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600 underline">
                Back to Portal
            </a>
        </div>
    </div>
</body>
</html>
