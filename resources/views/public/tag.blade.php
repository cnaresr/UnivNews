@extends('layouts.public')

@section('title', 'Tags: ' . $tag->name . ' - University News')

@section('content')
<main class="bg-[#FCF8F9] min-h-screen pb-20">
    <div class="max-w-[1280px] w-full mx-auto px-6 md:px-10 py-12">
        
        <!-- Page Header -->
        <div class="border-b border-[#C5C6CF] pb-4 mb-8">
            <h1 class="font-heading font-semibold text-3xl text-[#00081E]">TAGS: {{ strtoupper($tag->name) }}</h1>
            <p class="text-[#44464E] font-sans text-sm mt-2">{{ $articles->total() }} artikel ditemukan</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-12">
            
            <!-- Main Content (Left) -->
            <div>
                @if($articles->count() > 0)
                <!-- Articles Grid (Masonry 3 Column) -->
                <div class="columns-1 sm:columns-2 lg:columns-3 gap-6">
                    @foreach($articles as $article)
                    <a href="{{ route('article', $article->slug) }}" class="block group bg-white border border-[#C5C6CF] hover:shadow-md transition-shadow break-inside-avoid mb-6">
                        <!-- Thumbnail -->
                        <div class="w-full bg-gray-100 border-b border-[#C5C6CF] overflow-hidden">
                            @if($article->featured_image_path)
                                @if(Str::startsWith($article->featured_image_path, ['http://', 'https://']))
                                    <img src="{{ $article->featured_image_path }}" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105" alt="{{ $article->title }}">
                                @else
                                    <img src="{{ asset('storage/' . $article->featured_image_path) }}" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105" alt="{{ $article->title }}">
                                @endif
                            @else
                                <img src="https://picsum.photos/seed/tag{{ $article->id }}/800/533" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105" alt="Article">
                            @endif
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6">
                            <div class="flex items-center space-x-3 mb-3">
                                <span class="text-xs font-bold uppercase tracking-widest text-crimson" style="font-family: 'Work Sans', sans-serif;">{{ $article->tags->first() ? $article->tags->first()->name : $article->category->name }}</span>
                                <span class="text-xs text-gray-500 font-medium" style="font-family: 'Work Sans', sans-serif;">{{ $article->published_at->format('M d') }}</span>
                            </div>
                            <h3 class="text-[18px] font-bold mb-3 group-hover:text-crimson transition-colors text-navy" style="font-family: Montserrat, sans-serif; line-height: 1.3;">
                                {{ $article->title }}
                            </h3>
                            <p class="text-[14px] text-gray-600 line-clamp-3" style="font-family: 'Source Serif 4', serif; line-height: 1.6;">
                                {{ $article->excerpt }}
                            </p>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $articles->links() }}
                </div>
                @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center h-64 text-center border border-[#C5C6CF] border-dashed rounded-lg bg-gray-50">
                    <svg class="w-12 h-12 text-[#C5C6CF] mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <p class="text-[#44464E] font-medium font-sans mb-1">No articles found with tag "{{ $tag->name }}"</p>
                    <p class="text-[#C5C6CF] text-sm font-sans">Try exploring other tags or return to the homepage.</p>
                    <a href="{{ route('home') }}" class="mt-4 text-crimson text-sm font-bold uppercase tracking-wider hover:underline">Back to Home</a>
                </div>
                @endif
            </div>

            <!-- Sidebar (Right) -->
            <aside class="space-y-8">
                
                <!-- Tag Cloud -->
                <div class="bg-[#F0EDEE] p-6 rounded-lg">
                    <h4 class="font-heading font-bold text-[22px] text-[#00081E] mb-6 border-b border-[#C5C6CF] pb-2">All Tags</h4>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $allTags = \App\Models\Tag::withCount(['articles' => function($q) {
                                $q->where('status', 'published')
                                  ->whereNotNull('published_at')
                                  ->where('published_at', '<=', now());
                            }])->orderBy('name')->get();
                        @endphp
                        @foreach($allTags as $t)
                        <a href="{{ route('tag', $t->name) }}" 
                           class="px-3 py-1.5 text-xs font-sans font-medium rounded transition-colors border {{ $t->id === $tag->id ? 'bg-crimson text-white border-crimson' : 'bg-white text-[#44464E] border-[#C5C6CF] hover:bg-crimson hover:text-white hover:border-crimson' }}">
                            {{ $t->name }} <span class="opacity-60">({{ $t->articles_count }})</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Trending Articles -->
                <div class="bg-[#F0EDEE] p-6 rounded-lg">
                    <div class="mb-6 border-b border-[#C5C6CF] pb-2">
                        <h4 class="font-heading font-bold text-[22px] text-[#00081E]">Artikel Populer</h4>
                    </div>
                    
                    <ul class="space-y-5">
                        @php
                            $popular = \App\Models\Article::where('status', 'published')
                                ->whereNotNull('published_at')
                                ->where('published_at', '<=', now())
                                ->orderBy('views_count', 'desc')
                                ->limit(4)
                                ->get();
                        @endphp
                        @foreach($popular as $popArticle)
                        <li class="group">
                            <a href="{{ route('article', $popArticle->slug) }}" class="flex items-start">
                                <div class="bg-transparent text-center min-w-[36px] mr-4 pt-1">
                                    <div class="text-[10px] font-bold text-crimson uppercase tracking-widest leading-none mb-1">{{ $popArticle->published_at->format('M') }}</div>
                                    <div class="text-[20px] font-heading font-bold text-crimson leading-none">{{ $popArticle->published_at->format('d') }}</div>
                                </div>
                                <div>
                                    <span class="font-heading font-bold text-[15px] text-[#00081E] group-hover:text-crimson transition-colors leading-snug block">{{ $popArticle->title }}</span>
                                    <span class="text-[11px] text-[#7687B2] font-sans mt-1 block">{{ number_format($popArticle->views_count) }} views</span>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Advertisement -->
                <div>
                    <div class="flex items-center mb-3">
                        <span class="w-2 h-2 rounded-full bg-crimson mr-2"></span>
                        <h4 class="font-sans font-bold text-[10px] text-[#C5C6CF] uppercase tracking-widest">ADVERTISEMENT</h4>
                    </div>
                    <div class="bg-[#EAE7E8] rounded-lg h-[240px] flex flex-col items-center justify-center text-[#C5C6CF] border border-[#C5C6CF] border-dashed">
                        <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="font-sans font-bold text-xs tracking-wider uppercase opacity-70">SPONSOR CONTENT</span>
                    </div>
                </div>

            </aside>
        </div>
    </div>
</main>
@endsection
