<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Link Expired — University News</title>
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

    <div class="max-w-md w-full bg-white border border-[#c5c6cf] p-10 shadow-sm">

        <div class="flex justify-center mb-6">
            <div class="w-14 h-14 rounded-full bg-orange-100 flex items-center justify-center">
                <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        <div class="text-center mb-6">
            <h1 class="text-xl font-bold font-heading text-[#00081e]">Activation Link Expired</h1>
            <p class="text-xs text-gray-500 mt-2">Your password setup link is no longer valid.</p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 text-green-800 text-xs">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <p class="text-sm text-gray-600 text-center mb-6">
            Enter your email to receive a new activation link (valid for 48 hours).
        </p>

        <form method="POST" action="{{ route('author.set-password.resend') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">
                    Author Account Email
                </label>
                <input type="email"
                       name="email"
                       id="email"
                       value="{{ isset($user) ? $user->email : '' }}"
                       class="w-full bg-[#f8f9fa] border border-gray-300 px-3 py-2.5 text-xs text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0"
                       placeholder="your@email.com"
                       required>
            </div>
            <button type="submit"
                    class="w-full py-2.5 bg-[#00081e] hover:bg-[#8b1528] text-white text-xs font-bold uppercase tracking-wider transition-colors">
                Resend Activation Link
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-gray-100 text-center">
            <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600 underline">
                &larr; Back to Portal
            </a>
        </div>
    </div>
</body>
</html>
