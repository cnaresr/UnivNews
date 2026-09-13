@extends('layouts.transactional')

@section('title', 'Konfirmasi Pembayaran — ' . config('app.name'))

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16">

    {{-- Status indicator --}}
    <div class="flex items-center justify-center mb-8">
        <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 px-5 py-2.5 rounded-none">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-pulse inline-block"></span>
            <span class="text-xs font-bold uppercase tracking-widest text-amber-800">Menunggu Pembayaran</span>
        </div>
    </div>

    {{-- Main card --}}
    <div class="bg-white border border-gray-200 shadow-lg overflow-hidden">

        {{-- Card header --}}
        <div class="bg-[#00081e] px-8 py-7 border-b-4 border-[#8b1528]">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-[#7687B2] mb-2">Biaya Publikasi Artikel</p>
                    <h1 class="text-white text-2xl font-bold font-heading leading-tight">
                        Selesaikan Pembayaran
                    </h1>
                    <p class="text-[#7687B2] text-sm mt-2">
                        Artikel kamu sudah disetujui admin — satu langkah lagi untuk terpublish!
                    </p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-[#7687B2] uppercase tracking-wider mb-1">Total</p>
                    <p class="text-3xl font-bold text-white">
                        Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    </p>
                    <p class="text-[11px] text-[#7687B2] mt-1">Satu kali bayar</p>
                </div>
            </div>
        </div>

        {{-- Article info --}}
        <div class="px-8 py-6 border-b border-gray-100">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Artikel yang Akan Dipublikasikan</p>
            <div class="flex items-start gap-4">
                <div class="w-1 h-12 bg-[#8b1528] flex-shrink-0 mt-0.5"></div>
                <div>
                    <h2 class="font-bold text-[#00081e] text-lg leading-snug font-heading">
                        {{ $article->title }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Kategori: <span class="font-medium text-gray-700">{{ $article->category->name ?? '-' }}</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Publish schedule --}}
        <div class="px-8 py-5 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-blue-500 mb-0.5">Jadwal Publish</p>
                    <p class="text-sm font-bold text-blue-900">
                        {{ $article->published_at 
                            ? $article->published_at->locale('id')->isoFormat('dddd, D MMMM YYYY · HH:mm') . ' WIB'
                            : 'Segera setelah pembayaran' }}
                    </p>
                    <p class="text-xs text-blue-600 mt-0.5">Artikel akan otomatis tayang pada tanggal ini.</p>
                </div>
            </div>
        </div>

        {{-- Payment info --}}
        <div class="px-8 py-5 border-b border-gray-100">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3">Metode Pembayaran Tersedia</p>
            <div class="grid grid-cols-3 gap-3">
                <div class="border border-gray-200 p-3 text-center">
                    <div class="text-xl mb-1">📱</div>
                    <p class="text-xs font-semibold text-gray-700">QRIS</p>
                </div>
                <div class="border border-gray-200 p-3 text-center">
                    <div class="text-xl mb-1">🏦</div>
                    <p class="text-xs font-semibold text-gray-700">Virtual Account</p>
                </div>
                <div class="border border-gray-200 p-3 text-center">
                    <div class="text-xl mb-1">💳</div>
                    <p class="text-xs font-semibold text-gray-700">E-Wallet</p>
                </div>
            </div>
            <p class="text-[11px] text-gray-400 mt-3 text-center">
                Pilih metode pembayaran di halaman checkout berikutnya.
            </p>
        </div>

        {{-- CTA --}}
        <div class="px-8 py-7">
            <form action="{{ route('payment.pay', $article) }}" method="POST">
                @csrf
                <button type="submit" id="btn-pay-now"
                    class="w-full py-4 bg-[#8b1528] hover:bg-[#6b0f1f] text-white font-bold text-sm uppercase tracking-widest transition-colors flex items-center justify-center gap-3 group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Bayar Sekarang — Rp {{ number_format($payment->amount, 0, ',', '.') }}
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <p class="text-[11px] text-gray-400 text-center mt-4 leading-relaxed">
                Kamu akan diarahkan ke halaman pembayaran Mayar yang aman.<br>
                Setelah pembayaran berhasil, artikel akan otomatis terpublish sesuai jadwal.
            </p>

            {{-- Back link --}}
            <div class="text-center mt-4">
                <a href="{{ route('author.dashboard') }}" class="text-xs text-gray-400 hover:text-gray-600 transition-colors underline">
                    Back to Dashboard
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
