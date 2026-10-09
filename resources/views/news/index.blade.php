@extends('components.layouts.guest')

@section('title', 'Berita')

@use(App\Models\News)

@section('content')
@php
    $newsList = Illuminate\Support\Facades\Schema::hasTable('news')
        ? News::with('users')
            ->where('status', 'published')
            ->latest('published_at')
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'slug' => $n->slug,
                    'excerpt' => \Illuminate\Support\Str::limit(strip_tags($n->content), 160),
                    'thumbnail' => $n->thumbnail ?: 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=800&q=85',
                    'date' => optional($n->published_at)->translatedFormat('d M Y') ?? '-',
                    'author' => $n->users?->name ?? 'Admin SEMAFE',
                ];
            })
        : collect();
@endphp

<div x-data="newsIndex()" class="min-h-screen bg-[#FFFDF7] text-[#171717]">
    {{-- Header --}}
    <header class="sticky top-0 z-40 border-b border-[#DED9CF] bg-[#FFFDF7]">
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

    <main class="mx-auto max-w-7xl px-6 py-12 lg:px-8 lg:py-16">
        {{-- Heading --}}
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
            <div>
                <div class="flex items-center gap-3">
                    <span class="h-0.5 w-8 bg-[#B91C1C]"></span>
                    <span class="text-xs font-black uppercase tracking-[0.2em] text-[#B91C1C]">Kabar terbaru</span>
                </div>
                <h1 class="mt-5 text-4xl font-black tracking-tight sm:text-5xl">
                    Berita &
                    <span class="text-[#B91C1C]">Informasi.</span>
                </h1>
            </div>

            <div class="relative w-full md:w-72">
                <input type="text" x-model="search" placeholder="Cari berita..."
                    class="w-full border border-[#DED9CF] bg-white py-2.5 pl-10 pr-4 text-sm focus:border-[#B91C1C] focus:outline-none">
                <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        {{-- Featured --}}
        <template x-if="featured && !search">
            <article class="mt-12 grid overflow-hidden border border-[#DED9CF] bg-white lg:grid-cols-[1.2fr_0.8fr]">
                <div class="aspect-16/10 lg:aspect-auto">
                    <img :src="featured.thumbnail" :alt="featured.title" class="h-full w-full object-cover">
                </div>
                <div class="flex flex-col justify-center p-8 sm:p-12">
                    <span
                        class="inline-flex w-fit items-center bg-[#B91C1C] px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.15em] text-white">
                        Terbaru
                    </span>
                    <h2 class="mt-5 text-3xl font-black leading-tight" x-text="featured.title"></h2>
                    <p class="mt-4 text-sm leading-7 text-gray-500" x-text="featured.excerpt"></p>
                    <div class="mt-6 flex items-center gap-3 text-xs text-gray-400">
                        <span x-text="featured.date"></span>
                        <span>•</span>
                        <span x-text="featured.author"></span>
                    </div>
                    <a :href="'/news/' + featured.slug"
                        class="mt-8 w-fit border-b-2 border-[#F4C430] pb-1 text-sm font-black transition hover:border-[#B91C1C]">
                        Baca selengkapnya →
                    </a>
                </div>
            </article>
        </template>

        {{-- Grid --}}
        <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3" x-show="filtered.length > 0">
            <template x-for="n in filtered" :key="n.id">
                <article class="group flex flex-col border border-[#DED9CF] bg-white transition hover:shadow-lg">
                    <div class="aspect-16/10 overflow-hidden bg-[#F1EDE4]">
                        <img :src="n.thumbnail" :alt="n.title"
                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    </div>
                    <div class="flex flex-1 flex-col p-6">
                        <div class="flex items-center gap-3 text-[11px] font-bold uppercase tracking-wider text-[#B91C1C]">
                            <span x-text="n.date"></span>
                        </div>
                        <h3 class="mt-3 text-lg font-black leading-snug" x-text="n.title"></h3>
                        <p class="mt-3 flex-1 text-sm leading-6 text-gray-500" x-text="n.excerpt"></p>
                        <a :href="'/news/' + n.slug"
                            class="mt-5 w-fit border-b-2 border-[#F4C430] pb-0.5 text-sm font-black transition hover:border-[#B91C1C]">
                            Baca selengkapnya →
                        </a>
                    </div>
                </article>
            </template>
        </div>

        {{-- Empty state --}}
        <div x-show="filtered.length === 0" class="mt-12 border border-dashed border-[#DED9CF] bg-white py-20 text-center">
            <p class="text-sm font-semibold text-gray-500" x-text="search ? 'Berita tidak ditemukan' : 'Belum ada berita dipublikasikan'"></p>
        </div>
    </main>

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
    Alpine.data('newsIndex', () => ({
        news: @json($newsList),
        search: '',
        get featured() {
            return this.news[0] ?? null;
        },
        get filtered() {
            if (!this.search) return this.news;
            const s = this.search.toLowerCase();
            return this.news.filter(n =>
                n.title.toLowerCase().includes(s) ||
                n.excerpt.toLowerCase().includes(s) ||
                n.author.toLowerCase().includes(s)
            );
        }
    }));
});
</script>
@endpush