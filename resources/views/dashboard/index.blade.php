@extends('components.layouts.app')

@section('title', 'Dashboard')

@section('content')
@use(App\Models\Division)
@use(App\Models\Events)
@use(App\Models\Member)
@use(App\Models\News)
@use(App\Models\Role)
@use(App\Models\User)

@php
    /**
     * Helper format tanggal yang aman (menerima string, Carbon, atau null).
     */
    $formatDate = function ($date, $format) {
        if (empty($date)) {
            return null;
        }

        return \Carbon\Carbon::parse($date)->translatedFormat($format);
    };

    /**
     * Helper: resolve URL foto/thumbnail.
     * Mendukung nilai berupa URL penuh, path file storage, atau fallback avatar.
     */
    $resolveMedia = function ($path, $seed = null, $size = 128) {
        if ($path && str_starts_with($path, 'http')) {
            return $path;
        }

        if ($path && \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return \Illuminate\Support\Facades\Storage::url($path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($seed ?? 'User') . '&background=6366f1&color=fff&size=' . $size;
    };

    /* ---------- Statistik ---------- */
    $stats = [
        'members' => Member::count(),
        'divisions' => Division::count(),
        'roles' => Role::count(),
        'users' => User::count(),
    ];

    /* ---------- Anggota terbaru ---------- */
    $divisionNames = Division::pluck('name', 'id');

    $newMembers = Member::orderByDesc('id')
        ->take(5)
        ->get()
        ->map(function ($m) use ($resolveMedia, $divisionNames) {
            return [
                'id' => $m->id,
                'name' => $m->name,
                'nim' => $m->nim,
                'position' => $m->position,
                'division' => $divisionNames[$m->division_id] ?? '-',
                'division_id' => $m->division_id,
                'joined' => $m->created_at?->translatedFormat('d F Y') ?? '-',
                'photo' => $resolveMedia($m->photos, $m->name),
            ];
        });

    /* ---------- Statistik divisi ---------- */
    $totalMembers = max($stats['members'], 1);

    $divisiStats = Division::withCount('members')
        ->orderByDesc('members_count')
        ->get()
        ->map(function ($d) use ($totalMembers) {
            $palette = ['#6366f1', '#a855f7', '#f97316', '#22c55e', '#3b82f6', '#ef4444', '#14b8a6', '#f59e0b'];

            return [
                'id' => $d->id,
                'name' => $d->name,
                'slug' => $d->slug,
                'count' => $d->members_count,
                'percent' => round($d->members_count / $totalMembers * 100),
                'color' => $palette[($d->id - 1) % count($palette)],
                'coordinator' => $d->members()->where('position', 'like', '%oordinator%')->value('name') ?? '-',
            ];
        });

    /* ---------- Kegiatan mendatang ---------- */
    $upcomingEvents = Events::where('start_at', '>=', now())
        ->orderBy('start_at')
        ->take(3)
        ->get()
        ->map(function ($e) use ($formatDate) {
            return [
                'id' => $e->id,
                'title' => $e->title,
                'slug' => $e->slug,
                'description' => $e->description,
                'location' => $e->location ?? '-',
                'day' => $formatDate($e->start_at, 'd') ?? '-',
                'month' => strtoupper($formatDate($e->start_at, 'M') ?? '-'),
                'time' => ($formatDate($e->start_at, 'H:i') ?? '-') . ' - ' . ($formatDate($e->end_at, 'H:i') ?? '-') . ' WIB',
                'date_display' => ($formatDate($e->start_at, 'd M Y, H:i') ?? '-') . ' WIB',
                'status' => $e->status,
            ];
        });

    /* ---------- Pengumuman terbaru ---------- */
    $announcements = News::with('users')
        ->latest('published_at')
        ->take(3)
        ->get()
        ->map(function ($n) use ($formatDate) {
            return [
                'id' => $n->id,
                'title' => $n->title,
                'slug' => $n->slug,
                'content' => $n->content,
                'excerpt' => \Illuminate\Support\Str::limit(strip_tags($n->content), 120),
                'thumbnail' => $n->thumbnail,
                'date' => $formatDate($n->published_at, 'd M Y') ?? '-',
                'author' => $n->users?->name ?? 'Admin',
                'status' => $n->status,
            ];
        });
@endphp

<div x-data="dashboardApp()" class="relative">

    {{-- Toast --}}
    <div x-show="toast.show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed top-20 right-6 z-[60] flex items-center gap-3 px-4 py-3 bg-white rounded-xl shadow-lg border border-gray-100"
        style="display:none">
        <div class="p-1.5 rounded-lg" :class="toast.type === 'success' ? 'bg-green-100' : 'bg-indigo-100'">
            <svg class="w-4 h-4" :class="toast.type === 'success' ? 'text-green-600' : 'text-indigo-600'"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    :d="toast.type === 'success' ? 'M5 13l4 4L19 7' : 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'" />
            </svg>
        </div>
        <p class="text-sm font-medium text-gray-800" x-text="toast.message"></p>
    </div>

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}</h2>
            <p class="text-sm text-gray-500">Ringkasan organisasi SEMAFE — {{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <button @click="refreshData()"
            class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-xl hover:bg-indigo-600 hover:text-white hover:border-indigo-600 shadow-sm transition-all duration-200 cursor-pointer">
            <svg class="w-4 h-4" :class="refreshing && 'animate-spin'" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span x-text="refreshing ? 'Memuat...' : 'Refresh Data'"></span>
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <a href="{{ url('/member') }}"
            class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Anggota</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['members'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Terdaftar di sistem</p>
                </div>
                <div class="p-4 bg-indigo-50 rounded-xl">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ url('/division') }}"
            class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Divisi Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['divisions'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Divisi terdaftar</p>
                </div>
                <div class="p-4 bg-green-50 rounded-xl">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ url('/role') }}"
            class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Peran & Jabatan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['roles'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Role pengguna sistem</p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-xl">
                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
        </a>

        <a href="{{ url('/user') }}"
            class="block p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pengguna Sistem</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['users'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Akun terdaftar</p>
                </div>
                <div class="p-4 bg-pink-50 rounded-xl">
                    <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </a>
    </div>

    {{-- Aksi Cepat --}}
    <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 mt-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            <a href="{{ url('/admin/news') }}"
                class="flex flex-col items-center gap-3 p-5 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors cursor-pointer group">
                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700">Tulis Berita</span>
            </a>

            <a href="{{ url('/admin/events') }}"
                class="flex flex-col items-center gap-3 p-5 bg-green-50 rounded-xl hover:bg-green-100 transition-colors cursor-pointer group">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-green-700">Tambah Kegiatan</span>
            </a>

            <a href="{{ url('/member') }}"
                class="flex flex-col items-center gap-3 p-5 bg-yellow-50 rounded-xl hover:bg-yellow-100 transition-colors cursor-pointer group">
                <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-yellow-700">Tambah Anggota</span>
            </a>

            <a href="{{ url('/division') }}"
                class="flex flex-col items-center gap-3 p-5 bg-pink-50 rounded-xl hover:bg-pink-100 transition-colors cursor-pointer group">
                <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="text-sm font-semibold text-gray-700 group-hover:text-pink-700">Kelola Divisi</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mt-6 xl:grid-cols-3">
        {{-- Anggota Terbaru --}}
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 xl:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Anggota Terbaru</h3>
                <a href="{{ url('/member') }}"
                    class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Lihat semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 border-b border-gray-100">
                            <th class="pb-3 font-medium text-left">Foto</th>
                            <th class="pb-3 font-medium text-left">Nama</th>
                            <th class="pb-3 font-medium text-left">NIM</th>
                            <th class="pb-3 font-medium text-left">Divisi</th>
                            <th class="pb-3 font-medium text-left">Jabatan</th>
                            <th class="pb-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($newMembers as $m)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4">
                                    <img src="{{ $m['photo'] }}" alt="{{ $m['name'] }}"
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm">
                                </td>
                                <td class="py-4 font-medium text-gray-900">{{ $m['name'] }}</td>
                                <td class="py-4 text-gray-500 font-mono">{{ $m['nim'] }}</td>
                                <td class="py-4 text-gray-700">{{ $m['division'] }}</td>
                                <td class="py-4 text-gray-700">{{ $m['position'] }}</td>
                                <td class="py-4 text-right">
                                    <button @click="openMember({{ Js::from($m) }})"
                                        class="px-3 py-1.5 text-xs text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors cursor-pointer">Detail</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-gray-400">
                                    Belum ada data anggota
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Statistik Divisi --}}
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Statistik Divisi</h3>
            <ul class="space-y-3">
                @forelse ($divisiStats as $d)
                    <li>
                        <button @click="openDivision({{ Js::from($d) }})" class="w-full text-left group cursor-pointer">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition-colors">{{ $d['name'] }}</span>
                                <span class="text-sm text-gray-500">{{ $d['count'] }} anggota</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-500 group-hover:opacity-80"
                                    style="width: {{ max($d['percent'], 2) }}%; background-color: {{ $d['color'] }}"></div>
                            </div>
                        </button>
                    </li>
                @empty
                    <li class="py-6 text-center text-gray-400">Belum ada data divisi</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mt-6 xl:grid-cols-2">
        {{-- Kegiatan Mendatang --}}
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Kegiatan Mendatang</h3>
                <span class="text-xs text-gray-400">{{ $upcomingEvents->count() }} kegiatan</span>
            </div>
            <div class="space-y-3">
                @forelse ($upcomingEvents as $e)
                    <button @click="openEvent({{ Js::from($e) }})"
                        class="w-full flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-indigo-50 transition-colors text-left cursor-pointer group">
                        <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-600 transition-colors">
                            <span class="text-xl font-bold text-indigo-600 group-hover:text-white transition-colors">{{ $e['day'] }}</span>
                            <span class="text-xs text-indigo-500 group-hover:text-indigo-100 transition-colors">{{ $e['month'] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $e['title'] }}</p>
                            <p class="text-sm text-gray-500">{{ $e['location'] }} • {{ $e['time'] }}</p>
                        </div>
                        <span class="text-gray-300 group-hover:text-indigo-500 transition-colors">→</span>
                    </button>
                @empty
                    <div class="py-10 text-center text-gray-400">Tidak ada kegiatan mendatang</div>
                @endforelse
            </div>
        </div>

        {{-- Pengumuman --}}
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Pengumuman Terbaru</h3>
                <button @click="markAllRead()"
                    class="text-xs text-indigo-600 hover:text-indigo-700 font-medium cursor-pointer">Tandai sudah dibaca</button>
            </div>
            <div class="space-y-3">
                @forelse ($announcements as $i => $a)
                    <button @click="openAnnouncement({{ Js::from($a) }})"
                        class="w-full p-3 bg-gray-50 rounded-lg border-l-4 border-indigo-500 hover:bg-indigo-50 transition-colors text-left cursor-pointer group relative">
                        <span x-show="!readAnnouncements.includes({{ $a['id'] }})"
                            class="absolute top-3 right-3 w-2 h-2 bg-red-500 rounded-full"></span>
                        <p class="font-medium text-gray-900 pr-4">{{ $a['title'] }}</p>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $a['excerpt'] }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs text-gray-400">{{ $a['date'] }}</span>
                            <span class="text-gray-300">•</span>
                            <span class="text-xs text-gray-400">{{ $a['author'] }}</span>
                        </div>
                    </button>
                @empty
                    <div class="py-10 text-center text-gray-400">Belum ada pengumuman</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Modal Detail Anggota --}}
    <div x-show="modals.member" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        style="display:none" x-transition.opacity>
        <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-xl" @click.away="modals.member = false">
            <template x-if="active.member">
                <div>
                    <div class="flex items-center gap-4">
                        <img :src="active.member.photo" :alt="active.member.name"
                            class="w-16 h-16 rounded-full object-cover ring-2 ring-indigo-100">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900" x-text="active.member.name"></h3>
                            <p class="text-sm text-gray-500" x-text="active.member.nim"></p>
                        </div>
                    </div>
                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Divisi</dt>
                            <dd class="font-medium text-gray-800" x-text="active.member.division"></dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Jabatan</dt>
                            <dd class="font-medium text-gray-800" x-text="active.member.position"></dd>
                        </div>
                        <div class="flex justify-between py-2">
                            <dt class="text-gray-500">Bergabung</dt>
                            <dd class="font-medium text-gray-800" x-text="active.member.joined"></dd>
                        </div>
                    </dl>
                    <div class="flex justify-end mt-6">
                        <button @click="modals.member = false"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors cursor-pointer">Tutup</button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Modal Detail Divisi --}}
    <div x-show="modals.division" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        style="display:none" x-transition.opacity>
        <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-xl" @click.away="modals.division = false">
            <template x-if="active.division">
                <div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center justify-center w-14 h-14 rounded-xl"
                            :style="'background-color: ' + active.division.color + '20'">
                            <svg class="w-7 h-7" :style="'color: ' + active.division.color" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900" x-text="active.division.name"></h3>
                            <p class="text-sm text-gray-500" x-text="active.division.count + ' anggota aktif'"></p>
                        </div>
                    </div>
                    <dl class="mt-5 space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Slug</dt>
                            <dd class="font-medium text-gray-800 font-mono" x-text="active.division.slug"></dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Porsi anggota</dt>
                            <dd class="font-medium text-gray-800" x-text="active.division.percent + '%'"></dd>
                        </div>
                        <div class="flex justify-between py-2">
                            <dt class="text-gray-500">Koordinator</dt>
                            <dd class="font-medium text-gray-800" x-text="active.division.coordinator"></dd>
                        </div>
                    </dl>
                    <div class="flex justify-end gap-2 mt-6">
                        <button @click="modals.division = false"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors cursor-pointer">Tutup</button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Modal Detail Kegiatan --}}
    <div x-show="modals.event" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        style="display:none" x-transition.opacity>
        <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-xl" @click.away="modals.event = false">
            <template x-if="active.event">
                <div>
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-indigo-100 rounded-xl flex flex-col items-center justify-center">
                            <span class="text-xl font-bold text-indigo-600" x-text="active.event.day"></span>
                            <span class="text-xs text-indigo-500" x-text="active.event.month"></span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900" x-text="active.event.title"></h3>
                            <p class="text-sm text-gray-500" x-text="active.event.date_display"></p>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-gray-600" x-text="active.event.description"></p>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Lokasi</dt>
                            <dd class="font-medium text-gray-800" x-text="active.event.location"></dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Waktu</dt>
                            <dd class="font-medium text-gray-800" x-text="active.event.time"></dd>
                        </div>
                        <div class="flex justify-between py-2">
                            <dt class="text-gray-500">Status</dt>
                            <dd class="font-medium text-gray-800" x-text="active.event.status"></dd>
                        </div>
                    </dl>
                    <div class="flex justify-end mt-6">
                        <button @click="modals.event = false"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors cursor-pointer">Tutup</button>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Modal Detail Pengumuman --}}
    <div x-show="modals.announcement" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        style="display:none" x-transition.opacity>
        <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-xl" @click.away="modals.announcement = false">
            <template x-if="active.announcement">
                <div>
                    <h3 class="text-lg font-bold text-gray-900" x-text="active.announcement.title"></h3>
                    <p class="mt-3 text-sm leading-6 text-gray-600" x-text="active.announcement.content"></p>
                    <div class="flex items-center gap-2 mt-4 text-xs text-gray-400">
                        <span x-text="active.announcement.author"></span>
                        <span>•</span>
                        <span x-text="active.announcement.date"></span>
                        <span>•</span>
                        <span x-text="active.announcement.status"></span>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button @click="modals.announcement = false"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors cursor-pointer">Tutup</button>
                    </div>
                </div>
            </template>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const announcementIds = @json($announcements->pluck('id'));

    document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardApp', () => ({
        refreshing: false,
        readAnnouncements: [],
        modals: {
            member: false,
            division: false,
            event: false,
            announcement: false,
        },
        active: {
            member: null,
            division: null,
            event: null,
            announcement: null,
        },
        toast: {
            show: false,
            message: '',
            type: 'success',
        },

        notify(message, type = 'success') {
            this.toast = { show: true, message, type };
            clearTimeout(this._toastTimer);
            this._toastTimer = setTimeout(() => {
                this.toast.show = false;
            }, 3000);
        },

        refreshData() {
            this.refreshing = true;
            setTimeout(() => {
                this.refreshing = false;
                this.notify('Data dashboard berhasil dimuat ulang');
            }, 800);
        },

        openMember(member) {
            this.active.member = member;
            this.modals.member = true;
        },

        openDivision(division) {
            this.active.division = division;
            this.modals.division = true;
        },

        openEvent(event) {
            this.active.event = event;
            this.modals.event = true;
        },

        openAnnouncement(announcement) {
            this.active.announcement = announcement;
            this.readAnnouncements = [...new Set([...this.readAnnouncements, announcement.id])];
            this.modals.announcement = true;
        },

        markAllRead() {
            this.readAnnouncements = announcementIds;
            this.notify('Semua pengumuman ditandai sudah dibaca');
        },
    }));
});
</script>
@endpush