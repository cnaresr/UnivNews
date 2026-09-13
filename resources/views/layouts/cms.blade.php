<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full overflow-hidden bg-[#030919]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'CMS Portal - University News')</title>
    @if(request()->getHost() === 'devtest.univnews.site' || config('app.env') === 'staging')
        <meta name="robots" content="noindex, nofollow">
    @endif
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' fill='%23B71032'/><text x='50' y='50' font-family='sans-serif' font-weight='bold' font-size='70' fill='white' dominant-baseline='central' text-anchor='middle'>U</text></svg>">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;0,8..60,700;1,8..60,400&family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind & App Assets via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js is bundled with Vite/Breeze -->
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Work Sans', sans-serif; color: #1b1b1c; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Montserrat', sans-serif; }
        .font-serif-content { font-family: 'Source Serif 4', serif; }
    </style>
</head>
@php
    // Detect current user (admin or web guard) for onboarding data attributes
    $onboardingUser = null;
    $onboardingRole = null;
    if (request()->routeIs('admin.*') && Auth::guard('admin')->check()) {
        $onboardingUser = Auth::guard('admin')->user();
        $onboardingRole = 'admin';
    } elseif (Auth::guard('web')->check()) {
        $onboardingUser = Auth::guard('web')->user();
        if ($onboardingUser && $onboardingUser->isAuthor()) {
            $onboardingRole = 'author';
        }
    }
    $onboardingPending = ($onboardingUser && !$onboardingUser->has_completed_onboarding) ? 'true' : 'false';
    $completedTours = $onboardingUser ? ($onboardingUser->completed_page_tours ?? []) : [];
