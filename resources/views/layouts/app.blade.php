<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'University News'))</title>
    @yield('og_meta')
    @if(request()->getHost() === 'devtest.univnews.site' || config('app.env') === 'staging')
        <meta name="robots" content="noindex, nofollow">
    @endif
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23B71032'/><text x='50' y='50' font-family='sans-serif' font-weight='bold' font-size='70' fill='white' dominant-baseline='central' text-anchor='middle'>U</text></svg>">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-background text-text-main min-h-screen">
    @auth
        @if(is_null(auth()->user()->password))
            <div x-data="{ open: true }" x-show="open" class="bg-blue-50 border-b border-blue-200 px-4 py-3 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-between">
                    <div class="flex w-0 flex-1 items-center">
                        <span class="flex rounded-lg bg-blue-100 p-2">
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </span>
                        <p class="ml-3 truncate font-medium text-blue-700 text-sm">
                            <span>Your account currently uses Google Sign-In. You can optionally create a local password for an additional login method.</span>
                        </p>
                    </div>
                    <div class="order-3 mt-2 w-full flex-shrink-0 sm:order-2 sm:mt-0 sm:w-auto flex gap-2">
                        <a href="{{ route('password.create') }}" class="flex items-center justify-center rounded-md border border-transparent bg-white px-4 py-2 text-sm font-medium text-blue-600 shadow-sm hover:bg-blue-50">Create Local Password</a>
                        <button @click="open = false" type="button" class="flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Maybe Later</button>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    @yield('base_content')
    @isset($slot)
        {{ $slot }}
    @endisset
    @stack('scripts')
    <x-alert-toast />
    <x-alert-dialog />
    <x-page-loader />
</body>
</html>
