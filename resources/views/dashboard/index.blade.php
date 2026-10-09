@extends('components.layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    $newMembers = [
        ['name' => 'Andi Pratama', 'nim' => '230101', 'divisi' => 'Humas', 'divisi_color' => 'bg-indigo-100 text-indigo-700', 'position' => 'Anggota', 'photo' => 'https://ui-avatars.com/api/?name=Andi+Pratama&background=6366f1&color=fff&size=128', 'email' => 'andi@semafe.ac.id', 'batch' => '2023', 'joined' => '12 Januari 2024', 'phone' => '0812-1111-2222'],
        ['name' => 'Siti Rahma', 'nim' => '230105', 'divisi' => 'Media', 'divisi_color' => 'bg-purple-100 text-purple-700', 'position' => 'Koordinator', 'photo' => 'https://ui-avatars.com/api/?name=Siti+Rahma&background=a855f7&color=fff&size=128', 'email' => 'siti@semafe.ac.id', 'batch' => '2023', 'joined' => '20 Januari 2024', 'phone' => '0812-3333-4444'],
        ['name' => 'Budi Santoso', 'nim' => '230108', 'divisi' => 'Kegiatan', 'divisi_color' => 'bg-orange-100 text-orange-700', 'position' => 'Anggota', 'photo' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=f97316&color=fff&size=128', 'email' => 'budi@semafe.ac.id', 'batch' => '2023', 'joined' => '5 Februari 2024', 'phone' => '0812-5555-6666'],
        ['name' => 'Dewi Lestari', 'nim' => '230112', 'divisi' => 'Danus', 'divisi_color' => 'bg-green-100 text-green-700', 'position' => 'Staff', 'photo' => 'https://ui-avatars.com/api/?name=Dewi+Lestari&background=22c55e&color=fff&size=128', 'email' => 'dewi@semafe.ac.id', 'batch' => '2023', 'joined' => '18 Februari 2024', 'phone' => '0812-7777-8888'],
        ['name' => 'Rizki Maulana', 'nim' => '230115', 'divisi' => 'PSDM', 'divisi_color' => 'bg-blue-100 text-blue-700', 'position' => 'Anggota', 'photo' => 'https://ui-avatars.com/api/?name=Rizki+Maulana&background=3b82f6&color=fff&size=128', 'email' => 'rizki@semafe.ac.id', 'batch' => '2023', 'joined' => '25 Februari 2024', 'phone' => '0812-9999-0000'],
    ];

    $divisiStats = [
        ['name' => 'Humas', 'count' => 28, 'percent' => 22, 'color' => '#6366f1', 'slug' => 'humas', 'coordinator' => 'Andi Pratama', 'description' => 'Menjalin hubungan eksternal dan publikasi kegiatan.'],
        ['name' => 'Media', 'count' => 22, 'percent' => 17, 'color' => '#a855f7', 'slug' => 'media', 'coordinator' => 'Siti Rahma', 'description' => 'Mengelola dokumentasi, desain, dan media sosial.'],
        ['name' => 'Kegiatan', 'count' => 35, 'percent' => 28, 'color' => '#f97316', 'slug' => 'kegiatan', 'coordinator' => 'Budi Santoso', 'description' => 'Menyelenggarakan program kerja dan event mahasiswa.'],
        ['name' => 'Danus', 'count' => 18, 'percent' => 14, 'color' => '#22c55e', 'slug' => 'danus', 'coordinator' => 'Dewi Lestari', 'description' => 'Mengelola dana usaha dan kewirausahaan.'],
        ['name' => 'PSDM', 'count' => 24, 'percent' => 19, 'color' => '#3b82f6', 'slug' => 'psdm', 'coordinator' => 'Rizki Maulana', 'description' => 'Pengembangan SDM, rekrutmen, dan kesejahteraan anggota.'],
    ];

    $upcomingEvents = [
        ['day' => '15', 'month' => 'DEC', 'title' => 'Rapat Koordinasi Bulanan', 'divisi' => 'Semua Divisi', 'time' => '19:00 WIB', 'place' => 'Sekretariat SEMAFE', 'desc' => 'Evaluasi program kerja bulan November dan rencana kegiatan Desember ditutup dengan rencana kerja akhir semester.'],
        ['day' => '20', 'month' => 'DEC', 'title' => 'Workshop Desain Grafis', 'divisi' => 'Media', 'time' => '14:00 WIB', 'place' => 'Lab Komputer FE', 'desc' => 'Pelatihan desain grafis untuk seluruh anggota media. Materi: Figma, Canva, dan tipografi.'],
        ['day' => '25', 'month' => 'DEC', 'title' => 'Bakti Sosial Akhir Tahun', 'divisi' => 'Kegiatan', 'time' => '08:00 WIB', 'place' => 'Panti Asuhan Al-Ikhlas', 'desc' => 'Kunjungan sosial dan santunan anak yatim sebagai wujud kepedulian SEMAFE.'],
    ];

    $announcements = [
        ['title' => 'Pendaftaran Anggota Baru Dibuka', 'content' => 'Pendaftaran periode Januari 2027 dibuka mulai 1 Januari. Silakan hubungi divisi PSDM untuk informasi lebih lanjut.', 'date' => '10 Des 2026', 'author' => 'Pengurus Harian', 'priority' => 'Tinggi'],
        ['title' => 'Jadwal Rapat Mingguan Diperbarui', 'content' => 'Rapat koor bergeser ke hari Rabu pukul 19:00 WIB efektif minggu depan.', 'date' => '8 Des 2026', 'author' => 'Sekretaris', 'priority' => 'Sedang'],
        ['title' => 'Pengumpulan Laporan Bulanan', 'content' => 'Batas pengumpulan laporan divisi tanggal 28 setiap bulan. Diharapkan tepat waktu.', 'date' => '5 Des 2026', 'author' => 'Sekretaris', 'priority' => 'Normal'],
    ];
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
                    <p class="text-3xl font-bold text-gray-900 mt-1" x-text="stats.members"></p>
                    <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        +12% dari bulan lalu
                    </p>
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
                    <p class="text-3xl font-bold text-gray-900 mt-1" x-text="stats.divisions"></p>
                    <p class="text-xs text-gray-500 mt-1">3 divisi baru semester ini</p>
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
                    <p class="text-3xl font-bold text-gray-900 mt-1" x-text="stats.roles"></p>
                    <p class="text-xs text-gray-500 mt-1">Termasuk koordinator & staff</p>
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
                    <p class="text-3xl font-bold text-gray-900 mt-1" x-text="stats.users"></p>
                    <p class="text-xs text-gray-500 mt-1">2 admin, 10 operator</p>
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
                        @foreach ($newMembers as $m)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-4">
                                    <img src="{{ $m['photo'] }}" alt="{{ $m['name'] }}"
                                        class="w-10 h-10 rounded-full object-cover ring-2 ring-white shadow-sm">
                                </td>
                                <td class="py-4 font-medium text-gray-900">{{ $m['name'] }}</td>
                                <td class="py-4 text-gray-500">{{ $m['nim'] }}</td>
                                <td class="py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $m['divisi_color'] }}">{{ $m['divisi'] }}</span>
                                </td>
                                <td class="py-4 text-gray-700">{{ $m['position'] }}</td>
                                <td class="py-4 text-right space-x-2">
                                    <button @click="openMember({{ Js::from($m) }})"
                                        class="px-3 py-1.5 text-xs text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors cursor-pointer">Detail</button>
                                    <button @click="copyContact({{ Js::from($m) }})"
                                        class="px-3 py-1.5 text-xs text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">Kontak</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Statistik Divisi --}}
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Statistik Divisi</h3>
            <ul class="space-y-3">
                @foreach ($divisiStats as $d)
                    <li>
                        <button @click="openDivision({{ Js::from($d) }})"
                            class="w-full text-left group cursor-pointer">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700 group-hover:text-indigo-600 transition-colors">{{ $d['name'] }}</span>
                                <span class="text-sm text-gray-500">{{ $d['count'] }} anggota</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-500 group-hover:opacity-80"
                                    style="width: {{ $d['percent'] }}%; background-color: {{ $d['color'] }}"></div>
                            </div>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mt-6 xl:grid-cols-2">
        {{-- Kegiatan Mendatang --}}
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Kegiatan Mendatang</h3>
                <span class="text-xs text-gray-400">{{ count($upcomingEvents) }} kegiatan</span>
            </div>
            <div class="space-y-3">
                @foreach ($upcomingEvents as $e)
                    <button @click="openEvent({{ Js::from($e) }})"
                        class="w-full flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-indigo-50 transition-colors text-left cursor-pointer group">
                        <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-600 transition-colors">
                            <span class="text-xl font-bold text-indigo-600 group-hover:text-white transition-colors">{{ $e['day'] }}</span>
                            <span class="text-xs text-indigo-500 group-hover:text-indigo-100 transition-colors">{{ $e['month'] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $e['title'] }}</p>
                            <p class="text-sm text-gray-500">{{ $e['divisi'] }} • {{ $e['time'] }}</p>
                        </div>
                        <span class="text-gray-300 group-hover:text-indigo-500 transition-colors">→</span>
                    </button>
                @endforeach
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
                @foreach ($announcements as $i => $a)
                    <button @click="openAnnouncement({{ Js::from($a) }})"
                        class="w-full p-3 bg-gray-50 rounded-lg border-l-4 border-indigo-500 hover:bg-indigo-50 transition-colors text-left cursor-pointer group relative">
                        <span x-show="!readAnnouncements.includes({{ $i }})"
                            class="absolute top-3 right-3 w-2 h-2 bg-red-500 rounded-full"></span>
                        <p class="font-medium text-gray-900 pr-4">{{ $a['title'] }}</p>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $a['content'] }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="text-xs text-gray-400">{{ $a['date'] }}</span>
                            <span class="text-gray-300">•</span>
                            <span class="text-xs px-1.5 py-0.5 rounded"
                                :class="{
                                    'bg-red-100 text-red-600': '{{ $a['priority'] }}' === 'Tinggi',
                                    'bg-yellow-100 text-yellow-600': '{{ $a['priority'] }}' === 'Sedang',
                                    'bg-gray-100 text-gray-600': '{{ $a['priority'] }}' === 'Normal'
                                }">{{ $a['priority'] }}</span>
                        </div>
                    </button>
                @endforeach
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
                            <dd class="font-medium text-gray-800" x-text="active.member.divisi"></dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Jabatan</dt>
                            <dd class="font-medium text-gray-800" x-text="active.member.position"></dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Angkatan</dt>
                            <dd class="font-medium text-gray-800" x-text="active.member.batch"></dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Email</dt>
                            <dd class="font-medium text-gray-800" x-text="active.member.email"></dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Telepon</dt>
                            <dd class="font-medium text-gray-800" x-text="active.member.phone"></dd>
                        </div>
                        <div class="flex justify-between py-2">
                            <dt class="text-gray-500">Bergabung</dt>
                            <dd class="font-medium text-gray-800" x-text="active.member.joined"></dd>
                        </div>
                    </dl>
                    <div class="flex justify-end gap-2 mt-6">
                        <button @click="copyContact(active.member)"
                            class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer">Salin
                            Kontak</button>
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
                    <p class="mt-4 text-sm leading-6 text-gray-600" x-text="active.division.description"></p>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Koordinator</dt>
                            <dd class="font-medium text-gray-800" x-text="active.division.coordinator"></dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Porsi anggota</dt>
                            <dd class="font-medium text-gray-800" x-text="active.division.percent + '%'"></dd>
                        </div>
                    </dl>
                    <div class="flex justify-end gap-2 mt-6">
                        <a :href="'/member/?divisi=' + active.division.slug"
                            class="px-4 py-2 text-sm text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">Lihat
                            Anggota</a>
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
                            <p class="text-sm text-gray-500" x-text="active.event.divisi + ' • ' + active.event.time"></p>
                        </div>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-gray-600" x-text="active.event.desc"></p>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-50">
                            <dt class="text-gray-500">Lokasi</dt>
                            <dd class="font-medium text-gray-800" x-text="active.event.place"></dd>
                        </div>
                        <div class="flex justify-between py-2">
                            <dt class="text-gray-500">Waktu</dt>
                            <dd class="font-medium text-gray-800" x-text="active.event.time + ' WIB'"></dd>
                        </div>
                    </dl>
                    <div class="flex justify-end gap-2 mt-6">
                        <button @click="addToCalendar(active.event)"
                            class="px-4 py-2 text-sm text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors cursor-pointer">Ingatkan
                            Saya</button>
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
                    <div class="flex items-start justify-between gap-3">
                        <h3 class="text-lg font-bold text-gray-900" x-text="active.announcement.title"></h3>
                        <span class="text-xs px-2 py-1 rounded-full shrink-0"
                            :class="{
                                'bg-red-100 text-red-600': active.announcement.priority === 'Tinggi',
                                'bg-yellow-100 text-yellow-600': active.announcement.priority === 'Sedang',
                                'bg-gray-100 text-gray-600': active.announcement.priority === 'Normal'
                            }"
                            x-text="active.announcement.priority"></span>
                    </div>
                    <p class="mt-3 text-sm leading-6 text-gray-600" x-text="active.announcement.content"></p>
                    <div class="flex items-center gap-2 mt-4 text-xs text-gray-400">
                        <span x-text="active.announcement.author"></span>
                        <span>•</span>
                        <span x-text="active.announcement.date"></span>
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
        stats: {
            members: 127,
            divisions: 8,
            roles: 15,
            users: 12,
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
            this.modals.announcement = true;
        },

        copyContact(member) {
            const text = `${member.name} — ${member.email} / ${member.phone}`;
            if (navigator.clipboard?.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    this.notify('Kontak ' + member.name + ' disalin ke clipboard');
                });
            } else {
                this.notify('Kontak: ' + text);
            }
        },

        addToCalendar(event) {
            this.notify('Pengingat ditambahkan: ' + event.title);
            this.modals.event = false;
        },

        markAllRead() {
            this.readAnnouncements = [0, 1, 2];
            this.notify('Semua pengumuman ditandai sudah dibaca');
        },
    }));
});
</script>
@endpush