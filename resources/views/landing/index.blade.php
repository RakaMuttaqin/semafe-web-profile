@extends('components.layouts.guest')

@section('title', 'SEMAFE — Senat Mahasiswa Fakultas Ekonomi')

@section('content')
    @php
        $newsData = \Illuminate\Support\Facades\Schema::hasTable('news')
            ? App\Models\News::where('status', 'published')
                ->latest('published_at')
                ->take(4)
                ->get()
                ->map(function ($n) {
                    return [
                        'id' => $n->id,
                        'title' => $n->title,
                        'slug' => $n->slug,
                        'excerpt' => Illuminate\Support\Str::limit(strip_tags($n->content), 140),
                        'thumbnail' => $n->thumbnail ?: 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1400&q=85',
                        'date' => optional($n->published_at)->translatedFormat('d M Y') ?? '-',
                        'status' => $n->status,
                    ];
                })
            : collect();

        $featuredNews = $newsData->first();
        $newsList = $newsData->skip(1);
    @endphp

    <div x-data="semafeLanding()" class="min-h-screen bg-[#FFFDF7] text-[#171717]">
        <header class="fixed inset-x-0 top-0 z-50 border-b border-[#DED9CF] bg-[#FFFDF7]">
            <nav class="mx-auto flex h-18.5 max-w-7xl items-center justify-between px-6 lg:px-8">
                {{-- LOGO --}}
                <button @click="scrollTo('home')" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center bg-[#B91C1C]">
                        <span class="text-lg font-black text-white">
                            S
                        </span>
                    </div>
                    <div class="text-left leading-none">
                        <div class="text-lg font-black tracking-tight">
                            SEMAFE
                        </div>
                        <div class="mt-1 text-[9px] font-bold uppercase tracking-[0.18em] text-[#B91C1C]">
                            Fakultas Ekonomi
                        </div>
                    </div>

                </button>


                {{-- DESKTOP NAV --}}
                <div class="hidden items-center lg:flex">

                    <div class="mr-8 flex items-center gap-7">

                        <template x-for="item in navigation" :key="item[0]">

                            <button @click="scrollTo(item[0])"
                                class="group relative py-7 text-[13px] font-semibold text-gray-600 transition hover:text-[#B91C1C]">

                                <span x-text="item[1]"></span>

                                <span x-show="activeSection === item[0]" x-transition
                                    class="absolute bottom-0 left-0 right-0 h-0.75 bg-[#B91C1C]"></span>

                            </button>

                        </template>

                    </div>


                    <div class="flex items-center gap-3">
                        {{-- Aspirasi --}}
                        <button @click="scrollTo('aspirasi')"
                            class="bg-[#B91C1C] px-5 py-2.5 text-xs font-bold text-white transition hover:bg-[#991B1B]">
                            Aspirasi
                        </button>
                    </div>

                </div>


                {{-- MOBILE --}}
                <button @click="mobileMenu = !mobileMenu"
                    class="flex h-10 w-10 items-center justify-center border border-[#DED9CF] lg:hidden">

                    <svg x-show="!mobileMenu" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    <svg x-show="mobileMenu" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-width="2" d="M6 6l12 12M6 18L18 6" />
                    </svg>

                </button>

            </nav>


            {{-- MOBILE MENU --}}
            <div x-show="mobileMenu" x-transition class="border-t border-[#DED9CF] bg-[#FFFDF7] lg:hidden">

                <div class="mx-auto max-w-7xl px-6 py-4">

                    <div class="divide-y divide-[#DED9CF]">

                        <template x-for="item in navigation" :key="item[0]">

                            <button @click="scrollTo(item[0])"
                                class="flex w-full items-center justify-between py-4 text-left text-sm font-semibold">

                                <span x-text="item[1]"></span>

                                <span class="text-[#B91C1C]">
                                    →
                                </span>

                            </button>

                        </template>

                    </div>


                    <button @click="scrollTo('aspirasi')"
                        class="mt-4 w-full bg-[#B91C1C] py-3 text-sm font-bold text-white">
                        Sampaikan Aspirasi
                    </button>

                </div>

            </div>

        </header>


        <main>

            <section id="home" class="scroll-mt-18.5 border-b border-[#DED9CF] bg-[#FFFDF7] pt-18.5">
                <div class="mx-auto grid min-h-[calc(100vh-74)] max-w-7xl lg:grid-cols-[0.9fr_1.1fr]">
                    {{-- LEFT --}}
                    <div class="flex flex-col justify-center px-6 py-20 lg:px-8 lg:py-24">
                        <div class="flex items-center gap-3">
                            <span class="h-0.5 w-8 bg-[#B91C1C]"></span>
                            <span class="text-xs font-black uppercase tracking-[0.2em] text-[#B91C1C]">
                                Senat Mahasiswa
                            </span>
                        </div>
                        <h1
                            class="mt-7 max-w-xl text-5xl font-black leading-[0.95] tracking-[-0.04em] sm:text-6xl lg:text-[76px]">
                            Ruang untuk
                            <span class="text-[#B91C1C]">
                                tumbuh.
                            </span>
                            <br>
                            Ruang untuk
                            <span class="relative inline-block">
                                bergerak.
                                <span class="absolute -bottom-2 left-0 h-1.25 w-full bg-[#F4C430]"></span>
                            </span>
                        </h1>
                        <p class="mt-8 max-w-lg text-base leading-8 text-gray-500">
                            SEMA Fakultas Ekonomi hadir sebagai wadah
                            mahasiswa untuk menyampaikan aspirasi,
                            mengembangkan potensi, dan membangun
                            kolaborasi.
                        </p>
                        <div class="mt-9 flex flex-wrap items-center gap-5">
                            <button @click="scrollTo('tentang')"
                                class="bg-[#B91C1C] px-6 py-3.5 text-sm font-bold text-white transition hover:bg-[#991B1B]">
                                Tentang SEMAFE
                                <span class="ml-2">
                                    →
                                </span>
                            </button>
                            <button @click="scrollTo('berita')"
                                class="text-sm font-bold text-[#171717] underline decoration-[#F4C430] decoration-2 underline-offset-8">
                                Lihat kegiatan
                            </button>
                        </div>
                        {{-- Small information --}}
                        <div class="mt-16 grid max-w-md grid-cols-3 border-t border-[#DED9CF] pt-6">
                            <div>
                                <p class="text-2xl font-black">
                                    01
                                </p>
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                    Kabinet
                                </p>
                            </div>
                            <div class="border-l border-[#DED9CF] pl-5">
                                <p class="text-2xl font-black">
                                    08
                                </p>
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                    Departemen
                                </p>
                            </div>
                            <div class="border-l border-[#DED9CF] pl-5">
                                <p class="text-2xl font-black">
                                    20+
                                </p>
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                    Program
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT IMAGE --}}
                    <div class="relative min-h-125 border-l border-[#DED9CF] lg:min-h-0">
                        <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1600&q=85"
                            alt="Mahasiswa Fakultas Ekonomi" class="absolute inset-0 h-4/5 w-full object-cover">
                        <div class="absolute inset-0 bg-black/10"></div>
                        <div class="absolute bottom-0 left-0 right-0 bg-[#171717]/90 p-6 backdrop-blur-sm">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#F4C430]">
                                        SEMAFE 2026
                                    </p>
                                    <p class="mt-2 text-sm font-semibold text-white">
                                        Bergerak bersama mahasiswa.
                                    </p>
                                </div>
                                <span class="text-2xl text-white">
                                    ↗
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @php
                $marqueeItems = [
                    'ASPIRASI',
                    'KOLABORASI',
                    'AKSI',
                    'MAHASISWA',
                    'FAKULTAS EKONOMI',
                    'SEMAFE 2026',
                    'EKATAMA 2026',
                ];
            @endphp
            </section>


            <div class="overflow-hidden border-b border-[#DED9CF] bg-[#B91C1C]">

                <div class="flex w-max animate-[marquee_30s_linear_infinite]">

                    {{-- Set pertama --}}
                    @foreach ($marqueeItems as $item)
                        <div class="flex shrink-0 items-center gap-8 px-8 py-3">

                            <span class="text-xs font-black tracking-[0.2em] text-white">
                                {{ $item }}
                            </span>

                            <span class="text-[#F4C430]">
                                ◆
                            </span>

                        </div>
                    @endforeach


                    {{-- Set kedua --}}
                    @foreach ($marqueeItems as $item)
                        <div class="flex shrink-0 items-center gap-8 px-8 py-3">

                            <span class="text-xs font-black tracking-[0.2em] text-white">
                                {{ $item }}
                            </span>

                            <span class="text-[#F4C430]">
                                ◆
                            </span>

                        </div>
                    @endforeach

                </div>

            </div>


            <section id="tentang" class="scroll-mt-18.5 border-b border-[#DED9CF] bg-[#FFFDF7] py-24 sm:py-32">

                <div class="mx-auto max-w-7xl px-6 lg:px-8">

                    <div class="grid gap-16 lg:grid-cols-[0.5fr_1.5fr]">

                        {{-- Number --}}
                        <div>

                            <span class="text-sm font-black text-[#B91C1C]">
                                01
                            </span>

                            <p class="mt-3 text-xs font-bold uppercase tracking-[0.2em] text-gray-400">
                                Tentang kami
                            </p>

                        </div>


                        {{-- Content --}}
                        <div>

                            <h2 class="max-w-4xl text-4xl font-black leading-tight tracking-tight sm:text-5xl lg:text-6xl">

                                Bukan sekadar organisasi.
                                <span class="text-[#B91C1C]">
                                    Ini ruang bersama.
                                </span>

                            </h2>


                            <div class="mt-10 grid gap-10 md:grid-cols-2">

                                <p class="leading-8 text-gray-500">
                                    SEMA Fakultas Ekonomi merupakan organisasi
                                    mahasiswa yang menjadi ruang untuk
                                    mengembangkan kemampuan, menyampaikan
                                    aspirasi, dan membangun hubungan antar
                                    mahasiswa.
                                </p>

                                <p class="leading-8 text-gray-500">
                                    Kami percaya bahwa organisasi yang baik
                                    bukan hanya menghasilkan kegiatan,
                                    tetapi juga menciptakan pengalaman,
                                    kesempatan, dan dampak bagi mahasiswa.
                                </p>

                            </div>


                            {{-- Values --}}
                            <div class="mt-14 grid border-y border-[#DED9CF] md:grid-cols-3">

                                <div class="border-b border-[#DED9CF] py-7 md:border-b-0 md:border-r md:pr-8">

                                    <span class="text-[#B91C1C]">
                                        01
                                    </span>

                                    <h3 class="mt-3 font-black">
                                        Aspiratif
                                    </h3>

                                    <p class="mt-2 text-sm leading-6 text-gray-500">
                                        Mendengar dan membawa suara mahasiswa.
                                    </p>

                                </div>


                                <div class="border-b border-[#DED9CF] py-7 md:border-b-0 md:px-8 md:border-r">

                                    <span class="text-[#B91C1C]">
                                        02
                                    </span>

                                    <h3 class="mt-3 font-black">
                                        Kolaboratif
                                    </h3>

                                    <p class="mt-2 text-sm leading-6 text-gray-500">
                                        Membuka ruang untuk bekerja bersama.
                                    </p>

                                </div>


                                <div class="py-7 md:pl-8">

                                    <span class="text-[#B91C1C]">
                                        03
                                    </span>

                                    <h3 class="mt-3 font-black">
                                        Berdampak
                                    </h3>

                                    <p class="mt-2 text-sm leading-6 text-gray-500">
                                        Mengubah gagasan menjadi aksi nyata.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

            <section id="kabinet" class="scroll-mt-18.5 bg-[#171717] py-24 text-white sm:py-32">

                <div class="mx-auto max-w-7xl px-6 lg:px-8">

                    <div class="grid gap-10 lg:grid-cols-[0.5fr_1.5fr]">

                        <div>

                            <span class="text-sm font-black text-[#F4C430]">
                                02
                            </span>

                            <p class="mt-3 text-xs font-bold uppercase tracking-[0.2em] text-white/40">
                                Kabinet
                            </p>

                        </div>


                        <div>

                            <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">

                                <h2 class="max-w-3xl text-4xl font-black leading-tight sm:text-5xl lg:text-6xl">

                                    Mereka yang
                                    <span class="text-[#B91C1C]">
                                        mengambil peran.
                                    </span>

                                </h2>

                                <span class="text-sm text-white/30">
                                    Kabinet SEMAFE 2026
                                </span>

                            </div>


                            {{-- Leaders --}}
                            <div class="mt-14 grid gap-6 sm:grid-cols-2">

                                {{-- Ketua --}}
                                <article class="group">

                                    <div class="aspect-4/5 overflow-hidden bg-[#242424]">

                                        <img src="{{ asset('storage/foto/' . 'KETUA.jpg') }}" alt="Ketua Umum"
                                            class="h-full w-full object-cover grayscale transition duration-500 group-hover:grayscale-0">

                                    </div>

                                    <div class="border-b border-white/10 py-5">

                                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#F4C430]">
                                            Ketua Umum
                                        </p>

                                        <h3 class="mt-2 text-xl font-black">
                                            M Gilar Ramadhan
                                        </h3>

                                    </div>

                                </article>


                                {{-- Wakil --}}
                                <article class="group">
                                    <div class="aspect-4/5 overflow-hidden bg-[#242424]">
                                        <img src="{{ asset('storage/foto/' . 'WAKETU.jpg') }}" alt="Wakil Ketua"
                                            class="h-full w-full object-cover grayscale transition duration-500 group-hover:grayscale-0">
                                    </div>
                                    <div class="border-b border-white/10 py-5">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#F4C430]">
                                            Wakil Ketua
                                        </p>
                                        <h3 class="mt-2 text-xl font-black">
                                            Abdul Latief
                                        </h3>
                                    </div>
                                </article>
                            </div>


                            {{-- Organization --}}
                            <div class="mt-12 border-t border-white/10">

                                @foreach (['Sekretaris', 'Bendahara', 'Departemen PSDM', 'Departemen Humas', 'Departemen Kegiatan'] as $index => $position)
                                    <div class="group flex items-center justify-between border-b border-white/10 py-5">

                                        <div class="flex items-center gap-6">

                                            <span class="text-xs text-white/30">
                                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                            </span>

                                            <span class="font-semibold">
                                                {{ $position }}
                                            </span>

                                        </div>

                                        <span
                                            class="text-white/20 transition group-hover:translate-x-2 group-hover:text-[#F4C430]">
                                            →
                                        </span>

                                    </div>
                                @endforeach

                            </div>

                        </div>

                    </div>

                </div>

            </section>


            <section id="program" class="scroll-mt-18.5 border-b border-[#DED9CF] bg-[#FFFDF7] py-24 sm:py-32">

                <div class="mx-auto max-w-7xl px-6 lg:px-8">

                    <div class="grid gap-10 lg:grid-cols-[0.5fr_1.5fr]">

                        <div>

                            <span class="text-sm font-black text-[#B91C1C]">
                                03
                            </span>

                            <p class="mt-3 text-xs font-bold uppercase tracking-[0.2em] text-gray-400">
                                Program kerja
                            </p>

                        </div>


                        <div>

                            <h2 class="max-w-3xl text-4xl font-black leading-tight sm:text-5xl lg:text-6xl">

                                Gagasan yang
                                <span class="text-[#B91C1C]">
                                    dikerjakan.
                                </span>

                            </h2>


                            <div class="mt-14 border-t border-[#DED9CF]">

                                @foreach ([['01', 'Pengembangan Mahasiswa', 'Ruang belajar dan pengembangan kemampuan mahasiswa.'], ['02', 'Aspirasi Mahasiswa', 'Menampung dan menyampaikan suara mahasiswa.'], ['03', 'Kolaborasi', 'Membangun kerja sama antar mahasiswa dan organisasi.'], ['04', 'Pengabdian', 'Menghadirkan kontribusi bagi lingkungan sekitar.']] as $program)
                                    <article
                                        class="group grid gap-6 border-b border-[#DED9CF] py-8 transition hover:px-4 sm:grid-cols-[60px_1fr_30px] sm:items-center">

                                        <span class="text-sm font-black text-[#B91C1C]">
                                            {{ $program[0] }}
                                        </span>

                                        <div>

                                            <h3 class="text-xl font-black sm:text-2xl">
                                                {{ $program[1] }}
                                            </h3>

                                            <p class="mt-2 max-w-xl text-sm leading-6 text-gray-500">
                                                {{ $program[2] }}
                                            </p>

                                        </div>

                                        <span
                                            class="text-xl text-gray-300 transition group-hover:translate-x-1 group-hover:text-[#B91C1C]">
                                            →
                                        </span>

                                    </article>
                                @endforeach

                            </div>

                        </div>

                    </div>

                </div>

            </section>

            <section id="berita" class="scroll-mt-18.5 border-b border-[#DED9CF] bg-[#F1EDE4] py-24 sm:py-32">

                <div class="mx-auto max-w-7xl px-6 lg:px-8">

                    <div class="flex flex-col justify-between gap-6 md:flex-row md:items-end">

                        <div>

                            <span class="text-sm font-black text-[#B91C1C]">
                                04
                            </span>

                            <p class="mt-3 text-xs font-bold uppercase tracking-[0.2em] text-gray-400">
                                Kabar terbaru
                            </p>

                        </div>


                        <div class="max-w-3xl">

                            <h2 class="text-4xl font-black leading-tight sm:text-5xl">
                                Cerita dari
                                <span class="text-[#B91C1C]">
                                    lapangan.
                                </span>
                            </h2>

                        </div>

                    </div>


                    {{-- Featured News --}}
                    @if ($featuredNews)
                        <a href="{{ url('/news/' . $featuredNews['slug']) }}"
                            class="mt-14 grid overflow-hidden bg-white group lg:grid-cols-[1.2fr_0.8fr] transition hover:shadow-lg">

                            <div class="aspect-16/10 lg:aspect-auto">
                                <img src="{{ $featuredNews['thumbnail'] }}" alt="{{ $featuredNews['title'] }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.02]">
                            </div>

                            <div class="flex flex-col justify-center p-8 sm:p-12">

                                <div class="flex items-center gap-3 text-[10px] font-black uppercase tracking-[0.15em]">
                                    <span class="text-[#B91C1C]">
                                        {{ $featuredNews['status'] === 'published' ? 'Berita' : ucfirst($featuredNews['status']) }}
                                    </span>
                                    <span class="text-gray-400">
                                        {{ $featuredNews['date'] }}
                                    </span>
                                </div>

                                <h3 class="mt-5 text-3xl font-black leading-tight">
                                    {{ $featuredNews['title'] }}
                                </h3>

                                <p class="mt-5 text-sm leading-7 text-gray-500">
                                    {{ $featuredNews['excerpt'] }}
                                </p>

                                <span class="mt-8 w-fit border-b-2 border-[#F4C430] pb-1 text-sm font-black">
                                    Baca selengkapnya →
                                </span>
                            </div>
                        </a>
                    @endif

                    {{-- News list --}}
                    @if ($newsList->isNotEmpty())
                        <div class="mt-8 grid border-t border-[#CEC8BC]">
                            @foreach ($newsList as $news)
                                <a href="{{ url('/news/' . $news['slug']) }}"
                                    class="group grid gap-4 border-b border-[#CEC8BC] py-6 sm:grid-cols-[160px_1fr_auto] sm:items-center">

                                    <span class="text-xs font-bold uppercase tracking-wider text-[#B91C1C]">
                                        {{ $news['date'] }}
                                    </span>

                                    <h3 class="font-black group-hover:text-[#B91C1C] transition-colors">
                                        {{ $news['title'] }}
                                    </h3>

                                    <span
                                        class="text-gray-400 transition group-hover:translate-x-1 group-hover:text-[#B91C1C]">
                                        →
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-14 border border-dashed border-[#CEC8BC] bg-white py-16 text-center">
                            <p class="text-sm font-semibold text-gray-500">Belum ada berita yang dipublikasikan</p>
                        </div>
                    @endif

                    <div class="mt-10 flex justify-center">
                        <a href="{{ url('/news') }}"
                            class="bg-[#B91C1C] px-6 py-3.5 text-sm font-bold text-white transition hover:bg-[#991B1B]">
                            Lihat semua berita →
                        </a>
                    </div>

                </div>

            </section>

            <section id="aspirasi" class="scroll-mt-18.5 bg-[#B91C1C] py-24 sm:py-32">

                <div class="mx-auto max-w-5xl px-6 text-center lg:px-8">

                    <p class="text-xs font-black uppercase tracking-[0.25em] text-[#F4C430]">
                        Ruang Aspirasi
                    </p>


                    <h2 class="mt-6 text-4xl font-black leading-tight text-white sm:text-6xl">

                        Ada yang ingin
                        <br>
                        kamu sampaikan?

                    </h2>


                    <p class="mx-auto mt-6 max-w-xl leading-8 text-red-100">
                        Kritik, ide, masukan, atau keresahan.
                        Sampaikan kepada kami dan mari cari jalan keluarnya bersama.
                    </p>


                    <button
                        class="mt-9 bg-white px-8 py-4 text-sm font-black text-[#B91C1C] transition hover:bg-[#FFFDF7]">
                        Sampaikan Aspirasi →
                    </button>

                </div>

            </section>

            {{-- FOOTER --}}
            <footer class="bg-[#171717] text-white">

                <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8">

                    <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-[1.5fr_0.5fr_0.5fr]">

                        {{-- Brand --}}
                        <div>

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center bg-[#B91C1C]">

                                    <span class="font-black">
                                        S
                                    </span>

                                </div>

                                <div>

                                    <p class="font-black">
                                        SEMAFE
                                    </p>

                                    <p class="text-[9px] uppercase tracking-widest text-[#F4C430]">
                                        Fakultas Ekonomi
                                    </p>

                                </div>

                            </div>


                            <p class="mt-6 max-w-md text-sm leading-7 text-white/40">
                                Senat Mahasiswa Fakultas Ekonomi sebagai
                                ruang bersama untuk tumbuh, bergerak,
                                dan memberikan dampak.
                            </p>

                        </div>


                        {{-- Navigation --}}
                        <div>

                            <p class="text-xs font-bold uppercase tracking-widest text-white/30">
                                Navigasi
                            </p>

                            <div class="mt-5 space-y-3 text-sm text-white/50">

                                <button @click="scrollTo('tentang')" class="block hover:text-white">
                                    Tentang
                                </button>

                                <button @click="scrollTo('kabinet')" class="block hover:text-white">
                                    Kabinet
                                </button>

                                <button @click="scrollTo('program')" class="block hover:text-white">
                                    Program
                                </button>

                                <button @click="scrollTo('berita')" class="block hover:text-white">
                                    Berita
                                </button>

                            </div>

                        </div>


                        {{-- Social --}}
                        <div>

                            <p class="text-xs font-bold uppercase tracking-widest text-white/30">
                                Ikuti kami
                            </p>

                            <div class="mt-5 space-y-3 text-sm text-white/50">

                                <a href="https://instagram.com/semafe.unpi" class="block hover:text-[#F4C430]">
                                    Instagram
                                </a>

                                <a href="mailto:semafeunpi25@gmail.com" class="block hover:text-[#F4C430]">
                                    Email
                                </a>

                            </div>

                        </div>

                    </div>


                    <div class="mt-14 border-t border-white/10 pt-6">

                        <div class="flex flex-col justify-between gap-3 text-xs text-white/30 sm:flex-row">

                            <span>
                                © {{ date('Y') }} SEMAFE Fakultas Ekonomi.
                            </span>

                            <span>
                                Dibuat untuk mahasiswa.
                            </span>

                        </div>

                    </div>

                </div>

            </footer>

        </main>

    </div>


    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {

                Alpine.data('semafeLanding', () => ({

                    mobileMenu: false,

                    activeSection: 'home',

                    navigation: [
                        ['home', 'Beranda'],
                        ['tentang', 'Tentang'],
                        ['kabinet', 'Kabinet'],
                        ['program', 'Program'],
                        ['berita', 'Berita'],
                    ],


                    init() {

                        const sections = [
                            'home',
                            'tentang',
                            'kabinet',
                            'program',
                            'berita'
                        ];


                        const observer = new IntersectionObserver(

                            (entries) => {

                                entries.forEach((entry) => {

                                    if (entry.isIntersecting) {

                                        this.activeSection = entry.target.id;

                                    }

                                });

                            },

                            {
                                rootMargin: '-30% 0px -60% 0px'
                            }

                        );


                        sections.forEach((id) => {

                            const section = document.getElementById(id);

                            if (section) {
                                observer.observe(section);
                            }

                        });

                    },


                    scrollTo(id) {

                        this.mobileMenu = false;

                        const element = document.getElementById(id);

                        if (!element) return;


                        const navbarHeight = 74;

                        const position =
                            element.getBoundingClientRect().top +
                            window.scrollY -
                            navbarHeight;


                        window.scrollTo({

                            top: position,

                            behavior: 'smooth'

                        });

                    }

                }));

            });
        </script>
    @endpush


    <style>
        @keyframes marquee {

            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }

        }
    </style>

@endsection
