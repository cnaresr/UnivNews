@extends('layouts.transactional')

@section('title', 'Pembayaran Diproses — ' . config('app.name'))

@section('content')
<div class="max-w-xl mx-auto px-4 py-16">

    {{-- Animated checkmark --}}
    <div class="flex justify-center mb-8">
        <div class="w-24 h-24 rounded-full bg-[#f0fdf4] border-4 border-[#16a34a] flex items-center justify-center">
            <svg class="w-12 h-12 text-[#16a34a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
    </div>

    {{-- Card --}}
    <div class="bg-white border border-gray-200 shadow-lg overflow-hidden">

        {{-- Header --}}
        <div class="bg-[#00081e] px-8 py-7 text-center border-b-4 border-[#16a34a]">
            <h1 class="text-white text-xl font-bold font-heading">
                Terima Kasih!
            </h1>
            <p class="text-[#7687B2] text-sm mt-2 leading-relaxed">
                Pembayaran kamu sedang kami verifikasi.
            </p>
        </div>

        {{-- Body --}}
        <div class="px-8 py-8">

            {{-- Article title --}}
            <div class="border-l-4 border-[#16a34a] pl-4 mb-6">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Artikel</p>
                <p class="font-bold text-[#00081e] text-base leading-snug font-heading">
                    {{ $article->title }}
                </p>
            </div>

            {{-- Status info --}}
            <div class="bg-green-50 border border-green-200 p-5 mb-6">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-green-800 mb-1">Pembayaran Sedang Diverifikasi</p>
                        <p class="text-xs text-green-700 leading-relaxed">
                            Sistem kami sedang memverifikasi pembayaran kamu secara otomatis. 
                            Proses ini biasanya berlangsung dalam beberapa menit.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Publish schedule --}}
            @if($article->published_at)
            <div class="bg-blue-50 border border-blue-200 p-5 mb-6">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-blue-500 mb-1">Jadwal Publish</p>
                        <p class="text-sm font-bold text-blue-900">
                            {{ $article->published_at->locale('id')->isoFormat('dddd, D MMMM YYYY · HH:mm') }} WIB
                        </p>
                        <p class="text-xs text-blue-600 mt-0.5">
                            Artikel akan otomatis tayang pada jadwal ini setelah pembayaran terkonfirmasi.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            {{-- What happens next --}}
            <div class="mb-6">
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Yang Akan Terjadi Selanjutnya</p>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-[#00081e] text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0">1</div>
                        <p class="text-xs text-gray-600 pt-0.5 leading-relaxed">Sistem memverifikasi konfirmasi pembayaran dari Mayar secara otomatis.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-[#00081e] text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0">2</div>
                        <p class="text-xs text-gray-600 pt-0.5 leading-relaxed">Artikel kamu akan otomatis terpublish sesuai jadwal yang telah ditentukan admin.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-[#8b1528] text-white text-[11px] font-bold flex items-center justify-center flex-shrink-0">3</div>
                        <p class="text-xs text-gray-600 pt-0.5 leading-relaxed"><strong>Kamu akan menerima email konfirmasi</strong> saat artikel berhasil terpublish.</p>
                    </div>
                </div>
            </div>

            {{-- Note --}}
            <div class="bg-amber-50 border border-amber-200 p-4 mb-6">
                <p class="text-xs text-amber-800 leading-relaxed">
                    ⚠️ <strong>Catatan:</strong> Halaman ini bukan konfirmasi bahwa pembayaran sudah berhasil. 
                    Status publikasi sepenuhnya bergantung pada verifikasi otomatis sistem. 
                    Jika kamu tidak menerima email dalam 1×24 jam, hubungi admin.
                </p>
            </div>

            {{-- Actions --}}
            <div class="space-y-3">
                <a href="{{ route('author.dashboard') }}" id="btn-back-dashboard"
                   class="w-full py-3.5 bg-[#00081e] text-white text-xs font-bold uppercase tracking-widest text-center block hover:bg-[#0f1f4a] transition-colors">
                    Back to Dashboard
                </a>
                <a href="{{ route('home') }}"
                   class="w-full py-3 border border-gray-300 text-gray-600 text-xs font-bold uppercase tracking-widest text-center block hover:bg-gray-50 transition-colors">
                    Back to Home
                </a>
            </div>
        </div>

    </div>

    {{-- Security note --}}
    <div class="mt-6 flex items-center justify-center gap-2 text-xs text-gray-400">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
        Pembayaran diproses secara aman melalui Mayar.id
    </div>

</div>
@endsection
