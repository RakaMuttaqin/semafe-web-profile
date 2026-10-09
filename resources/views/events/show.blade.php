@extends('components.layouts.guest')

@section('title', 'Kegiatan')

@use(App\Models\Events)

@section('content')
@php
    $event = Events::where('slug', $slug)->firstOrFail();

    $fmt = fn($d, $f) => $d ? \Carbon\Carbon::parse($d)->translatedFormat($f) : null;
    $isUpcoming = $event->start_at && \Carbon\Carbon::parse($event->start_at)->isFuture();

    $data = [
        'id' => $event->id,
        'title' => $event->title,
        'slug' => $event->slug,
        'description' => $event->description,
        'image' => $event->image ?: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1600&q=85',
        'location' => $event->location ?? '-',
        'day' => $fmt($event->start_at, 'd') ?? '-',
        'month' => strtoupper($fmt($event->start_at, 'M') ?? '-'),
        'year' => $fmt($event->start_at, 'Y') ?? '-',
        'time' => ($fmt($event->start_at, 'H:i') ?? '-') . ' - ' . ($fmt($event->end_at, 'H:i') ?? '-') . ' WIB',
        'date_full' => $fmt($event->start_at, 'l, d F Y') ?? '-',
        'status' => $event->status,
        'is_upcoming' => $isUpcoming,
    ];

    $related = Events::where('status', 'published')
        ->where('id', '!=', $event->id)
        ->orderBy('start_at')
        ->take(3)
        ->get()
        ->map(function ($e) {
            return [
                'id' => $e->id,
                'title' => $e->title,
                'slug' => $e->slug,
                'image' => $e->image ?: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=85',
                'date' => optional($e->start_at)->translatedFormat('d M Y') ?? '-',
                'location' => $e->location ?? '-',
            ];
        });
@endphp

