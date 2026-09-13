@extends('layouts.cms')

@section('title', 'Admin Settings - University News')
@section('header_tagline', 'University News CMS')
@section('page_tour_id', 'admin.settings')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold font-heading text-[#00081e] tracking-tight">Admin Settings</h1>
        <p class="text-gray-500 font-sans text-sm mt-1">Manage your admin account, contact info, and view activity.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-600 text-green-700 text-sm font-sans flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if(session('password_success'))
    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-600 text-green-700 text-sm font-sans flex items-center gap-2">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('password_success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-600 text-red-700 text-sm">
        <p class="font-bold">Please correct the following errors:</p>
        <ul class="list-disc pl-5 mt-1 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start"
         x-data="{
             previewUrl: '{{ Auth::guard('admin')->user()->avatar_url }}',
             hasAvatar: {{ Auth::guard('admin')->user()->hasAvatar() ? 'true' : 'false' }},
             removeAvatar: false,
             newPhotoSelected: false,
             errorMessage: '',
             validateAndPreview(e) {
                 const file = e.target.files[0];
                 if (!file) return;

                 this.errorMessage = '';

                 // Format validation (Strictly PNG or JPG)
                 const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                 const fileExt = file.name.split('.').pop().toLowerCase();
                 const validExts = ['jpg', 'jpeg', 'png'];

                 if (!validTypes.includes(file.type) && !validExts.includes(fileExt)) {
                     const msg = 'Invalid format! Only PNG or JPG photos are allowed.';
                     this.errorMessage = msg;
                     if (window.showWarningAlert) {
                         window.showWarningAlert('Format Warning', msg);
                     } else {
                         alert(msg);
                     }
                     e.target.value = '';
                     return;
                 }

                 // Maximum size: 2MB (2 * 1024 * 1024 = 2,097,152 bytes)
                 if (file.size > 2 * 1024 * 1024) {
                     const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                     const msg = 'Photo exceeds the 2MB size limit (' + sizeMB + ' MB). Please choose a smaller file.';
                     this.errorMessage = msg;
                     if (window.showWarningAlert) {
                         window.showWarningAlert('File Size Warning', msg);
                     } else {
                         alert(msg);
                     }
                     e.target.value = '';
                     return;
                 }

                 this.removeAvatar = false;
                 this.newPhotoSelected = true;
                 this.previewUrl = URL.createObjectURL(file);
                 if (window.showSuccessAlert) {
                     window.showSuccessAlert('Photo Selected', 'Click Save Changes to apply your new profile photo.');
                 }
             },
             removePhoto() {
                 this.previewUrl = '';
                 this.removeAvatar = true;
                 this.newPhotoSelected = false;
                 this.errorMessage = '';
                 const input = document.getElementById('admin_avatar_input');
                 if (input) input.value = '';
                 if (window.showInfoAlert) {
                     window.showInfoAlert('Photo Marked for Removal', 'Click Save Changes to permanently remove your photo.');
                 }
             }
         }">
        
        <!-- ═══════════════ Left Column: Profile Card & Account Status ═══════════════ -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Main Profile Summary Card -->
            <div class="bg-white border border-gray-200 p-6 text-center shadow-sm" data-tour="settings-profile-card">
                <!-- Avatar -->
                <div class="relative group w-32 h-40 mx-auto bg-gray-100 border border-gray-200 overflow-hidden shadow-inner flex items-center justify-center mb-3">
                    <template x-if="previewUrl">
                        <img :src="previewUrl" alt="{{ Auth::guard('admin')->user()->name }}" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!previewUrl">
                        <div class="w-full h-full bg-slate-200 flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                        </div>
                    </template>

                    <!-- Quick Change Overlay -->
                    <button type="button" 
                            @click="document.getElementById('admin_avatar_input').click()"
                            class="absolute inset-0 bg-black/50 text-white opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center gap-1 transition-opacity cursor-pointer text-xs font-semibold">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>Change</span>
                    </button>
                </div>

                <!-- Avatar Actions -->
                <div class="mb-4 space-y-2">
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" 
                                @click="document.getElementById('admin_avatar_input').click()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#f4f6f8] hover:bg-[#eef0f2] border border-gray-300 text-gray-700 text-xs font-semibold uppercase tracking-wider transition-colors">
                            <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span>Change Photo</span>
                        </button>

                        <button type="button" 
                                x-show="previewUrl"
                                @click="removePhoto()"
                                class="inline-flex items-center px-2.5 py-1.5 text-xs font-semibold text-red-600 hover:text-red-800 hover:bg-red-50 transition-colors">
                            Remove
                        </button>
                    </div>

                    <!-- Size / Format Help text -->
                    <p class="text-[11px] text-gray-500 font-sans">
                        PNG or JPG &bull; Max <strong>2MB</strong> &bull; Auto compressed
                    </p>

                    <template x-if="newPhotoSelected">
                        <div class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            <span>Photo selected &mdash; click Save Changes</span>
                        </div>
                    </template>
                    <template x-if="removeAvatar">
                        <div class="inline-flex items-center gap-1 text-[11px] font-semibold text-red-600 bg-red-50 border border-red-200 px-2 py-0.5 rounded">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span>Removed &mdash; click Save Changes</span>
                        </div>
                    </template>
                    <template x-if="errorMessage">
                        <p class="text-[11px] font-semibold text-red-600 font-sans" x-text="errorMessage"></p>
                    </template>
                </div>

                <!-- User Name & Role Badge -->
                <h2 class="text-xl font-bold font-heading text-[#00081e]">{{ Auth::guard('admin')->user()->name }}</h2>
                <div class="mt-1.5 inline-block">
                    <span class="px-2.5 py-0.5 bg-[#8b1528]/10 text-[#8b1528] text-xs font-semibold uppercase tracking-wider border border-[#8b1528]/20">
                        Administrator
                    </span>
                </div>

                <!-- Meta Details -->
                <div class="mt-6 pt-6 border-t border-gray-100 text-left space-y-3.5 text-xs text-gray-600 font-sans">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Joined {{ Auth::guard('admin')->user()->created_at->format('F Y') }}</span>
                    </div>

                    <!-- Social Media Links -->
                    @php $socials = Auth::guard('admin')->user()->social_links ?? []; @endphp
                    @if(!empty($socials))
                    <div class="pt-3 border-t border-gray-100">
                        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-2.5">Social</p>
                        <div class="flex items-center gap-2.5">
                            @if(!empty($socials['instagram']))
                            <a href="https://instagram.com/{{ ltrim($socials['instagram'], '@') }}" target="_blank" rel="noopener" title="Instagram" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-pink-50 text-gray-500 hover:text-pink-600 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            @endif
                            @if(!empty($socials['twitter']))
                            <a href="https://x.com/{{ ltrim($socials['twitter'], '@') }}" target="_blank" rel="noopener" title="X / Twitter" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-sky-50 text-gray-500 hover:text-sky-600 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            @endif
                            @if(!empty($socials['threads']))
                            <a href="https://threads.net/@{{ ltrim($socials['threads'], '@') }}" target="_blank" rel="noopener" title="Threads" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-800 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.186 24h-.007c-3.581-.024-6.334-1.205-8.184-3.509C2.35 18.44 1.5 15.586 1.472 12.01v-.017c.03-3.579.879-6.43 2.525-8.482C5.845 1.205 8.6.024 12.18 0h.014c2.746.02 5.043.725 6.826 2.098 1.677 1.29 2.858 3.13 3.509 5.467l-2.04.569c-1.104-3.96-3.898-5.984-8.304-6.015-2.91.022-5.11.936-6.54 2.717C4.307 6.504 3.616 8.914 3.59 12c.025 3.086.718 5.496 2.057 7.164 1.432 1.783 3.631 2.698 6.54 2.717 2.623-.02 4.358-.631 5.8-2.045 1.647-1.613 1.618-3.593 1.09-4.798-.34-.775-.951-1.4-1.762-1.851a8.57 8.57 0 01-.343.608c-.49.784-1.199 1.4-2.107 1.832-.897.427-1.942.65-3.108.661h-.007c-1.76-.018-3.19-.592-4.254-1.706-1.044-1.093-1.58-2.545-1.594-4.319.014-1.29.332-2.426.946-3.376.632-.977 1.534-1.733 2.681-2.249 1.123-.504 2.416-.767 3.842-.782h.026c1.383.013 2.592.225 3.595.631.942.381 1.71.928 2.284 1.627l-1.498 1.37c-.858-.97-2.27-1.462-4.195-1.509h-.032c-2.005.021-3.513.673-4.488 1.94-.574.747-.874 1.706-.893 2.85.013 1.165.34 2.087.973 2.74.651.672 1.593 1.016 2.801 1.026h.005c1.424-.015 2.476-.42 3.127-1.206.408-.493.658-1.14.747-1.93a5.937 5.937 0 01-2.088-.353c-.67-.268-1.186-.638-1.534-1.1l1.378-1.08c.195.259.522.49.972.688.574.253 1.29.39 2.13.408l.145.002c.627 0 1.062-.036 1.378-.094.726-2.782-.453-4.392-3.506-4.786-.504-.065-1.04-.098-1.592-.098h-.088c-1.164.008-2.218.208-3.133.594-.9.38-1.595.918-2.065 1.599-.483.699-.734 1.544-.747 2.51.013 1.366.412 2.452 1.187 3.228.798.8 1.908 1.215 3.3 1.234h.007c.868-.008 1.635-.175 2.282-.498.633-.315 1.116-.748 1.437-1.286.096-.162.18-.329.252-.5.424.151.81.268 1.16.349.555.837.854 1.83.854 2.942 0 .272-.021.548-.063.824-1.594 1.856-3.748 2.808-6.396 2.83z"/></svg>
                            </a>
                            @endif
                            @if(!empty($socials['linkedin']))
                            <a href="https://linkedin.com/in/{{ $socials['linkedin'] }}" target="_blank" rel="noopener" title="LinkedIn" class="w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-blue-50 text-gray-500 hover:text-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Account Status Card -->
            <div class="bg-white border border-gray-200 p-6 shadow-sm">
                <h3 class="text-sm font-bold font-heading text-[#00081e] mb-4 pb-2 border-b border-gray-100">Account Status</h3>
                
                <div class="space-y-3.5 text-xs font-sans">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Role Level</span>
                        <span class="font-semibold text-gray-800">Level 4 (Admin)</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Total Articles</span>
                        <span class="font-semibold text-gray-800">
                            {{ number_format(\App\Models\Article::where('status', 'published')->count()) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Pending Review</span>
                        <span class="font-semibold {{ \App\Models\Article::where('status', 'pending_review')->count() > 0 ? 'text-amber-600' : 'text-gray-800' }}">
                            {{ number_format(\App\Models\Article::where('status', 'pending_review')->count()) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Last Login</span>
                        <span class="font-semibold text-gray-800">Today, {{ now()->format('h:i A') }}</span>
                    </div>
                </div>

                <!-- Tutorial Replay Button -->
                <div class="mt-5 pt-4 border-t border-gray-100">
                    <button
                        data-tour="settings-tutorial-replay"
                        onclick="typeof window.replayOnboarding === 'function' ? window.replayOnboarding() : null"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-[#f4f6f8] hover:bg-[#eef0f2] border border-gray-200 text-gray-600 hover:text-[#8b1528] text-[11px] font-semibold uppercase tracking-wider transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Lihat Tutorial Dashboard
                    </button>
                </div>
            </div>
        </div>

        <!-- ═══════════════ Right Column: Forms ═══════════════ -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- ── Edit Profile Card ──────────────────────────────────────── -->
            <div class="bg-white border border-gray-200 shadow-sm p-6 lg:p-8" data-tour="settings-edit-form">
                <h2 class="text-xl font-bold font-heading text-[#00081e] mb-6">Edit Profile</h2>

                <form id="admin-profile-form" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="email" value="{{ Auth::guard('admin')->user()->email }}">
                    <input type="file" 
                           id="admin_avatar_input" 
                           name="avatar" 
                           accept="image/png,image/jpeg" 
                           class="hidden" 
                           @change="validateAndPreview($event)">
                    <input type="hidden" 
                           name="remove_avatar" 
                           :value="removeAvatar ? '1' : '0'">

                    <div class="space-y-6">
                        <!-- Full Name & Preferred Name -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Full Name</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', Auth::guard('admin')->user()->name) }}" 
                                       class="w-full bg-[#f8f9fa] border border-gray-300 px-3.5 py-2.5 text-sm text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0" 
                                       required>
                            </div>

                            <div>
                                <label for="preferred_name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Preferred Name</label>
                                <input type="text" 
                                       name="preferred_name" 
                                       id="preferred_name" 
                                       value="{{ old('preferred_name', Auth::guard('admin')->user()->preferred_name ?? '') }}" 
                                       class="w-full bg-[#f8f9fa] border border-gray-300 px-3.5 py-2.5 text-sm text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0">
                            </div>
                        </div>

                        <!-- Social Media Links Section -->
                        <div class="pt-4 border-t border-gray-100">
                            <h3 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                Social Media Links
                            </h3>
                            @php $socials = Auth::guard('admin')->user()->social_links ?? []; @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="social_instagram" class="block text-xs font-medium text-gray-500 mb-1.5">
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-pink-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                            Instagram
                                        </span>
                                    </label>
                                    <input type="text" 
                                           name="social_instagram" 
                                           id="social_instagram" 
                                           value="{{ old('social_instagram', $socials['instagram'] ?? '') }}" 
                                           placeholder="username" 
                                           class="w-full bg-[#f8f9fa] border border-gray-300 px-3.5 py-2.5 text-sm text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0">
                                </div>
                                <div>
                                    <label for="social_twitter" class="block text-xs font-medium text-gray-500 mb-1.5">
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-gray-800" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                            X / Twitter
                                        </span>
                                    </label>
                                    <input type="text" 
                                           name="social_twitter" 
                                           id="social_twitter" 
                                           value="{{ old('social_twitter', $socials['twitter'] ?? '') }}" 
                                           placeholder="username" 
                                           class="w-full bg-[#f8f9fa] border border-gray-300 px-3.5 py-2.5 text-sm text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0">
                                </div>
                                <div>
                                    <label for="social_threads" class="block text-xs font-medium text-gray-500 mb-1.5">
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-gray-800" fill="currentColor" viewBox="0 0 24 24"><path d="M12.186 24h-.007c-3.581-.024-6.334-1.205-8.184-3.509C2.35 18.44 1.5 15.586 1.472 12.01v-.017c.03-3.579.879-6.43 2.525-8.482C5.845 1.205 8.6.024 12.18 0h.014c2.746.02 5.043.725 6.826 2.098 1.677 1.29 2.858 3.13 3.509 5.467l-2.04.569c-1.104-3.96-3.898-5.984-8.304-6.015-2.91.022-5.11.936-6.54 2.717C4.307 6.504 3.616 8.914 3.59 12c.025 3.086.718 5.496 2.057 7.164 1.432 1.783 3.631 2.698 6.54 2.717 2.623-.02 4.358-.631 5.8-2.045 1.647-1.613 1.618-3.593 1.09-4.798-.34-.775-.951-1.4-1.762-1.851a8.57 8.57 0 01-.343.608c-.49.784-1.199 1.4-2.107 1.832-.897.427-1.942.65-3.108.661h-.007c-1.76-.018-3.19-.592-4.254-1.706-1.044-1.093-1.58-2.545-1.594-4.319.014-1.29.332-2.426.946-3.376.632-.977 1.534-1.733 2.681-2.249 1.123-.504 2.416-.767 3.842-.782h.026c1.383.013 2.592.225 3.595.631.942.381 1.71.928 2.284 1.627l-1.498 1.37c-.858-.97-2.27-1.462-4.195-1.509h-.032c-2.005.021-3.513.673-4.488 1.94-.574.747-.874 1.706-.893 2.85.013 1.165.34 2.087.973 2.74.651.672 1.593 1.016 2.801 1.026h.005c1.424-.015 2.476-.42 3.127-1.206.408-.493.658-1.14.747-1.93a5.937 5.937 0 01-2.088-.353c-.67-.268-1.186-.638-1.534-1.1l1.378-1.08c.195.259.522.49.972.688.574.253 1.29.39 2.13.408l.145.002c.627 0 1.062-.036 1.378-.094.726-2.782-.453-4.392-3.506-4.786-.504-.065-1.04-.098-1.592-.098h-.088c-1.164.008-2.218.208-3.133.594-.9.38-1.595.918-2.065 1.599-.483.699-.734 1.544-.747 2.51.013 1.366.412 2.452 1.187 3.228.798.8 1.908 1.215 3.3 1.234h.007c.868-.008 1.635-.175 2.282-.498.633-.315 1.116-.748 1.437-1.286.096-.162.18-.329.252-.5.424.151.81.268 1.16.349.555.837.854 1.83.854 2.942 0 .272-.021.548-.063.824-1.594 1.856-3.748 2.808-6.396 2.83z"/></svg>
                                            Threads
                                        </span>
                                    </label>
                                    <input type="text" 
                                           name="social_threads" 
                                           id="social_threads" 
                                           value="{{ old('social_threads', $socials['threads'] ?? '') }}" 
                                           placeholder="username" 
                                           class="w-full bg-[#f8f9fa] border border-gray-300 px-3.5 py-2.5 text-sm text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0">
                                </div>
                                <div>
                                    <label for="social_linkedin" class="block text-xs font-medium text-gray-500 mb-1.5">
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-blue-700" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                            LinkedIn
                                        </span>
                                    </label>
                                    <input type="text" 
                                           name="social_linkedin" 
                                           id="social_linkedin" 
                                           value="{{ old('social_linkedin', $socials['linkedin'] ?? '') }}" 
                                           placeholder="username" 
                                           class="w-full bg-[#f8f9fa] border border-gray-300 px-3.5 py-2.5 text-sm text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-6 border-t border-gray-100 flex items-center justify-end space-x-4">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="px-5 py-2.5 border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-semibold uppercase tracking-wider transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-[#8b1528] hover:bg-[#721120] text-white text-xs font-semibold uppercase tracking-wider flex items-center gap-2 shadow-sm transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ── Security & Password Card ───────────────────────────────── -->
            <div class="bg-white border border-gray-200 shadow-sm p-6 lg:p-8" data-tour="settings-password">
                <h2 class="text-xl font-bold font-heading text-[#00081e] mb-2">Security & Password</h2>
                <p class="text-gray-500 text-xs font-sans mb-6">Change your password directly or request a reset link via email.</p>

                <!-- Direct Password Change Form -->
                <form method="POST" action="{{ route('admin.settings.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        <div>
                            <label for="current_password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Current Password</label>
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   class="w-full bg-[#f8f9fa] border border-gray-300 px-3.5 py-2.5 text-sm text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0" 
                                   required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">New Password</label>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="w-full bg-[#f8f9fa] border border-gray-300 px-3.5 py-2.5 text-sm text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0" 
                                       required>
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Confirm New Password</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="w-full bg-[#f8f9fa] border border-gray-300 px-3.5 py-2.5 text-sm text-gray-800 focus:bg-white focus:outline-none focus:border-[#8b1528] focus:ring-0" 
                                       required>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <!-- Alternative: Reset via Email -->
                            <form method="POST" action="{{ route('password.email') }}" class="inline">
                                @csrf
                                <input type="hidden" name="email" value="{{ Auth::guard('admin')->user()->email }}">
                                <button type="submit" class="text-xs text-gray-500 hover:text-[#8b1528] underline underline-offset-2 transition-colors">
                                    Or send reset link via email
                                </button>
                            </form>

                            <button type="submit" 
                                    class="px-6 py-2.5 bg-[#8b1528] hover:bg-[#721120] text-white text-xs font-semibold uppercase tracking-wider flex items-center gap-2 shadow-sm transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
        </div>
    </div>
    </div>

    <!-- ── Activity Log Card ──────────────────────────────────────── -->
    <div class="bg-white border border-gray-200 shadow-sm p-6 lg:p-8 mt-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold font-heading text-[#00081e]">Activity Log</h2>
                        <p class="text-gray-500 text-xs font-sans mt-1">Recent article reviews and boost activity.</p>
                    </div>
                    <a href="{{ route('admin.articles.index') }}" class="text-xs text-[#8b1528] hover:underline font-medium">View All Articles →</a>
                </div>

                @if($activityLog->isEmpty())
                    <div class="text-center py-10 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm font-medium">No recent activity</p>
                        <p class="text-xs mt-1">Activity will appear here as articles are reviewed.</p>
                    </div>
                @else
                    <div class="space-y-0 divide-y divide-gray-100">
                        @foreach($activityLog as $log)
                        <div class="flex items-start gap-3.5 py-3.5 {{ $loop->first ? 'pt-0' : '' }}">
                            <!-- Icon -->
                            <div class="flex-shrink-0 mt-0.5">
                                @if($log['icon'] === 'clock')
                                    <div class="w-8 h-8 bg-amber-50 text-amber-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @elseif($log['icon'] === 'check')
                                    <div class="w-8 h-8 bg-green-50 text-green-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @elseif($log['icon'] === 'x')
                                    <div class="w-8 h-8 bg-red-50 text-red-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                @elseif($log['icon'] === 'credit-card')
                                    <div class="w-8 h-8 bg-blue-50 text-blue-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </div>
                                @elseif($log['icon'] === 'lightning')
                                    <div class="w-8 h-8 bg-purple-50 text-purple-600 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
                                    <span class="text-xs font-semibold text-gray-800">{{ $log['type'] }}</span>
                                    <span class="px-1.5 py-0.5 text-[10px] font-medium uppercase tracking-wider
                                        {{ $log['color'] === 'amber' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $log['color'] === 'green' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $log['color'] === 'red' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $log['color'] === 'blue' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $log['color'] === 'purple' ? 'bg-purple-100 text-purple-700' : '' }}
                                    ">
                                        {{ $log['color'] === 'amber' ? 'Needs Action' : ($log['color'] === 'green' ? 'Completed' : ($log['color'] === 'red' ? 'Declined' : ($log['color'] === 'blue' ? 'Payment' : 'Expired'))) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 truncate">{{ $log['detail'] }}</p>
                            </div>

                            <!-- Timestamp -->
                            <div class="flex-shrink-0 text-right">
                                <span class="text-[11px] text-gray-400 font-mono">{{ $log['time']->diffForHumans() }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
</div>
@endsection
