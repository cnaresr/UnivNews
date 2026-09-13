@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-[900px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Floating Merged Navigation Pill against background -->
        <div class="mb-6 flex items-center justify-start">
            <nav class="inline-flex items-center bg-white/80 hover:bg-white backdrop-blur-sm border border-gray-200 rounded-full p-1 shadow-2xs text-xs font-semibold text-gray-600 transition-all">
                <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ route('home') }}'" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full hover:bg-gray-100 hover:text-[#8b1528] transition-colors">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Back</span>
                </button>
                <span class="w-px h-3.5 bg-gray-200 mx-0.5"></span>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full hover:bg-gray-100 hover:text-[#8b1528] transition-colors">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Home</span>
                </a>
            </nav>
        </div>

        <!-- Header -->
        <div class="mb-10 text-center">
            <h1 class="font-heading font-extrabold text-3xl sm:text-4xl text-navy tracking-tight">
                Contact Information
            </h1>
            <p class="text-gray-500 font-sans text-sm mt-2">Get in touch with University News Portal administration team.</p>
            <div class="w-16 h-1 bg-crimson mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-10 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- WhatsApp -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center hover:border-crimson transition-colors group">
                    <div class="w-12 h-12 bg-crimson/10 text-crimson rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading font-bold text-navy text-lg mb-1">WhatsApp</h3>
                    <p class="text-xs text-gray-500 font-sans mb-3">Direct Support Line</p>
                    @php
                        $cleanWa = preg_replace('/[^0-9]/', '', $contactWhatsapp);
                    @endphp
                    <a href="https://wa.me/{{ $cleanWa }}" target="_blank" rel="noopener" class="text-crimson hover:underline font-sans font-medium text-sm">
                        {{ $contactWhatsapp }}
                    </a>
                </div>

                <!-- Email -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center hover:border-crimson transition-colors group">
                    <div class="w-12 h-12 bg-crimson/10 text-crimson rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading font-bold text-navy text-lg mb-1">Email</h3>
                    <p class="text-xs text-gray-500 font-sans mb-3">General Inquiries</p>
                    <a href="mailto:{{ $contactEmail }}" class="text-crimson hover:underline font-sans font-medium text-sm">
                        {{ $contactEmail }}
                    </a>
                </div>

                <!-- Address -->
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 text-center hover:border-crimson transition-colors group">
                    <div class="w-12 h-12 bg-crimson/10 text-crimson rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-heading font-bold text-navy text-lg mb-1">Office Location</h3>
                    <p class="text-xs text-gray-500 font-sans mb-3">Headquarters</p>
                    <p class="text-gray-700 font-sans text-xs leading-relaxed">
                        {!! nl2br(e($contactAddress)) !!}
                    </p>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