<div x-data="eventShow()" class="min-h-screen bg-[#FFFDF7] text-[#171717]">
    {{-- Cover --}}
    <div class="relative h-[45vh] min-h-80 w-full overflow-hidden bg-[#171717]">
        <img src="{{ $data['image'] }}" alt="{{ $data['title'] }}" class="h-full w-full object-cover opacity-70">
        <div class="absolute inset-0 bg-gradient-to-t from-[#171717] via-[#171717]/40 to-transparent"></div>

        {{-- Header --}}
        <header class="absolute inset-x-0 top-0 z-10">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6 lg:px-8">
                <a href="/" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center bg-[#B91C1C]">
                        <span class="text-lg font-black text-white">S</span>
                    </div>
                    <div class="text-left leading-none">
                        <div class="text-lg font-black tracking-tight text-white">SEMAFE</div>
                        <div class="mt-1 text-[9px] font-bold uppercase tracking-[0.18em] text-[#F4C430]">Fakultas
                            Ekonomi</div>
                    </div>
                </a>

                <nav class="hidden items-center gap-7 lg:flex">
                    <a href="/" class="text-[13px] font-semibold text-white/70 transition hover:text-white">Beranda</a>
                    <a href="/news" class="text-[13px] font-semibold text-white/70 transition hover:text-white">Berita</a>
                    <a href="/events" class="text-[13px] font-semibold text-white">Kegiatan</a>
                </nav>

                <a href="/login" class="bg-white px-5 py-2.5 text-xs font-black text-[#B91C1C] transition hover:bg-[#B91C1C] hover:text-white">
                    Login
                </a>
            </div>
        </header>

        {{-- Title block --}}
        <div class="absolute inset-x-0 bottom-0">
            <div class="mx-auto max-w-5xl px-6 pb-10 lg:px-8">
                <div class="flex items-center gap-2 text-xs font-semibold text-white/60">
                    <a href="/" class="hover:text-white">Beranda</a>
                    <span>/</span>
                    <a href="/events" class="hover:text-white">Kegiatan</a>
                    <span>/</span>
                    <span class="text-white/80">Detail</span>
                </div>

                <div class="mt-5 flex flex-wrap items-center gap-3">
                    @if ($data['is_upcoming'])
                        <span class="bg-[#F4C430] px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.15em] text-[#171717]">
                            Akan datang
                        </span>
                    @else
                        <span class="bg-white/20 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.15em] text-white">
                            Sudah selesai
                        </span>
                    @endif
                    <span class="bg-white/20 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.15em] text-white">
                        {{ $data['status'] }}
                    </span>
                </div>

                <h1 class="mt-4 max-w-3xl text-3xl font-black leading-tight text-white sm:text-5xl">{{ $data['title'] }}</h1>
            </div>
        </div>
    </div>

    {{-- Content --}}
    <main class="mx-auto max-w-5xl px-6 py-12 lg:px-8 lg:py-16">
        <div class="grid gap-10 lg:grid-cols-[1fr_320px]">
            {{-- Description --}}
            <div>
                <h2 class="text-2xl font-black">Deskripsi Kegiatan</h2>
                <div class="mt-5 whitespace-pre-line text-[15px] leading-8 text-gray-700">
                    {{ $data['description'] ?: 'Deskripsi kegiatan akan segera diperbarui.' }}
                </div>
            </div>

            {{-- Info card --}}
            <aside class="h-fit border border-[#DED9CF] bg-white p-6">
                <div class="flex items-center gap-4 border-b border-[#DED9CF] pb-5">
                    <div class="flex h-16 w-16 flex-col items-center justify-center bg-[#B91C1C] text-white">
                        <span class="text-2xl font-black leading-none">{{ $data['day'] }}</span>
                        <span class="mt-0.5 text-[10px] font-bold uppercase tracking-widest">{{ $data['month'] }}</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Tanggal</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $data['date_full'] }}</p>
                    </div>
                </div>

                <dl class="space-y-4 py-5 text-sm">
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">Waktu</dt>
                        <dd class="mt-1 font-semibold text-gray-700">{{ $data['time'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">Lokasi</dt>
                        <dd class="mt-1 font-semibold text-gray-700">{{ $data['location'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold uppercase tracking-wider text-gray-400">Status</dt>
                        <dd class="mt-1 font-semibold text-gray-700 capitalize">{{ $data['status'] }}</dd>
                    </div>
                </dl>

                <button @click="addToCalendar()"
                    class="w-full bg-[#B91C1C] py-3 text-sm font-black text-white transition hover:bg-[#991B1B]">
                    Tambahkan ke Kalender
                </button>
                <span x-show="added" x-transition.opacity class="mt-3 block text-center text-sm font-semibold text-green-600">
                    Ditambahkan ke kalender ✓
                </span>
            </aside>
        </div>
    </main>

    {{-- Related --}}
    @if ($related->isNotEmpty())
        <section class="border-t border-[#DED9CF] bg-[#F1EDE4] py-14">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <h2 class="text-2xl font-black">Kegiatan lainnya</h2>
                <div class="mt-8 grid gap-6 sm:grid-cols-3">
                    @foreach ($related as $r)
                        <a href="/events/{{ $r['slug'] }}" class="group border border-[#DED9CF] bg-white transition hover:shadow-lg">
                            <div class="aspect-16/10 overflow-hidden">
                                <img src="{{ $r['image'] }}" alt="{{ $r['title'] }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            </div>
                            <div class="p-5">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-[#B91C1C]">{{ $r['date'] }}</span>
                                <h3 class="mt-2 font-black leading-snug group-hover:text-[#B91C1C]">{{ $r['title'] }}</h3>
                                <p class="mt-1 text-xs text-gray-500">{{ $r['location'] }}</p>
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
    Alpine.data('eventShow', () => ({
        added: false,
        addToCalendar() {
            this.added = true;
            setTimeout(() => (this.added = false), 2500);
        }
    }));
});
</script>
@endpush