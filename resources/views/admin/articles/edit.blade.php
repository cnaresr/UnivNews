@extends('layouts.cms')

@section('title', 'Edit Article - University News')
@section('header_tagline', 'EDIT ARTICLE - UNIVERSITY NEWS')

@section('content')
<div class="max-w-6xl mx-auto" x-data="articleEditFormHandler()">
    
    <!-- Top Back Link & Heading -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.articles.index') }}" class="inline-flex items-center text-xs font-semibold text-gray-500 hover:text-[#8b1528] mb-2 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Articles
            </a>
            <h1 class="text-3xl font-extrabold font-heading text-[#00081e] tracking-tight">Edit Article</h1>
        </div>

        @if($article->isPendingReview())
            <a href="{{ route('admin.articles.review', $article) }}" class="px-4 py-2 bg-[#8b1528] hover:bg-[#721120] text-white text-xs font-semibold uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Open Review Mode
            </a>
        @endif
    </div>

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-600 text-red-700 text-sm">
        <p class="font-bold">Please check the form for errors:</p>
        <ul class="list-disc pl-5 mt-1 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" id="articleEditForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" id="formStatus" value="{{ $article->status }}">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Column: Main Editor Fields -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Title Field -->
                <div class="bg-white border border-gray-200 p-6 shadow-sm">
                    <label for="title" class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-2">
                        Title <span class="text-[#8b1528]">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $article->title) }}" 
                           class="w-full bg-[#f8f9fa] border border-gray-300 px-4 py-3 text-base text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0" 
                           required>
                </div>

                <!-- Excerpt Field -->
                <div class="bg-white border border-gray-200 p-6 shadow-sm">
                    <label for="excerpt" class="block text-xs font-bold text-gray-800 uppercase tracking-wider mb-2">
                        Excerpt (Summary)
                    </label>
                    <textarea name="excerpt" 
                              id="excerpt" 
                              rows="3" 
                              class="w-full bg-[#f8f9fa] border border-gray-300 px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0">{{ old('excerpt', $article->excerpt) }}</textarea>
                </div>

                <!-- Full Content Editor -->
                <div class="bg-white border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-[#fcfcfd]">
                        <label class="block text-xs font-bold text-gray-800 uppercase tracking-wider">
                            Full Content <span class="text-[#8b1528]">*</span>
                        </label>
                        
                        <!-- Rich text toolbar helper -->
                        <div class="flex items-center space-x-2 text-gray-500">
                            <button type="button" @click="insertTag('<b>', '</b>')" class="p-1.5 hover:text-navy hover:bg-gray-100 font-bold text-sm w-7 h-7 flex items-center justify-center border border-gray-200" title="Bold">B</button>
                            <button type="button" @click="insertTag('<i>', '</i>')" class="p-1.5 hover:text-navy hover:bg-gray-100 italic font-serif text-sm w-7 h-7 flex items-center justify-center border border-gray-200" title="Italic">I</button>
                            <button type="button" @click="insertTag('<u>', '</u>')" class="p-1.5 hover:text-navy hover:bg-gray-100 underline text-sm w-7 h-7 flex items-center justify-center border border-gray-200" title="Underline">U</button>
                            <button type="button" @click="insertTag('<blockquote>', '</blockquote>')" class="p-1.5 hover:text-navy hover:bg-gray-100 text-xs w-7 h-7 flex items-center justify-center border border-gray-200" title="Quote">&ldquo;&rdquo;</button>
                            <button type="button" @click="insertTag('<h3>', '</h3>')" class="p-1.5 hover:text-navy hover:bg-gray-100 text-xs font-bold w-7 h-7 flex items-center justify-center border border-gray-200" title="Heading">H3</button>
                        </div>
                    </div>

                    <div class="p-6">
                        <textarea name="content" 
                                  id="content" 
                                  rows="18" 
                                  class="w-full bg-[#f8f9fa] border border-gray-300 p-4 text-base font-serif-content leading-relaxed text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0" 
                                  required>{{ old('content', $article->content) }}</textarea>
                    </div>
                </div>

            </div>

            <!-- Right Column: Sidebar Action Controls -->
            <div class="lg:col-span-4 space-y-6">
                
                <!-- Publish Box -->
                <div class="bg-white border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider pb-3 mb-5 border-b border-gray-100">
                        Publish
                    </h3>

                    <div class="space-y-3">
                        <!-- Publish Now Button -->
                        <button type="button" 
                                @click="submitWithStatus('published')" 
                                class="w-full py-3 bg-[#6b0f1f] hover:bg-[#520a17] text-white text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                            </svg>
                            Publish Now
                        </button>

                        <!-- Save as Draft Button -->
                        <button type="button" 
                                @click="submitWithStatus('draft')" 
                                class="w-full py-3 bg-[#8b1528] hover:bg-[#721120] text-white text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-2 shadow-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            Save as Draft
                        </button>

                        <!-- Cancel Link -->
                        <div class="text-center pt-2">
                            <a href="{{ route('admin.articles.index') }}" class="text-xs text-gray-500 hover:text-[#00081e] transition-colors font-medium">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Categories Box -->
                <div class="bg-white border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider pb-3 mb-4 border-b border-gray-100">
                        Categories <span class="text-[#8b1528]">*</span>
                    </h3>

                    <div class="flex flex-wrap gap-2">
                        @foreach($categories as $category)
                        <label class="cursor-pointer relative">
                            <input type="radio" 
                                   name="category_id" 
                                   value="{{ $category->id }}" 
                                   data-slug="{{ $category->slug }}"
                                   {{ (old('category_id', $article->category_id) == $category->id) ? 'checked' : '' }} 
                                   class="peer sr-only" 
                                   @change="onCategoryChange($event)"
                                   required>
                            <span class="inline-block px-4 py-2 text-xs font-medium border border-gray-200 text-gray-600 transition-colors peer-checked:bg-[#8b1528] peer-checked:text-white peer-checked:border-[#8b1528] hover:bg-gray-50 peer-checked:hover:bg-[#721120]">
                                {{ $category->name }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Event Details Panel (muncul saat kategori Events dipilih) -->
                <div x-show="isEventCategory" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="bg-white border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider pb-3 mb-4 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Event Details
                    </h3>
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Tipe Event</label>
                        <div class="grid grid-cols-2 gap-2">
                            @php
                                $eventTypes = [
                                    'Campus Events' => '🏫',
                                    'Seminar'       => '🎤',
                                    'Sports'        => '🏆',
                                    'Arts & Culture'=> '🎨',
                                    'Academic'      => '📚',
                                    'Community'     => '🤝',
                                ];
                                $currentEventType = old('event_type', $article->event_type);
                            @endphp
                            @foreach($eventTypes as $typeName => $emoji)
                            <label class="cursor-pointer">
                                <input type="radio" name="event_type" value="{{ $typeName }}" {{ $currentEventType === $typeName ? 'checked' : '' }} class="peer sr-only">
                                <span class="flex items-center gap-2 px-3 py-2 text-xs font-medium border border-gray-200 text-gray-700 bg-white transition-all peer-checked:bg-[#8b1528] peer-checked:text-white peer-checked:border-[#8b1528] hover:bg-gray-50 rounded">
                                    <span>{{ $emoji }}</span>
                                    <span>{{ $typeName }}</span>
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-5">
                        <label for="event_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tanggal Event</label>
                        <input type="datetime-local" name="event_date" id="event_date"
                               value="{{ old('event_date', $article->event_date ? $article->event_date->format('Y-m-d\TH:i') : '') }}"
                               class="w-full bg-[#f8f9fa] border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0 rounded">
                    </div>
                    <div x-data="{ hasRegistration: {{ (old('registration_link', $article->registration_link)) ? 'true' : 'false' }} }" class="mb-1">
                        <label class="inline-flex items-center gap-3 cursor-pointer mb-4 select-none">
                            <div class="relative">
                                <input type="checkbox" class="sr-only" x-model="hasRegistration">
                                <div class="w-11 h-6 rounded-full transition-colors duration-200" :class="hasRegistration ? 'bg-[#8b1528]' : 'bg-gray-300'"></div>
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-200 pointer-events-none" :class="hasRegistration ? 'translate-x-5' : 'translate-x-0'"></div>
                            </div>
                            <span class="text-xs font-bold text-gray-700 uppercase tracking-wider">Event Ini Membuka Pendaftaran</span>
                        </label>
                        <div x-show="hasRegistration" x-transition class="space-y-4 pl-2 border-l-2 border-gray-200">
                            <div>
                                <label for="registration_link" class="block text-xs font-semibold text-gray-700 mb-1">URL / Link Pendaftaran</label>
                                <input type="url" name="registration_link" id="registration_link"
                                       value="{{ old('registration_link', $article->registration_link) }}"
                                       placeholder="https://forms.gle/..."
                                       class="w-full bg-[#f8f9fa] border border-gray-300 px-4 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0 rounded">
                                <p class="mt-1 text-[10px] text-gray-500">Pembaca akan diarahkan ke URL ini saat menekan tombol "Daftar Sekarang".</p>
                            </div>
                            <div>
                                <label for="registration_deadline" class="block text-xs font-semibold text-gray-700 mb-1">Batas Akhir Pendaftaran <span class="font-normal">(Opsional)</span></label>
                                <input type="datetime-local" name="registration_deadline" id="registration_deadline"
                                       value="{{ old('registration_deadline', $article->registration_deadline ? $article->registration_deadline->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full bg-[#f8f9fa] border border-gray-300 px-4 py-2.5 text-sm text-gray-900 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0 rounded">
                                <p class="mt-1 text-[10px] text-gray-500">Tombol "Daftar Sekarang" akan otomatis disembunyikan setelah tanggal ini.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Research Details Panel (muncul saat kategori Research & Innovation dipilih) -->
                <div x-show="isResearchCategory" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak class="bg-white border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider pb-3 mb-4 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        Research Details
                    </h3>
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Research Field</label>
                        <div class="grid grid-cols-2 gap-2">
                            @php
                                $researchFields = [
                                    'Biomedical Sciences'           => '🧬',
                                    'Engineering & Applied Science' => '⚙️',
                                    'Social Sciences & Humanities'  => '🏛️',
                                    'Computer Science & AI'         => '🤖',
                                ];
                                $currentResearchField = old('research_field', $article->research_field);
                            @endphp
                            @foreach($researchFields as $fieldName => $emoji)
                            <label class="cursor-pointer">
                                <input type="radio" name="research_field" value="{{ $fieldName }}" {{ $currentResearchField === $fieldName ? 'checked' : '' }} class="peer sr-only">
                                <span class="flex items-center gap-2 px-3 py-2 text-xs font-medium border border-gray-200 text-gray-700 bg-white transition-all peer-checked:bg-[#8b1528] peer-checked:text-white peer-checked:border-[#8b1528] hover:bg-gray-50 rounded">
                                    <span>{{ $emoji }}</span>
                                    <span>{{ $fieldName }}</span>
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Research Center / Lab</label>
                        <div class="grid grid-cols-1 gap-2">
                            @php
                                $researchCenters = [
                                    'Institute for Sustainable Energy' => '⚡',
                                    'Center for Digital Ethics'        => '💡',
                                    'Genomics Research Institute'      => '🔬',
                                ];
                                $currentResearchCenter = old('research_center', $article->research_center);
                            @endphp
                            @foreach($researchCenters as $centerName => $emoji)
                            <label class="cursor-pointer">
                                <input type="radio" name="research_center" value="{{ $centerName }}" {{ $currentResearchCenter === $centerName ? 'checked' : '' }} class="peer sr-only">
                                <span class="flex items-center gap-2 px-3 py-2 text-xs font-medium border border-gray-200 text-gray-700 bg-white transition-all peer-checked:bg-[#8b1528] peer-checked:text-white peer-checked:border-[#8b1528] hover:bg-gray-50 rounded">
                                    <span>{{ $emoji }}</span>
                                    <span>{{ $centerName }}</span>
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Tags Box -->
                <div class="bg-white border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider pb-3 mb-4 border-b border-gray-100">
                        Tags
                    </h3>

                    <!-- Add Tag Input Row with Suggestions -->
                    <div class="relative mb-4" @click.outside="showSuggestions = false">
                        <div class="flex items-center gap-2">
                            <input type="text" 
                                   x-model="newTagInput" 
                                   @focus="showSuggestions = true"
                                   @input="showSuggestions = true"
                                   @keydown.escape="showSuggestions = false"
                                   @keydown.enter.prevent="addTag()" 
                                   placeholder="Add a tag..." 
                                   class="flex-1 bg-[#f8f9fa] border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0">
                            <button type="button" 
                                    @click="addTag()" 
                                    class="px-4 py-2 bg-[#6b0f1f] hover:bg-[#520a17] text-white text-xs font-bold uppercase tracking-wider transition-colors">
                                Add
                            </button>
                        </div>

                        <!-- Suggestions Dropdown List -->
                        <div x-show="showSuggestions && filteredSuggestions.length > 0" 
                             x-transition
                             x-cloak
                             class="absolute z-20 w-full bg-white border border-gray-200 shadow-lg max-h-48 overflow-y-auto mt-1 left-0 rounded-sm">
                            <div class="px-3 py-1.5 bg-gray-50 border-b border-gray-100 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                Tag Suggestions
                            </div>
                            <template x-for="suggestion in filteredSuggestions" :key="suggestion">
                                <button type="button" 
                                        @click="selectTag(suggestion)" 
                                        class="w-full text-left px-3 py-2 text-xs text-gray-700 hover:bg-[#8b1528]/10 hover:text-[#8b1528] flex items-center justify-between border-b border-gray-50 last:border-0 transition-colors">
                                    <span class="font-medium" x-text="'#' + suggestion"></span>
                                    <span class="text-[10px] text-gray-400">existing tag</span>
                                </button>
                            </template>
                        </div>
                    </div>

                    <!-- Selected Tag Badges Container -->
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(tag, index) in tags" :key="index">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-[#8b1528] border border-[#8b1528]/30 bg-[#8b1528]/5 gap-1.5">
                                <span x-text="'#' + tag"></span>
                                <button type="button" @click="removeTag(index)" class="hover:text-red-900 font-bold leading-none">&times;</button>
                                <input type="hidden" name="tags[]" :value="tag">
                            </span>
                        </template>
                    </div>
                </div>

                <!-- Featured Image Box -->
                <div class="bg-white border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider pb-3 mb-4 border-b border-gray-100">
                        Featured Image
                    </h3>

                    @if($article->featured_image_path)
                    <div class="mb-4">
                        <img src="{{ asset($article->featured_image_path) }}" alt="Featured Image" class="w-full h-32 object-cover border border-gray-200">
                    </div>
                    @endif

                    <div class="relative border-2 border-dashed border-gray-300 hover:border-gray-400 transition-colors p-6 text-center cursor-pointer bg-[#fafafa]">
                        <input type="file" 
                               name="featured_image" 
                               id="featured_image" 
                               accept="image/*" 
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        
                        <div class="space-y-2">
                            <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-xs font-medium text-gray-700">Click to upload or drag and drop</p>
                            <p class="text-[10px] text-gray-400">SVG, PNG, JPG or GIF (max. 800&times;400px)</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<script>
function articleEditFormHandler() {
    return {
        newTagInput: '',
        showSuggestions: false,
        availableTags: @json($allTags ?? []),
        tags: @json(old('tags', $article->tags->pluck('name'))),
        isEventCategory: false,
        isResearchCategory: false,
        init() {
            const checked = document.querySelector('input[name="category_id"]:checked');
            if (checked) {
                this.isEventCategory    = (checked.dataset.slug === 'events');
                this.isResearchCategory = (checked.dataset.slug === 'research-innovation');
            }
        },
        onCategoryChange(e) {
            this.isEventCategory    = (e.target.dataset.slug === 'events');
            this.isResearchCategory = (e.target.dataset.slug === 'research-innovation');
        },
        get filteredSuggestions() {
            if (!this.newTagInput || !this.newTagInput.trim()) {
                return this.availableTags.filter(t => !this.tags.includes(t)).slice(0, 6);
            }
            const q = this.newTagInput.trim().toLowerCase().replace(/^#/, '');
            return this.availableTags.filter(t => 
                t.toLowerCase().includes(q) && !this.tags.includes(t)
            ).slice(0, 8);
        },
        selectTag(tagName) {
            if (tagName && !this.tags.includes(tagName)) {
                this.tags.push(tagName);
            }
            this.newTagInput = '';
            this.showSuggestions = false;
        },
        addTag() {
            const trimmed = this.newTagInput.trim().replace(/^#/, '');
            if (trimmed && !this.tags.includes(trimmed)) {
                this.tags.push(trimmed);
            }
            this.newTagInput = '';
            this.showSuggestions = false;
        },
        removeTag(index) {
            this.tags.splice(index, 1);
        },
        insertTag(openTag, closeTag) {
            const textarea = document.getElementById('content');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selected = textarea.value.substring(start, end);
            const replacement = openTag + selected + closeTag;
            textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
            textarea.focus();
            textarea.setSelectionRange(start + openTag.length, end + openTag.length);
        },
        submitWithStatus(status) {
            document.getElementById('formStatus').value = status;
            document.getElementById('articleEditForm').submit();
        }
    }
}
</script>
@endsection
