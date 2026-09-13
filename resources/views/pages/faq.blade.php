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
                Frequently Asked Questions
            </h1>
            <p class="text-gray-500 font-sans text-sm mt-2">Find answers to common questions about University News Portal.</p>
            <div class="w-16 h-1 bg-crimson mx-auto mt-4 rounded-full"></div>
        </div>

        @php
            $allFaqs = $faqs->flatten();
        @endphp

        @if($allFaqs->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center text-gray-500 font-sans">
                No FAQ items found.
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden divide-y divide-gray-100" x-data="{ activeFaq: null }">
                @foreach($allFaqs as $faq)
                <div class="transition-all">
                    <button @click="activeFaq = (activeFaq === {{ $faq->id }} ? null : {{ $faq->id }})" class="w-full text-left px-5 py-4 font-sans font-medium text-sm text-navy flex justify-between items-center hover:bg-gray-50 transition-colors">
                        <span>{{ $faq->question }}</span>
                        <svg class="w-4 h-4 text-crimson flex-shrink-0 ml-3 transform transition-transform duration-200" :class="{ 'rotate-180': activeFaq === {{ $faq->id }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="activeFaq === {{ $faq->id }}" x-collapse class="px-5 pb-4 pt-1 border-t border-gray-100 bg-gray-50/50 text-gray-600 font-sans leading-relaxed text-sm">
                        {!! nl2br(e($faq->answer)) !!}
                    </div>
                </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
