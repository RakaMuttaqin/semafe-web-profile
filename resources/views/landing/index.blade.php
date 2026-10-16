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

        $has = fn($table) => \Illuminate\Support\Facades\Schema::hasTable($table);
        $stat = [
            'members' => $has('members') ? App\Models\Member::count() : 0,
            'divisions' => $has('divisions') ? App\Models\Division::count() : 0,
            'events' => $has('events') ? App\Models\Events::where('status', 'published')->count() : 0,
        ];

        // Program kerja = event dari tiap divisi
        $programEvents = $has('events')
            ? App\Models\Events::where('status', 'published')
                ->orderBy('start_at')
                ->take(4)
                ->get()
                ->map(fn($e) => [
                    'title' => $e->title,
                    'slug' => $e->slug,
                    'description' => \Illuminate\Support\Str::limit(strip_tags($e->description ?? ''), 110),
                    'location' => $e->location ?? '-',
                    'date' => optional($e->start_at)->translatedFormat('d M Y') ?? '-',
                ])
                ->values()
            : collect();

        // Foto pengurus — pakai file bila ada, jika tidak pakai avatar
        $kabinetPhoto = function ($file, $name) {
            if ($file && file_exists(public_path('storage/foto/' . $file))) {
                return asset('storage/foto/' . $file);
            }

            return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=1f2937&color=fff&size=600';
        };

        // Struktur kabinet (accordion)
        $orgStructure = [
            ['name' => 'Sekretaris', 'desc' => 'Administrasi surat-menyurat, notulen rapat, dan pengarsipan organisasi.'],
            ['name' => 'Bendahara', 'desc' => 'Pengelolaan keuangan, penyusunan anggaran, dan laporan kas organisasi.'],
            ['name' => 'Departemen PSDM', 'desc' => 'Pengembangan sumber daya mahasiswa, kaderisasi, dan rekrutmen anggota.'],
            ['name' => 'Departemen Humas', 'desc' => 'Hubungan eksternal, publikasi, dan komunikasi organisasi.'],
            ['name' => 'Departemen Kegiatan', 'desc' => 'Perencanaan dan pelaksanaan program kerja serta event organisasi.'],
        ];
    @endphp

    <div x-data="semafeLanding()" class="flex min-h-screen flex-col bg-[#FFFDF7] text-[#171717]">
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

                        <a href="{{ url('/news') }}"
                            class="py-7 text-[13px] font-semibold text-gray-600 transition hover:text-[#B91C1C]">
                            Berita
                        </a>

                        <a href="{{ url('/events') }}"
                            class="py-7 text-[13px] font-semibold text-gray-600 transition hover:text-[#B91C1C]">
                            Kegiatan
                        </a>

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

                        <a href="{{ url('/news') }}"
                            class="flex w-full items-center justify-between py-4 text-left text-sm font-semibold">
                            <span>Berita</span>
                            <span class="text-[#B91C1C]">→</span>
                        </a>

                        <a href="{{ url('/events') }}"
                            class="flex w-full items-center justify-between py-4 text-left text-sm font-semibold">
                            <span>Kegiatan</span>
                            <span class="text-[#B91C1C]">→</span>
                        </a>

                    </div>


                    <button @click="scrollTo('aspirasi')"
                        class="mt-4 w-full bg-[#B91C1C] py-3 text-sm font-bold text-white">
                        Sampaikan Aspirasi
                    </button>

                </div>

            </div>

        </header>


        <main class="flex-1">

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
                            <a href="{{ url('/events') }}"
                                class="text-sm font-bold text-[#171717] underline decoration-[#F4C430] decoration-2 underline-offset-8">
                                Lihat kegiatan
                            </a>
                        </div>
                        {{-- Small information --}}
                        <div class="mt-16 grid max-w-md grid-cols-3 border-t border-[#DED9CF] pt-6">
                            <div>
                                <p class="text-2xl font-black">
                                    {{ str_pad($stat['members'], 2, '0', STR_PAD_LEFT) }}
                                </p>
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                    Anggota
                                </p>
                            </div>
                            <div class="border-l border-[#DED9CF] pl-5">
                                <p class="text-2xl font-black">
                                    {{ str_pad($stat['divisions'], 2, '0', STR_PAD_LEFT) }}
                                </p>
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                    Divisi
                                </p>
                            </div>
                            <div class="border-l border-[#DED9CF] pl-5">
                                <p class="text-2xl font-black">
                                    {{ str_pad($stat['events'], 2, '0', STR_PAD_LEFT) }}
                                </p>
                                <p class="mt-1 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                                    Kegiatan
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

                                        <img src="{{ $kabinetPhoto('KETUA.jpg', 'M Gilar Ramadhan') }}" alt="Ketua Umum"
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
                                        <img src="{{ $kabinetPhoto('WAKETU.jpg', 'Abdul Latief') }}" alt="Wakil Ketua"
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


                            {{-- Organization (accordion) --}}
                            <div class="mt-12 border-t border-white/10" x-data="{ openPanel: 0 }">

                                @foreach ($orgStructure as $index => $item)
                                    <div class="border-b border-white/10">

                                        <button type="button"
                                            @click="openPanel = (openPanel === {{ $index }}) ? null : {{ $index }}"
                                            class="group flex w-full items-center justify-between gap-4 py-5 text-left">

                                            <div class="flex items-center gap-6">

                                                <span class="text-xs transition-colors duration-300"
                                                    :class="openPanel === {{ $index }} ? 'text-[#F4C430]' : 'text-white/30'">
                                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                                </span>

                                                <span class="font-semibold transition-colors duration-300"
                                                    :class="openPanel === {{ $index }} ? 'text-[#F4C430]' : 'text-white'">
                                                    {{ $item['name'] }}
                                                </span>

                                            </div>

                                            <svg class="h-4 w-4 shrink-0 transition-transform duration-300"
                                                :class="openPanel === {{ $index }} ? 'rotate-90 text-[#F4C430]' :
                                                    'text-white/30'"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>

                                        </button>

                                        <div x-show="openPanel === {{ $index }}" x-cloak
                                            x-transition:enter="transition-all ease-out duration-300"
                                            x-transition:enter-start="opacity-0 max-h-0"
                                            x-transition:enter-end="opacity-100 max-h-40"
                                            x-transition:leave="transition-all ease-in duration-200"
                                            x-transition:leave-start="opacity-100 max-h-40"
                                            x-transition:leave-end="opacity-0 max-h-0"
                                            class="overflow-hidden">

                                            <p class="pb-6 pl-12 pr-10 text-sm leading-7 text-white/50">
                                                {{ $item['desc'] }}
                                            </p>

                                        </div>

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

                                @forelse ($programEvents as $index => $program)
                                    <a href="{{ url('/events/' . $program['slug']) }}"
                                        class="group grid gap-6 border-b border-[#DED9CF] py-8 transition hover:px-4 sm:grid-cols-[60px_1fr_30px] sm:items-center">

                                        <span class="text-sm font-black text-[#B91C1C]">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </span>

                                        <div>

                                            <div class="flex flex-wrap items-center gap-3 text-[10px] font-black uppercase tracking-[0.15em] text-gray-400">
                                                <span>{{ $program['date'] }}</span>
                                                <span class="text-gray-300">•</span>
                                                <span>{{ $program['location'] }}</span>
                                            </div>

                                            <h3 class="mt-2 text-xl font-black group-hover:text-[#B91C1C] transition-colors sm:text-2xl">
                                                {{ $program['title'] }}
                                            </h3>

                                            <p class="mt-2 max-w-xl text-sm leading-6 text-gray-500">
                                                {{ $program['description'] }}
                                            </p>

                                        </div>

                                        <span
                                            class="text-xl text-gray-300 transition group-hover:translate-x-1 group-hover:text-[#B91C1C]">
                                            →
                                        </span>

                                    </a>
                                @empty
                                    <div class="border-b border-[#DED9CF] py-16 text-center">
                                        <p class="text-sm font-semibold text-gray-500">Belum ada program kerja dipublikasikan</p>
                                    </div>
                                @endforelse

                            </div>

                            @if ($programEvents->isNotEmpty())
                                <div class="mt-8">
                                    <a href="{{ url('/events') }}"
                                        class="text-sm font-black text-[#B91C1C] underline decoration-[#F4C430] decoration-2 underline-offset-8">
                                        Lihat semua kegiatan →
                                    </a>
                                </div>
                            @endif

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
                    @elseif ($newsData->isEmpty())
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


                    <button @click="openAspirasi = true"
                        class="mt-9 bg-white px-8 py-4 text-sm font-black text-[#B91C1C] transition hover:bg-[#FFFDF7]">
                        Sampaikan Aspirasi →
                    </button>

                </div>

                {{-- Modal Aspirasi --}}
                <div x-show="openAspirasi" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/60 p-4"
                    style="display:none" x-transition.opacity>
                    <div class="w-full max-w-lg bg-white p-8" @click.away="openAspirasi = false">
                        <h3 class="text-2xl font-black text-[#171717]">Sampaikan aspirasimu</h3>
                        <p class="mt-2 text-sm leading-6 text-gray-500">
                            Kritik, ide, atau masukanmu akan dikirim ke email resmi SEMAFE.
                        </p>

                        <form class="mt-6 space-y-4"
                            @submit.prevent="sendAspirasi($event)">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nama (opsional)</label>
                                <input type="text" x-model="aspirasi.nama" placeholder="Nama atau anonim"
                                    class="w-full border border-[#DED9CF] px-3 py-2.5 text-sm focus:border-[#B91C1C] focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Aspirasi</label>
                                <textarea x-model="aspirasi.pesan" rows="5" required
                                    placeholder="Tulis aspirasimu di sini..."
                                    class="w-full border border-[#DED9CF] px-3 py-2.5 text-sm focus:border-[#B91C1C] focus:outline-none"></textarea>
                            </div>
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="openAspirasi = false"
                                    class="px-5 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-100 transition">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="bg-[#B91C1C] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-[#991B1B]">
                                    Kirim Aspirasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </section>

        </main>

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

                                <a href="{{ url('/events') }}" class="block hover:text-white">
                                    Kegiatan
                                </a>

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

    </div>


    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {

                Alpine.data('semafeLanding', () => ({

                    mobileMenu: false,

                    activeSection: 'home',

                    openAspirasi: false,

                    aspirasi: {
                        nama: '',
                        pesan: ''
                    },

                    sendAspirasi(event) {
                        const pengirim = this.aspirasi.nama ? this.aspirasi.nama : 'Anonim';
                        const subject = encodeURIComponent('Aspirasi Mahasiswa - ' + pengirim);
                        const body = encodeURIComponent(this.aspirasi.pesan + '\n\n— ' + pengirim);

                        window.location.href = 'mailto:semafeunpi25@gmail.com?subject=' + subject + '&body=' + body;

                        this.openAspirasi = false;
                        this.aspirasi = {
                            nama: '',
                            pesan: ''
                        };
                    },

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
        [x-cloak] {
            display: none !important;
        }

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
