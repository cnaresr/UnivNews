@extends('layouts.public')

@section('title', $query ? 'Search: ' . $query . ' - University News' : 'Search Articles - University News')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Search Header & In-Page Search Bar -->
    <div class="bg-white border border-gray-200 shadow-sm p-6 sm:p-8 rounded-none mb-8">
        <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-[#00081E] uppercase tracking-tight mb-2">
            @if(!empty($query))
                Search Results for: <span class="text-crimson">"{{ $query }}"</span>
            @else
                Explore All News & Articles
            @endif
        </h1>
        <p class="text-gray-600 text-sm mb-6 font-sans">
            Found <span class="font-bold text-[#00081E]">{{ number_format($articles->total()) }}</span> {{ Str::plural('article', $articles->total()) }}
            @if(!empty($query)) matching your query @endif
        </p>

        <!-- Search Input Form -->
        <form action="{{ route('search') }}" method="GET" class="relative flex flex-col sm:flex-row gap-2.5">
            <input type="hidden" name="sort" value="{{ $sort }}">
            @if(!empty($selectedCategory) && $selectedCategory !== 'all')
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
            @endif

            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-[#00081E]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" 
                       name="q" 
                       value="{{ $query }}" 
                       placeholder="Search by keywords, title, topics, researchers..." 
                       style="padding-left: 3.25rem; padding-right: 2.75rem;"
                       class="w-full bg-gray-50 border border-gray-300 text-[#00081E] font-medium placeholder-gray-400 py-3 rounded-none text-sm font-sans focus:outline-none focus:bg-white focus:border-crimson focus:ring-1 focus:ring-crimson transition-all shadow-inner">
                @if(!empty($query))
                    <a href="{{ route('search', array_filter(['category' => $selectedCategory !== 'all' ? $selectedCategory : null, 'sort' => $sort])) }}" 
                       class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-crimson transition-colors"
                       title="Clear search">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>

            <button type="submit" 
                    class="bg-crimson hover:bg-red-700 text-white font-heading font-bold text-xs uppercase tracking-wider px-7 py-3 rounded-none transition-colors flex items-center justify-center gap-2 shadow">
                <span>Search</span>
            </button>
        </form>
    </div>

    <!-- Filters & Sort Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 mb-8 border-b border-gray-200">
        <!-- Category Filter Pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 scrollbar-none">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400 shrink-0">Category:</span>
            
            <a href="{{ route('search', array_filter(['q' => $query, 'sort' => $sort])) }}" 
               class="shrink-0 px-3 py-1.5 text-xs font-heading font-bold rounded-none transition-colors {{ $selectedCategory === 'all' ? 'bg-navy text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All Categories
            </a>

            @foreach($categories as $category)
                @if($category->articles_count > 0)
                    <a href="{{ route('search', array_filter(['q' => $query, 'sort' => $sort, 'category' => $category->slug])) }}" 
                       class="shrink-0 px-3 py-1.5 text-xs font-heading font-bold rounded-none transition-colors flex items-center gap-1.5 {{ $selectedCategory === $category->slug ? 'bg-crimson text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <span>{{ $category->name }}</span>
                        <span class="text-[10px] px-1.5 py-0.2 rounded-none {{ $selectedCategory === $category->slug ? 'bg-white/25 text-white' : 'bg-gray-200 text-gray-600' }}">
                            {{ $category->articles_count }}
                        </span>
                    </a>
                @endif
            @endforeach
        </div>

        <!-- Sort Controls -->
        <div class="flex items-center gap-2 shrink-0 self-end md:self-auto">
            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Sort by:</span>
            <div class="inline-flex rounded-none shadow-sm border border-gray-200 bg-white p-0.5">
                <a href="{{ route('search', array_filter(['q' => $query, 'sort' => 'relevance', 'category' => $selectedCategory !== 'all' ? $selectedCategory : null])) }}" 
                   class="px-3 py-1 text-xs font-heading font-bold rounded-none transition-colors {{ $sort === 'relevance' ? 'bg-navy text-white' : 'text-gray-600 hover:text-navy' }}">
                    Relevance
                </a>
                <a href="{{ route('search', array_filter(['q' => $query, 'sort' => 'latest', 'category' => $selectedCategory !== 'all' ? $selectedCategory : null])) }}" 
                   class="px-3 py-1 text-xs font-heading font-bold rounded-none transition-colors {{ $sort === 'latest' ? 'bg-navy text-white' : 'text-gray-600 hover:text-navy' }}">
                    Latest
                </a>
            </div>
        </div>
    </div>

    <!-- Search Results Articles List -->
    <div class="space-y-6">
        @forelse($articles as $article)
            @php
                // Safe Title Highlighting
                $highlightedTitle = e($article->title);
                if (!empty($query)) {
                    $terms = array_filter(preg_split('/[\s,\.\?\!\-\_\+]+/', $query));
                    foreach ($terms as $term) {
                        if (mb_strlen($term) >= 2) {
                            $escapedTerm = preg_quote($term, '/');
                            $highlightedTitle = preg_replace('/(' . $escapedTerm . ')/iu', '<mark class="bg-amber-100 text-navy font-bold px-0.5 rounded">$1</mark>', $highlightedTitle);
                        }
                    }
                }
            @endphp

            <article class="bg-white rounded-none border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <div class="p-6 flex flex-col md:flex-row gap-6">
                    <!-- Featured Image -->
                    <a href="{{ route('article', $article->slug) }}" class="aspect-video md:w-64 bg-gray-100 relative shrink-0 overflow-hidden rounded-none group block">
                        @if($article->featured_image_path)
                            @if(Str::startsWith($article->featured_image_path, ['http://', 'https://']))
                                <img src="{{ $article->featured_image_path }}" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <img src="{{ asset('storage/' . $article->featured_image_path) }}" onerror="this.src='{{ asset($article->featured_image_path) }}'" alt="{{ $article->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @endif
                        @else
                            <div class="absolute inset-0 flex items-center justify-center text-gray-400 font-serif italic text-sm">No Image</div>
                        @endif
                    </a>

                    <!-- Article Details -->
                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <!-- Category / Tags Badge -->
                            <div class="flex items-center gap-2 mb-2 flex-wrap">
                                <a href="{{ route('category', $article->category->slug) }}" class="text-crimson hover:underline font-heading font-bold text-xs uppercase tracking-wider">
                                    {{ $article->category->name }}
                                </a>
                                @if($article->tags->isNotEmpty())
                                    <span class="text-gray-300">&bull;</span>
                                    @foreach($article->tags->take(2) as $tag)
                                        <a href="{{ route('tag', $tag->name) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-[11px] px-2 py-0.5 rounded font-sans transition-colors">
                                            #{{ $tag->name }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>

                            <!-- Title -->
                            <h2 class="text-xl md:text-2xl font-serif font-bold text-navy mb-2.5 leading-snug">
                                <a href="{{ route('article', $article->slug) }}" class="hover:text-crimson transition-colors duration-200">
                                    {!! $highlightedTitle !!}
                                </a>
                            </h2>

                            <!-- Snippet or Excerpt -->
                            <div class="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                                @if(!empty($article->search_snippet) && str_contains($article->search_snippet, '<mark>'))
                                    <div class="bg-amber-50/60 border-l-2 border-amber-300 pl-3 py-1 text-gray-700 italic">
                                        &ldquo;{!! str_replace(['<mark>', '</mark>'], ['<mark class="bg-amber-200 text-navy font-semibold px-0.5 rounded not-italic">', '</mark>'], $article->search_snippet) !!}&rdquo;
                                    </div>
                                @else
                                    <p>{{ $article->excerpt }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Meta -->
                        <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100 mt-2">
                            <div class="flex items-center gap-2">
                                <span class="font-medium text-gray-700">{{ $article->user->name ?? 'UnivNews Staff' }}</span>
                                <span>&middot;</span>
                                <span>{{ $article->published_at ? $article->published_at->format('M j, Y') : '-' }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span>{{ number_format($article->views_count) }} views</span>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="py-16 text-center bg-gray-50 border border-dashed border-gray-200 rounded-lg">
                <div class="w-16 h-16 mx-auto mb-4 text-gray-300 flex items-center justify-center bg-white rounded-full shadow-sm">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h3 class="text-xl font-heading font-bold text-navy mb-2">No articles found</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6 text-sm">
                    @if(!empty($query))
                        We couldn't find any results matching "<span class="font-semibold text-navy">{{ $query }}</span>"
                        @if($selectedCategory !== 'all') in this category @endif.
                    @else
                        There are no articles available right now.
                    @endif
                </p>
                <div class="flex flex-wrap justify-center gap-3">
                    @if(!empty($query) || $selectedCategory !== 'all')
                        <a href="{{ route('search') }}" class="inline-flex items-center px-4 py-2 text-xs font-heading font-bold uppercase tracking-wider text-white bg-navy hover:bg-crimson rounded transition-colors">
                            Clear Filters & Search
                        </a>
                    @endif
                    <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 text-xs font-heading font-bold uppercase tracking-wider text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded transition-colors">
                        Back to Homepage
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($articles->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $articles->links() }}
        </div>
    @endif

</div>
@endsection
