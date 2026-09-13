@extends('layouts.public')

@section('title', $article->title . ' - University News')

@section('og_meta')
<meta property="og:title"       content="{{ $article->title }}">
<meta property="og:description" content="{{ $article->excerpt ?? Str::limit(strip_tags($article->content), 160) }}">
<meta property="og:url"         content="{{ url()->current() }}">
<meta property="og:type"        content="article">
@if($article->featured_image_path)
    @if(Str::startsWith($article->featured_image_path, ['http://', 'https://']))
        <meta property="og:image" content="{{ $article->featured_image_path }}">
    @else
        <meta property="og:image" content="{{ asset('storage/' . $article->featured_image_path) }}">
    @endif
@endif
@endsection

@section('content')
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative">
    
    <!-- Back Button -->
    <a href="javascript:history.back()" class="absolute top-4 left-4 sm:top-12 sm:-left-4 md:-left-12 flex items-center justify-center w-8 h-8 bg-white border border-gray-200 text-gray-500 hover:text-[#8b1528] hover:bg-gray-50 rounded-full shadow-sm transition-all z-10" title="Back">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </a>

    <!-- Header -->
    <header class="mb-10 text-center">
        <div class="flex flex-wrap items-center justify-center gap-3 mb-4">
            <a href="{{ route('category', $article->category->slug) }}" class="text-crimson font-heading font-bold text-sm uppercase tracking-wider hover:underline inline-block">
                @if($article->category->slug === 'events' && $article->event_type)
                    {{ $article->event_type }}
                @else
                    {{ $article->tags->first() ? $article->tags->first()->name : $article->category->name }}
                @endif
            </a>
            @if($isBoosted)
                <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-xs font-black uppercase tracking-wider bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-400 text-[#00081E] shadow-[0_0_15px_rgba(245,158,11,0.4)] animate-pulse">
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                    </svg>
                    Boosted Article
                </span>
            @endif
        </div>

        <h1 class="text-4xl md:text-6xl font-serif font-bold text-navy leading-tight mb-6">
            {{ $article->title }}
        </h1>

        <div class="text-gray-600 font-sans flex flex-wrap items-center justify-center gap-3 sm:gap-4 text-sm sm:text-base">
            <span class="font-medium flex items-center gap-1.5 {{ $isBoosted ? 'text-amber-800 font-semibold' : 'text-navy' }}">
                By <a href="#author-section" class="hover:underline font-bold">{{ $article->user->name }}</a>
                @if($article->user->university) 
                    <span class="text-gray-500 font-normal">({{ $article->user->university->abbreviation ?? $article->user->university->name }})</span>
                @endif
                @if($isBoosted)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-extrabold bg-gradient-to-r from-amber-400 to-yellow-400 text-[#00081E] uppercase tracking-wider shadow-sm">
                        ⚡ Featured Author
                    </span>
                @endif
            </span>
            <span class="text-gray-300">&bull;</span>
            <span>{{ $article->published_at ? $article->published_at->format('F j, Y') : 'Unpublished' }}</span>
            <span class="text-gray-300">&bull;</span>
            <span>{{ number_format($article->views_count) }} Views</span>
        </div>
    </header>

    <!-- Featured Image -->
    @if($article->featured_image_path)
    <div class="aspect-video bg-gray-100 w-full relative mb-12 shadow-md overflow-hidden border border-border-main">
        @if(Str::startsWith($article->featured_image_path, ['http://', 'https://']))
            <img src="{{ $article->featured_image_path }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
        @else
            <img src="{{ asset('storage/' . $article->featured_image_path) }}" onerror="this.src='{{ asset($article->featured_image_path) }}'" alt="{{ $article->title }}" class="w-full h-full object-cover">
        @endif
    </div>
    @endif

    <!-- Content -->
    <div class="prose prose-lg prose-blue max-w-none font-serif text-gray-800 leading-relaxed mb-12">
        {!! nl2br(e($article->content)) !!}
    </div>

    <!-- Event Registration -->
    @if($article->hasActiveRegistration())
    <div class="mb-8">
        <a href="{{ $article->registration_link }}" target="_blank" rel="noopener noreferrer" class="block w-full text-center bg-[#B71032] text-white font-sans font-bold uppercase tracking-wider py-4 rounded-full hover:bg-red-800 transition-colors shadow-md text-sm">
            Daftar Sekarang
        </a>
        @if($article->registration_deadline)
        <p class="text-center text-xs text-gray-500 mt-2">
            Pendaftaran ditutup: {{ $article->registration_deadline->translatedFormat('d F Y, H:i') }} WIB
        </p>
        @endif
    </div>
    @endif

    <!-- Tags -->
    @if($article->tags->count() > 0)
    <div class="border-t border-b border-gray-200 py-4 mb-10 flex items-center gap-4 flex-wrap">
        <span class="font-heading font-bold text-navy uppercase text-sm">Tags:</span>
        @foreach($article->tags as $tag)
            <a href="{{ route('tag', $tag->name) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 text-sm font-sans rounded-full transition-colors">{{ $tag->name }}</a>
        @endforeach
    </div>
    @endif

    <!-- Author Section -->
    <div id="author-section" class="my-12">
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 sm:p-8 text-gray-800">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left">
                <!-- Avatar -->
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden flex-shrink-0 bg-gray-200 border-2 border-gray-300">
                    @if($article->user->avatar_path)
                        @if(Str::startsWith($article->user->avatar_path, ['http://', 'https://']))
                            <img src="{{ $article->user->avatar_path }}" alt="{{ $article->user->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('storage/' . $article->user->avatar_path) }}" onerror="this.src='{{ asset($article->user->avatar_path) }}'" alt="{{ $article->user->name }}" class="w-full h-full object-cover">
                        @endif
                    @else
                        <div class="w-full h-full bg-[#00081E] text-white flex items-center justify-center font-bold text-2xl rounded-full">
                            {{ strtoupper(substr($article->user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <!-- Details -->
                <div class="flex-grow">
                    <div class="flex flex-wrap items-center justify-center sm:justify-between gap-2 mb-2">
                        <span class="text-xs font-bold uppercase tracking-widest text-crimson font-sans">About the Author</span>
                        @if($article->user->university)
                            <span class="text-xs text-gray-500 font-sans font-medium">
                                {{ $article->user->university->name }}
                            </span>
                        @endif
                    </div>
                    <h3 class="text-2xl font-bold font-serif text-navy mb-1">
                        {{ $article->user->name }}
                    </h3>
                    @if($article->user->department)
                        <p class="text-xs text-gray-600 font-sans mb-3">
                            {{ $article->user->department }}
                        </p>
                    @endif
                    <p class="text-sm text-gray-600 font-sans leading-relaxed">
                        {{ $article->user->author_bio ?? 'Academic contributor and verified researcher at University News Portal.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Engagement: Like, Comment & Share --}}
    @include('components.article-engagement', [
        'article'      => $article,
        'userHasLiked' => $userHasLiked,
        'likeCount'    => $likeCount,
    ])

    <!-- Related Articles -->
    @if($relatedArticles->count() > 0)
    <div class="mt-16 bg-gray-50 p-8 border border-gray-200">
        <h3 class="text-2xl font-heading font-bold text-navy uppercase mb-8 border-b-2 border-navy pb-2 inline-block">Related Reading</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($relatedArticles as $related)
            <a href="{{ route('article', $related->slug) }}" class="block group">
                <div class="aspect-[3/2] bg-gray-200 relative mb-4 overflow-hidden">
                    @if($related->featured_image_path)
                        @if(Str::startsWith($related->featured_image_path, ['http://', 'https://']))
                            <img src="{{ $related->featured_image_path }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <img src="{{ asset('storage/' . $related->featured_image_path) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @endif
                    @else
                        <div class="w-full h-full bg-navy/10 flex items-center justify-center text-navy/40 font-bold font-serif text-2xl">UN</div>
                    @endif
                </div>
                <h4 class="font-serif font-bold text-navy text-lg group-hover:text-crimson transition-colors line-clamp-2 mb-2">
                    {{ $related->title }}
                </h4>
                <div class="text-xs text-gray-500">{{ $related->published_at->format('M j, Y') }}</div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</article>

@endsection
