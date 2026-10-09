@extends('components.layouts.guest')

@section('title', 'Berita')

@use(App\Models\News)

@section('content')
@php
    $news = News::with('users')->where('slug', $slug)->firstOrFail();
    $related = News::where('status', 'published')
        ->where('id', '!=', $news->id)
        ->latest('published_at')
        ->take(3)
        ->get()
        ->map(function ($n) {
            return [
                'id' => $n->id,
                'title' => $n->title,
                'slug' => $n->slug,
                'thumbnail' => $n->thumbnail ?: 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=800&q=85',
                'date' => optional($n->published_at)->translatedFormat('d M Y') ?? '-',
            ];
        });
@endphp

<div x-data="newsShow()" class="min-h-screen bg-[#FFFDF7] text-[#171717]">
    {{-- Reading progress --}}
    <div class="fixed inset-x-0 top-0 z-50 h-1 bg-transparent">
        <div class="h-full bg-[#B91C1C] transition-[width] duration-150"
            :style="'width: ' + progress + '%'"></div>
    </div>

    {{-- Header --}}
    <header class="sticky top-1 z-40 border-b border-[#DED9CF] bg-[#FFFDF7]">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6 lg:px-8">
            <a href="/" class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center bg-[#B91C1C]">
                    <span class="text-lg font-black text-white">S</span>
                </div>
                <div class="text-left leading-none">
                    <div class="text-lg font-black tracking-tight">SEMAFE</div>
                    <div class="mt-1 text-[9px] font-bold uppercase tracking-[0.18em] text-[#B91C1C]">Fakultas Ekonomi
                    </div>
                </div>
            </a>

            <nav class="hidden items-center gap-7 lg:flex">
                <a href="/" class="text-[13px] font-semibold text-gray-600 transition hover:text-[#B91C1C]">Beranda</a>
                <a href="/news" class="text-[13px] font-semibold text-[#B91C1C]">Berita</a>
                <a href="/events" class="text-[13px] font-semibold text-gray-600 transition hover:text-[#B91C1C]">Kegiatan</a>
            </nav>

            <a href="/login" class="border border-[#B91C1C] px-5 py-2.5 text-xs font-bold text-[#B91C1C] transition hover:bg-[#B91C1C] hover:text-white">
                Login
            </a>
        </div>
    </header>

    <article class="mx-auto max-w-3xl px-6 py-12 lg:py-16">
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs font-semibold text-gray-400">
            <a href="/" class="hover:text-[#B91C1C]">Beranda</a>
            <span>/</span>
            <a href="/news" class="hover:text-[#B91C1C]">Berita</a>
            <span>/</span>
            <span class="text-gray-600">Detail</span>
        </div>

        <h1 class="mt-6 text-3xl font-black leading-tight sm:text-4xl">{{ $news->title }}</h1>

        <div class="mt-6 flex flex-wrap items-center gap-4 border-b border-[#DED9CF] pb-6 text-sm text-gray-500">
            <div class="flex items-center gap-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#B91C1C] text-xs font-bold text-white">
                    {{ strtoupper(substr($news->users?->name ?? 'A', 0, 1)) }}
                </div>
                <span class="font-semibold text-gray-700">{{ $news->users?->name ?? 'Admin SEMAFE' }}</span>
            </div>
            <span class="text-gray-300">•</span>
            <span>{{ optional($news->published_at)->translatedFormat('d F Y') ?? '-' }}</span>
            <span class="ml-auto bg-[#F1EDE4] px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider text-[#B91C1C]">
                {{ ucfirst($news->status) }}
            </span>
        </div>

        {{-- Cover --}}
        <div class="mt-8 aspect-16/9 overflow-hidden border border-[#DED9CF]">
            <img src="{{ $news->thumbnail ?: 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=1200&q=85' }}"
                alt="{{ $news->title }}" class="h-full w-full object-cover">
        </div>

        {{-- Content --}}
        <div class="prose mt-10 max-w-none text-[15px] leading-8 text-gray-700 whitespace-pre-line">
            {{ $news->content }}
        </div>

        {{-- Share --}}
        <div class="mt-12 flex flex-wrap items-center gap-4 border-t border-[#DED9CF] pt-6">
            <span class="text-sm font-bold text-gray-600">Bagikan:</span>
            <button @click="copyLink()"
                class="inline-flex items-center gap-2 border border-[#DED9CF] bg-white px-4 py-2 text-sm font-semibold text-gray-600 transition hover:border-[#B91C1C] hover:text-[#B91C1C]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                </svg>
                Salin tautan
            </button>
            <span x-show="copied" x-transition.opacity class="text-sm font-semibold text-green-600">Tautan disalin ✓</span>
        </div>
    </article>

    {{-- Related --}}
    @if ($related->isNotEmpty())
        <section class="border-t border-[#DED9CF] bg-[#F1EDE4] py-14">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="text-2xl font-black">Berita lainnya</h2>
                <div class="mt-8 grid gap-6 sm:grid-cols-3">
                    @foreach ($related as $r)
                        <a href="/news/{{ $r['slug'] }}" class="group border border-[#DED9CF] bg-white transition hover:shadow-lg">
                            <div class="aspect-16/10 overflow-hidden">
                                <img src="{{ $r['thumbnail'] }}" alt="{{ $r['title'] }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            </div>
                            <div class="p-5">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-[#B91C1C]">{{ $r['date'] }}</span>
                                <h3 class="mt-2 font-black leading-snug group-hover:text-[#B91C1C]">{{ $r['title'] }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Footer --}}
    <footer class="bg-[#171717] text-white">
        <div class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
            <div class="flex flex-col justify-between gap-4 text-xs text-white/30 sm:flex-row">
                <span>© {{ date('Y') }} SEMAFE Fakultas Ekonomi.</span>
                <span>Dibuat untuk mahasiswa.</span>
            </div>
        </div>
    </footer>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('newsShow', () => ({
        copied: false,
        progress: 0,

        init() {
            window.addEventListener('scroll', () => {
                const h = document.documentElement;
                const total = h.scrollHeight - h.clientHeight;
                this.progress = total > 0 ? Math.round(window.scrollY / total * 100) : 0;
            }, { passive: true });
        },

        copyLink() {
            navigator.clipboard?.writeText(window.location.href).then(() => {
                this.copied = true;
                setTimeout(() => (this.copied = false), 2500);
            });
        }
    }));
});
</script>
@endpush