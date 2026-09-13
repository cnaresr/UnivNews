@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-2xl mx-auto px-4 sm:px-6">
        
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
        <div class="mb-8 text-center">
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-navy tracking-tight">
                {{ $page->title ?? 'About Us' }}
            </h1>
            <div class="w-16 h-1 bg-crimson mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-10 mb-8 space-y-8">
            <!-- Platform Overview -->
            <div>
                <h2 class="font-heading font-bold text-xl text-navy mb-4">Who We Are</h2>
                <div class="text-gray-700 font-sans leading-relaxed space-y-4">
                    {!! nl2br(e($page->content ?? 'University News Portal provides authoritative reporting and intellectual discourse for the academic community.')) !!}
                </div>
            </div>

            @if(!empty($page->vision))
            <hr class="border-gray-100">
            <!-- Vision -->
            <div>
                <h2 class="font-heading font-bold text-xl text-navy mb-4">Our Vision</h2>
                <p class="text-gray-700 font-sans leading-relaxed bg-red-50/50 border-l-4 border-crimson p-4 rounded-r">
                    {!! nl2br(e($page->vision)) !!}
                </p>
            </div>
            @endif

            @if(!empty($page->mission))
            <hr class="border-gray-100">
            <!-- Mission -->
            <div>
                <h2 class="font-heading font-bold text-xl text-navy mb-4">Our Mission</h2>
                <ul class="space-y-3">
                    @foreach(explode("\n", $page->mission) as $missionItem)
                        @if(trim($missionItem) !== '')
                        <li class="flex items-start text-gray-700 font-sans">
                            <span class="w-2 h-2 bg-crimson rounded-full mt-2 mr-3 shrink-0"></span>
                            <span>{{ trim($missionItem) }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
