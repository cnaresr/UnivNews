@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <!-- Narrower, centered container -->
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

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-10">
            <!-- Title -->
            <h1 class="font-heading font-extrabold text-2xl sm:text-3xl text-navy tracking-tight mb-6 border-b border-gray-100 pb-4">
                {{ $page->title ?? 'Privacy Policy' }}
            </h1>

            <!-- Inner Privacy Content -->
            <div class="space-y-6 text-gray-700 font-sans leading-relaxed text-sm sm:text-base">
                @php
                    $rawContent = trim($page->content ?? '');
                    // Separate by double line break into sections
                    $blocks = preg_split('/\n\s*\n/', $rawContent);
                @endphp

                @foreach($blocks as $block)
                    @php
                        $lines = array_values(array_filter(array_map('trim', explode("\n", $block))));
                        if (empty($lines)) continue;
                        $firstLine = $lines[0];
                        $isBulletList = str_starts_with($firstLine, '-') || str_starts_with($firstLine, '•');
                        $isHeading = count($lines) == 1 && !$isBulletList && strlen($firstLine) < 70 && !str_ends_with($firstLine, '.');
                    @endphp

                    @if($isHeading)
                        <h2 class="font-heading font-bold text-lg sm:text-xl text-navy mt-6 mb-2">{{ $firstLine }}</h2>
                    @elseif($isBulletList)
                        <ul class="list-disc pl-5 space-y-2 text-gray-700 my-2">
                            @foreach($lines as $line)
                                <li>{{ ltrim($line, '-• ') }}</li>
                            @endforeach
                        </ul>
                    @else
                        <div class="space-y-2">
                            @foreach($lines as $line)
                                @if(str_starts_with($line, '-') || str_starts_with($line, '•'))
                                    <ul class="list-disc pl-5 space-y-1 text-gray-700 my-1">
                                        <li>{{ ltrim($line, '-• ') }}</li>
                                    </ul>
                                @else
                                    <p class="leading-relaxed">
                                        @if(str_contains($line, 'Kontak Kami'))
                                            {!! str_replace('Kontak Kami', '<a href="'.route('page.contact').'" class="text-crimson hover:underline font-medium">Kontak Kami</a>', e($line)) !!}
                                        @elseif(str_contains($line, 'Contact Us'))
                                            {!! str_replace('Contact Us', '<a href="'.route('page.contact').'" class="text-crimson hover:underline font-medium">Contact Us</a>', e($line)) !!}
                                        @else
                                            {{ $line }}
                                        @endif
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>

        </div>

    </div>
</div>
@endsection
