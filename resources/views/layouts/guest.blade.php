<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'University News Portal') }} - Authentication</title>
    @if(request()->getHost() === 'devtest.univnews.site' || config('app.env') === 'staging')
        <meta name="robots" content="noindex, nofollow">
    @endif
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23B71032'/><text x='50' y='50' font-family='sans-serif' font-weight='bold' font-size='70' fill='white' dominant-baseline='central' text-anchor='middle'>U</text></svg>">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-text-main antialiased bg-navy min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Fullscreen Background Image Layer -->
    <div class="fixed inset-0 z-0 pointer-events-none">
        <img src="https://images.unsplash.com/photo-1713633053651-0bb16c6dfa55?q=80&w=1920&auto=format&fit=crop" 
             alt="Background" 
             class="w-full h-full object-cover opacity-20">
    </div>

    <!-- Floating Top Navigation against Background (No heavy header bar) -->
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 flex items-center justify-between relative z-20">
        <!-- Brand Logo (Directly against background) -->
        <a href="{{ route('home') }}" class="inline-flex items-center space-x-3 group">
            <div class="bg-crimson text-white w-9 h-9 flex items-center justify-center font-heading font-bold text-xl rounded shadow transition-transform group-hover:scale-105">
                U
            </div>
            <span class="font-heading font-bold text-xl tracking-tight text-white drop-shadow">
                University<span class="font-normal text-gray-300">News</span>
            </span>
        </a>

        <!-- Merged Back & Home Pill (Directly against background) -->
        <nav class="inline-flex items-center bg-black/40 hover:bg-black/55 backdrop-blur-md border border-white/15 rounded-full p-1 shadow-lg text-xs font-semibold text-white transition-all">
            <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}'" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full hover:bg-white/15 hover:text-white transition-colors">
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back</span>
            </button>
            <span class="w-px h-3.5 bg-white/20 mx-0.5"></span>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full hover:bg-white/15 hover:text-amber-300 transition-colors">
                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Home</span>
            </a>
        </nav>
    </div>

    <div class="w-full flex-grow flex flex-col py-8 px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="w-full max-w-7xl m-auto flex flex-col md:flex-row items-center justify-center gap-12">
            
            @if(isset($support))
            <!-- Support Center (Desktop left side, mobile stacked below or above depending on view) -->
            <div class="w-full md:w-1/3 shrink-0 hidden md:block">
                {{ $support }}
            </div>
            @endif

            <!-- Main Auth Card Container -->
            <div class="w-full max-w-md {{ isset($support) ? 'md:w-1/2 md:max-w-md' : 'mx-auto' }}">
                {{ $slot }}
            </div>

            @if(isset($support_mobile))
            <!-- Support Center (Mobile only) -->
            <div class="w-full block md:hidden mt-8">
                {{ $support_mobile }}
            </div>
            @endif
        </div>

    </div>
    <x-alert-toast />
    <x-alert-dialog />
    <x-page-loader />
</body>
</html>
