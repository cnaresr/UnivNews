@extends('layouts.app')

@section('base_content')
    <header class="bg-navy text-white shadow-md sticky top-0 z-50">
        <div class="max-w-[1280px] w-full mx-auto px-6 md:px-10">
            <div class="flex justify-between items-center h-20">
                
                <!-- Left: Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="bg-crimson text-white w-10 h-10 flex items-center justify-center font-heading font-bold text-2xl transition-transform group-hover:scale-105">
                            U
                        </div>
                        <span class="font-heading font-bold text-2xl tracking-tight">
                            University<span class="font-normal">News</span>
                        </span>
                    </a>
                </div>

                <!-- Right: Back & Home Merged Pill -->
                <div class="flex items-center justify-end flex-shrink-0">
                    <nav class="inline-flex items-center bg-white/10 hover:bg-white/15 backdrop-blur-sm border border-white/15 rounded-full p-1 text-xs font-bold tracking-wider uppercase text-white/90 shadow-sm transition-all">
                        <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}'" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full hover:bg-white/15 hover:text-white transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            <span>BACK</span>
                        </button>
                        <span class="w-px h-3.5 bg-white/20 mx-0.5"></span>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full hover:bg-white/15 hover:text-amber-300 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span>HOME</span>
                        </a>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow bg-[#FCF8F9] min-h-[calc(100vh-80px-300px)]">
        @yield('content')
    </main>

    <footer class="bg-navy text-white mt-auto py-12 border-t border-crimson">
        <div class="max-w-[1280px] w-full mx-auto px-6 md:px-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                {{-- Column 1: Brand Info --}}
                <div class="lg:col-span-1">
                    <h2 class="font-heading font-bold text-2xl tracking-tight mb-4" style="color: #FFFFFF;">
                        University News
                    </h2>
                    <p class="font-sans text-sm leading-relaxed" style="color: #7687B2;">
                        Providing authoritative reporting and intellectual discourse for the academic community since 1893.
                    </p>
                </div>

                {{-- Column 2: Newsletter --}}
                <div>
                    <h3 class="font-heading font-bold uppercase tracking-wider mb-4 text-crimson" style="color: #DC2626;">NEWSLETTER</h3>
                    <p class="font-sans text-sm leading-relaxed mb-4" style="color: #7687B2;">
                        Subscribe to get weekly digest of top stories across campuses, early access to investigative reports, and exclusive research archives.
                    </p>
                    <button type="button" onclick="window.showInfoAlert('Info! Feature coming soon', 'Newsletter subscription is under development and will be available soon.');" style="background-color: #DC2626; color: #FFFFFF; font-weight: 600; font-size: 0.8125rem; padding: 0.45rem 0.9rem; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; width: auto; border: none; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#B91C1C'" onmouseout="this.style.backgroundColor='#DC2626'">
                        <svg class="mr-2" fill="currentColor" viewBox="0 0 20 20" style="width: 0.875rem; height: 0.875rem; margin-right: 0.4rem;">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        Subscribe
                    </button>
                </div>

                {{-- Column 3: Social --}}
                <div>
                    @php
                        $adminUser = \App\Models\User::where('role', 'admin')->first();
                        $socials = $adminUser ? ($adminUser->social_links ?? []) : [];
                    @endphp
                    <h3 class="font-heading font-bold uppercase tracking-wider mb-4 text-crimson" style="color: #DC2626;">SOCIAL</h3>
                    <ul class="space-y-2 text-gray-400 font-sans text-sm">
                        <li><a href="{{ !empty($socials['instagram']) ? 'https://instagram.com/'.ltrim($socials['instagram'], '@') : '#' }}" {!! !empty($socials['instagram']) ? 'target="_blank" rel="noopener"' : 'onclick="window.showInfoAlert(\'Info! Link coming soon\', \'This social media profile will be available soon. Please check back later!\'); return false;"' !!} class="hover:text-white transition-colors">Instagram</a></li>
                        <li><a href="{{ !empty($socials['threads']) ? 'https://threads.net/@'.ltrim($socials['threads'], '@') : '#' }}" {!! !empty($socials['threads']) ? 'target="_blank" rel="noopener"' : 'onclick="window.showInfoAlert(\'Info! Link coming soon\', \'This social media profile will be available soon. Please check back later!\'); return false;"' !!} class="hover:text-white transition-colors">Threads</a></li>
                        <li><a href="{{ !empty($socials['linkedin']) ? 'https://linkedin.com/in/'.$socials['linkedin'] : '#' }}" {!! !empty($socials['linkedin']) ? 'target="_blank" rel="noopener"' : 'onclick="window.showInfoAlert(\'Info! Link coming soon\', \'This social media profile will be available soon. Please check back later!\'); return false;"' !!} class="hover:text-white transition-colors">LinkedIn</a></li>
                        <li><a href="{{ !empty($socials['twitter']) ? 'https://x.com/'.ltrim($socials['twitter'], '@') : '#' }}" {!! !empty($socials['twitter']) ? 'target="_blank" rel="noopener"' : 'onclick="window.showInfoAlert(\'Info! Link coming soon\', \'This social media profile will be available soon. Please check back later!\'); return false;"' !!} class="hover:text-white transition-colors">X / Twitter</a></li>
                    </ul>
                </div>

                {{-- Column 4: About Us --}}
                <div>
                    <h3 class="font-heading font-bold uppercase tracking-wider mb-4 text-crimson" style="color: #DC2626;">ABOUT US</h3>
                    <ul class="space-y-2 text-gray-400 font-sans text-sm">
                        <li><a href="{{ route('page.about') }}" class="hover:text-white transition-colors">Our Story & Mission</a></li>
                    </ul>
                </div>

                {{-- Column 5: Help & Support --}}
                <div>
                    <h3 class="font-heading font-bold uppercase tracking-wider mb-4 text-crimson" style="color: #DC2626;">HELP & SUPPORT</h3>
                    <ul class="space-y-2 text-gray-400 font-sans text-sm">
                        <li><a href="{{ route('page.faq') }}" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="{{ route('page.contact') }}" class="hover:text-white transition-colors">Contact</a></li>
                        <li><a href="{{ route('page.privacy') }}" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" onclick="window.showInfoAlert('Info! Feature coming soon', 'Accessibility options are currently in development.'); return false;" class="hover:text-white transition-colors">Accessibility</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-12 pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center text-sm font-sans" style="color: #7687B2;">
                <p>&copy; {{ date('Y') }} University News Portal. All academic rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" onclick="window.showInfoAlert('Info! Feature coming soon', 'Terms of Service options are currently in development.'); return false;" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
@endsection