@endphp
<body class="h-full overflow-hidden antialiased bg-[#030919] text-[#1b1b1c]"
      data-onboarding-pending="{{ $onboardingPending }}"
      data-user-role="{{ $onboardingRole ?? '' }}"
      data-completed-tours="{{ json_encode($completedTours) }}"
      data-page-tour-id="@yield('page_tour_id')">
    <div class="flex h-screen h-[100dvh] w-full overflow-hidden bg-[#f8f9fa]" x-data="{ mobileSidebarOpen: false }">
        
        <!-- Mobile Sidebar Backdrop -->
        <div x-show="mobileSidebarOpen" 
             x-cloak
             @click="mobileSidebarOpen = false" 
             class="fixed inset-0 z-40 bg-black/60 md:hidden"></div>

        <!-- Sidebar -->
        <aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'" 
               class="fixed inset-y-0 left-0 z-50 w-64 h-full min-h-screen md:h-screen md:sticky md:top-0 bg-[#030919] text-white transition-transform duration-200 ease-in-out md:translate-x-0 flex flex-col flex-shrink-0 select-none shadow-xl md:shadow-none">
            
            <!-- Logo Header -->
            <div class="p-6 border-b border-gray-800/80">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded bg-[#8b1528] flex items-center justify-center text-white font-bold text-lg shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-lg font-extrabold tracking-tight font-heading text-white group-hover:text-red-400 transition-colors leading-tight">CMS Portal</div>
                        <div class="text-xs text-gray-400 font-sans tracking-wide">University News</div>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto py-6 space-y-1.5 px-0 no-scrollbar sidebar-nav-scroll [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden" data-tour="sidebar-nav">
                @php
                    // Determine user from the correct guard based on current route context
                    $isAdminRoute = request()->routeIs('admin.*');
                    if ($isAdminRoute) {
                        $currentUser = Auth::guard('admin')->user();
                        $isAdmin = true;
                        $isAuthor = false;
                    } else {
                        $currentUser = Auth::guard('web')->user();
                        $isAdmin = false;
                        $isAuthor = $currentUser && $currentUser->isAuthor();
                    }
                @endphp

                <!-- Dashboard -->
                <a href="{{ $isAdmin ? route('admin.dashboard') : route('author.dashboard') }}" 
                   class="flex items-center px-6 py-3.5 text-sm font-medium transition-colors {{ request()->routeIs('*.dashboard') ? 'bg-[#8b1528] text-white font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="7" height="7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="14" y="3" width="7" height="7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="14" y="14" width="7" height="7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <rect x="3" y="14" width="7" height="7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Dashboard
                </a>

                <!-- Articles -->
                <a href="{{ $isAdmin ? route('admin.articles.index') : route('author.articles.index') }}" 
                   data-tour="{{ $isAdmin ? '' : 'author-articles-link' }}"
                   class="flex items-center px-6 py-3.5 text-sm font-medium transition-colors {{ request()->routeIs('*articles*') ? 'bg-[#8b1528] text-white font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Articles
                </a>

                <!-- Users / Authors Management (Admin only) -->
                @if($isAdmin)
                <a href="{{ route('admin.authors.index') }}" 
                   data-tour="admin-users-link"
                   class="flex items-center px-6 py-3.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.authors*') || request()->routeIs('admin.users*') ? 'bg-[#8b1528] text-white font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Users
                </a>

                <a href="{{ route('admin.universities.index') }}" 
                   class="flex items-center px-6 py-3.5 text-sm font-medium transition-colors {{ request()->routeIs('admin.universities*') ? 'bg-[#8b1528] text-white font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Universities
                </a>
                {{-- Universities --}}

                {{-- Site Content (Admin only) --}}
                <div x-data="{ openContent: {{ request()->routeIs('admin.pages*') || request()->routeIs('admin.faqs*') ? 'true' : 'false' }} }">
                    <button @click="openContent = !openContent" class="w-full flex items-center justify-between px-6 py-3.5 text-sm font-medium transition-colors text-gray-300 hover:bg-white/5 hover:text-white">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Site Content
                        </span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': openContent }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openContent" class="bg-black/20 py-1 space-y-1 text-xs">
                        <a href="{{ route('admin.pages.about.edit') }}" class="block px-12 py-2 {{ request()->routeIs('admin.pages.about*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">About Us</a>
                        <a href="{{ route('admin.faqs.index') }}" class="block px-12 py-2 {{ request()->routeIs('admin.faqs*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">FAQ</a>
                        <a href="{{ route('admin.pages.contact.edit') }}" class="block px-12 py-2 {{ request()->routeIs('admin.pages.contact*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">Contact Info</a>
                        <a href="{{ route('admin.pages.privacy.edit') }}" class="block px-12 py-2 {{ request()->routeIs('admin.pages.privacy*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">Privacy Policy</a>
                    </div>
                </div>

                {{-- Settings Dropdown (Admin only: Profile, Payment Settings, Active Sessions Settings) --}}
                <div x-data="{ openSettings: {{ request()->routeIs('admin.settings*') || request()->routeIs('admin.profile*') || request()->routeIs('admin.app-settings*') || request()->routeIs('admin.sessions*') ? 'true' : 'false' }} }">
                    <button @click="openSettings = !openSettings" class="w-full flex items-center justify-between px-6 py-3.5 text-sm font-medium transition-colors text-gray-300 hover:bg-white/5 hover:text-white">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-3.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Settings
                        </span>
                        <svg class="w-4 h-4 transform transition-transform" :class="{ 'rotate-180': openSettings }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openSettings" class="bg-black/20 py-1 space-y-1 text-xs">
                        <a href="{{ route('admin.settings.edit') }}" class="block px-12 py-2 {{ request()->routeIs('admin.settings*') || request()->routeIs('admin.profile*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">Profile</a>
                        <a href="{{ route('admin.app-settings.index') }}" class="block px-12 py-2 {{ request()->routeIs('admin.app-settings*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">Payment Settings</a>
                        <a href="{{ route('admin.sessions.index') }}" class="block px-12 py-2 {{ request()->routeIs('admin.sessions*') ? 'text-white font-semibold' : 'text-gray-400 hover:text-white' }}">Active Sessions Settings</a>
                    </div>
                </div>
                @else
                <!-- Settings for Author -->
                <a href="{{ route('author.settings.edit') }}" 
                   class="flex items-center px-6 py-3.5 text-sm font-medium transition-colors {{ request()->routeIs('*.settings*') || request()->routeIs('*.profile*') ? 'bg-[#8b1528] text-white font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3.5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Settings
                </a>
                @endif
            </nav>

            <!-- Bottom Log Out -->
            <div class="p-4 border-t border-gray-800/80">
                <form method="POST" action="{{ $isAdmin ? route('admin.logout') : route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-2.5 text-gray-400 hover:text-white hover:bg-white/5 transition-colors text-sm font-medium">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0">
            
            <!-- Top Header Navbar -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10 flex-shrink-0">
                <div class="flex items-center gap-4">
                    <!-- Mobile Hamburger -->
                    <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="md:hidden text-gray-700 hover:text-navy p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <!-- Subheader / Title Tagline -->
                    <div class="text-xs font-heading font-bold uppercase tracking-wider text-[#8b1528] hidden sm:block">
                        @yield('header_tagline', 'University News CMS')
                    </div>
                </div>

                <!-- Center Search Input -->
                <div class="hidden md:flex items-center max-w-sm w-full mx-6">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               placeholder="Search..." 
                               class="w-full pl-9 pr-4 py-1.5 bg-[#f8f9fa] border border-gray-300 text-xs text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0 transition-colors">
                    </div>
                </div>

                <!-- Right Utility Icons & User Info -->
                <div class="flex items-center space-x-5 text-gray-500">
                    <!-- Help Question Icon — triggers onboarding replay -->
                    <button
                        id="onboarding-help-btn"
                        data-tour="help-button"
                        onclick="typeof window.replayOnboarding === 'function' ? window.replayOnboarding() : null"
                        title="Lihat Tutorial Dashboard"
                        class="hover:text-[#8b1528] transition-colors relative group">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="absolute top-full right-0 mt-1.5 w-max bg-[#00081e] text-white text-[10px] font-sans px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap">Lihat Tutorial</span>
                    </button>

                    <div class="h-6 w-px bg-gray-200"></div>

                    <!-- User Pill -->
                    <div class="flex items-center gap-2.5">
                        <span class="text-xs font-sans text-gray-600 hidden sm:inline-block">Logged in as: <strong class="text-navy font-semibold text-gray-900">{{ $currentUser->name }}</strong></span>
                        <div class="w-8 h-8 rounded-full bg-navy text-white flex items-center justify-center font-bold text-xs overflow-hidden border border-gray-200">
                            @if($currentUser->hasAvatar())
                                <img src="{{ $currentUser->avatar_url }}" alt="{{ $currentUser->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="uppercase">{{ substr($currentUser->name, 0, 2) }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Scrollable Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-[#f8f9fa] p-6 lg:p-8">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-600 text-green-800 text-sm font-sans flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-green-600 hover:text-green-800">&times;</button>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-600 text-red-800 text-sm font-sans flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" @click="$el.parentElement.remove()" class="text-red-600 hover:text-red-800">&times;</button>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    <x-alert-toast />
    <x-alert-dialog />
    <x-page-loader />
</body>
</html>
