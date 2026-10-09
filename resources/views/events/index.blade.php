@extends('components.layouts.guest')

@section('title', 'Kegiatan')

@use(App\Models\Events)

@section('content')
@php
    $fmt = fn($d, $f) => $d ? \Carbon\Carbon::parse($d)->translatedFormat($f) : null;

    $events = Illuminate\Support\Facades\Schema::hasTable('events')
        ? Events::where('status', 'published')
            ->orderBy('start_at')
            ->get()
            ->map(function ($e) use ($fmt) {
                return [
                    'id' => $e->id,
                    'title' => $e->title,
                    'slug' => $e->slug,
                    'description' => \Illuminate\Support\Str::limit(strip_tags($e->description ?? ''), 160),
                    'image' => $e->image ?: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=85',
                    'location' => $e->location ?? '-',
                    'day' => $fmt($e->start_at, 'd') ?? '-',
                    'month' => strtoupper($fmt($e->start_at, 'M') ?? '-'),
                    'year' => $fmt($e->start_at, 'Y') ?? '-',
                    'time' => ($fmt($e->start_at, 'H:i') ?? '-') . ' - ' . ($fmt($e->end_at, 'H:i') ?? '-') . ' WIB',
                    'date_display' => ($fmt($e->start_at, 'd M Y') ?? '-') . ' • ' . ($fmt($e->start_at, 'H:i') ?? '-') . ' WIB',
                    'is_upcoming' => $e->start_at && \Carbon\Carbon::parse($e->start_at)->isFuture(),
                ];
            })
        : collect();

    $upcoming = $events->where('is_upcoming', true);
    $past = $events->where('is_upcoming', false);
@endphp

<div x-data="eventsIndex()" class="min-h-screen bg-[#FFFDF7] text-[#171717]">
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
                <a href="/news" class="text-[13px] font-semibold text-gray-600 transition hover:text-[#B91C1C]">Berita</a>
                <a href="/events" class="text-[13px] font-semibold text-[#B91C1C]">Kegiatan</a>
            </nav>

            <a href="/login" class="border border-[#B91C1C] px-5 py-2.5 text-xs font-bold text-[#B91C1C] transition hover:bg-[#B91C1C] hover:text-white">
                Login
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 py-12 lg:px-8 lg:py-16">
        <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">
            <div>
                <div class="flex items-center gap-3">
                    <span class="h-0.5 w-8 bg-[#B91C1C]"></span>
                    <span class="text-xs font-black uppercase tracking-[0.2em] text-[#B91C1C]">Agenda</span>
                </div>
                <h1 class="mt-5 text-4xl font-black tracking-tight sm:text-5xl">
                    Kegiatan &
                    <span class="text-[#B91C1C]">Acara.</span>
                </h1>
            </div>

            {{-- Tabs --}}
            <div class="inline-flex border border-[#DED9CF] bg-white p-1">
                <button @click="tab = 'upcoming'"
                    class="px-5 py-2 text-sm font-bold transition"
                    :class="tab === 'upcoming' ? 'bg-[#B91C1C] text-white' : 'text-gray-600 hover:text-[#B91C1C]'">
                    Mendatang
                </button>
                <button @click="tab = 'past'"
                    class="px-5 py-2 text-sm font-bold transition"
                    :class="tab === 'past' ? 'bg-[#B91C1C] text-white' : 'text-gray-600 hover:text-[#B91C1C]'">
                    Selesai
                </button>
            </div>
        </div>

        {{-- Search --}}
        <div class="relative mt-10">
            <input type="text" x-model="search" placeholder="Cari kegiatan atau lokasi..."
                class="w-full border border-[#DED9CF] bg-white py-3 pl-11 pr-4 text-sm focus:border-[#B91C1C] focus:outline-none">
            <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>

        {{-- List --}}
        <div class="mt-8 space-y-5" x-show="visible.length > 0">
            <template x-for="e in visible" :key="e.id">
                <article
                    class="group grid gap-0 border border-[#DED9CF] bg-white transition hover:shadow-lg sm:grid-cols-[120px_1fr_280px]">
                    {{-- Date block --}}
                    <div class="flex flex-col items-center justify-center border-b border-[#DED9CF] bg-[#F1EDE4] py-6 sm:border-b-0 sm:border-r">
                        <span class="text-3xl font-black text-[#B91C1C]" x-text="e.day"></span>
                        <span class="mt-1 text-xs font-bold uppercase tracking-widest text-gray-500" x-text="e.month"></span>
                        <span class="mt-0.5 text-[11px] text-gray-400" x-text="e.year"></span>
                    </div>

                    {{-- Info --}}
                    <div class="flex flex-col justify-center p-6">
                        <span x-show="e.is_upcoming"
                            class="inline-flex w-fit items-center bg-[#F4C430] px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.15em] text-[#171717]">
                            Akan datang
                        </span>
                        <h2 class="mt-2 text-xl font-black leading-snug group-hover:text-[#B91C1C]" x-text="e.title"></h2>
                        <p class="mt-2 text-sm leading-6 text-gray-500" x-text="e.description"></p>
                        <div class="mt-4 flex flex-wrap items-center gap-4 text-xs font-semibold text-gray-500">
                            <span class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-[#B91C1C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span x-text="e.location"></span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg class="h-4 w-4 text-[#B91C1C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span x-text="e.time"></span>
                            </span>
                        </div>
                    </div>

                    {{-- Image + CTA --}}
                    <div class="relative min-h-40 overflow-hidden border-t border-[#DED9CF] sm:border-l sm:border-t-0">
                        <img :src="e.image" :alt="e.title" class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-[#171717]/50 transition group-hover:bg-[#171717]/30"></div>
                        <a :href="'/events/' + e.slug"
                            class="absolute inset-0 flex items-center justify-center">
                            <span class="bg-white px-5 py-2.5 text-xs font-black text-[#B91C1C] transition group-hover:bg-[#B91C1C] group-hover:text-white">
                                Lihat detail →
                            </span>
                        </a>
                    </div>
                </article>
            </template>
        </div>

        {{-- Empty state --}}
        <div x-show="visible.length === 0" class="mt-10 border border-dashed border-[#DED9CF] bg-white py-20 text-center">
            <p class="text-sm font-semibold text-gray-500"
                x-text="search ? 'Kegiatan tidak ditemukan' : (tab === 'upcoming' ? 'Belum ada kegiatan mendatang' : 'Belum ada kegiatan selesai')">
            </p>
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
    Alpine.data('eventsIndex', () => ({
        upcoming: @json($upcoming->values()),
        past: @json($past->values()),
        tab: 'upcoming',
        search: '',
        get visible() {
            const list = this.tab === 'upcoming' ? this.upcoming : this.past;
            if (!this.search) return list;
            const s = this.search.toLowerCase();
            return list.filter(e =>
                e.title.toLowerCase().includes(s) ||
                e.location.toLowerCase().includes(s) ||
                e.description.toLowerCase().includes(s)
            );
        }
    }));
});
</script>
@endpush