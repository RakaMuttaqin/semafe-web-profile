@extends('components.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $newMembers = [
            [
                'name' => 'Andi Pratama',
                'nim' => '230101',
                'divisi' => 'Humas',
                'divisi_color' => 'bg-indigo-100 text-indigo-700',
                'position' => 'Anggota',
                'photo' => 'https://ui-avatars.com/api/?name=Andi+Pratama&background=6366f1&color=fff&size=128',
            ],
            [
                'name' => 'Siti Rahma',
                'nim' => '230105',
                'divisi' => 'Media',
                'divisi_color' => 'bg-purple-100 text-purple-700',
                'position' => 'Koordinator',
                'photo' => 'https://ui-avatars.com/api/?name=Siti+Rahma&background=a855f7&color=fff&size=128',
            ],
            [
                'name' => 'Budi Santoso',
                'nim' => '230108',
                'divisi' => 'Kegiatan',
                'divisi_color' => 'bg-orange-100 text-orange-700',
                'position' => 'Anggota',
                'photo' => 'https://ui-avatars.com/api/?name=Budi+Santoso&background=f97316&color=fff&size=128',
            ],
            [
                'name' => 'Dewi Lestari',
                'nim' => '230112',
                'divisi' => 'Danus',
                'divisi_color' => 'bg-green-100 text-green-700',
                'position' => 'Staff',
                'photo' => 'https://ui-avatars.com/api/?name=Dewi+Lestari&background=22c55e&color=fff&size=128',
            ],
            [
                'name' => 'Rizki Maulana',
                'nim' => '230115',
                'divisi' => 'PSDM',
                'divisi_color' => 'bg-blue-100 text-blue-700',
                'position' => 'Anggota',
                'photo' => 'https://ui-avatars.com/api/?name=Rizki+Maulana&background=3b82f6&color=fff&size=128',
            ],
        ];

        $divisiStats = [
            ['name' => 'Humas', 'count' => 28, 'percent' => 22],
            ['name' => 'Media', 'count' => 22, 'percent' => 17],
            ['name' => 'Kegiatan', 'count' => 35, 'percent' => 28],
            ['name' => 'Danus', 'count' => 18, 'percent' => 14],
            ['name' => 'PSDM', 'count' => 24, 'percent' => 19],
        ];

        $upcomingEvents = [
            [
                'day' => '15',
                'month' => 'DEC',
                'title' => 'Rapat Koordinasi Bulanan',
                'divisi' => 'Semua Divisi',
                'time' => '19:00 WIB',
            ],
            [
                'day' => '20',
                'month' => 'DEC',
                'title' => 'Workshop Desain Grafis',
                'divisi' => 'Media',
                'time' => '14:00 WIB',
            ],
            [
                'day' => '25',
                'month' => 'DEC',
                'title' => 'Bakti Sosial Akhir Tahun',
                'divisi' => 'Kegiatan',
                'time' => '08:00 WIB',
            ],
        ];

        $announcements = [
            [
                'title' => 'Pendaftaran Anggota Baru Dibuka',
                'content' => 'Pendaftaran periode Januari 2027 dibuka mulai 1 Januari. Silakan hubungi divisi PSDM.',
                'date' => '10 Des 2026',
            ],
            [
                'title' => 'Jadwal Rapat Mingguan Diperbarui',
                'content' => 'Rapat koor bergeser ke hari Rabu pukul 19:00 WIB efektif minggu depan.',
                'date' => '8 Des 2026',
            ],
            [
                'title' => 'Pengumpulan Laporan Bulanan',
                'content' => 'Batas pengumpulan laporan divisi tanggal 28 setiap bulan. Diharapkan tepat waktu.',
                'date' => '5 Des 2026',
            ],
        ];
    @endphp

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ Auth::user()->name }}</h2>
        <p class="text-sm text-gray-500">Ringkasan organisasi SEMAFE — {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Anggota</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">127</p>
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
        </div>

        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Divisi Aktif</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">8</p>
                    <p class="text-xs text-gray-500 mt-1">3 divisi baru semester ini</p>
                </div>
                <div class="p-4 bg-green-50 rounded-xl">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Peran & Jabatan</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">15</p>
                    <p class="text-xs text-gray-500 mt-1">Termasuk koordinator & staff</p>
                </div>
                <div class="p-4 bg-yellow-50 rounded-xl">
                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pengguna Sistem</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">12</p>
                    <p class="text-xs text-gray-500 mt-1">2 admin, 10 operator</p>
                </div>
                <div class="p-4 bg-pink-50 rounded-xl">
                    <svg class="w-7 h-7 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mt-6 xl:grid-cols-3">
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100 xl:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Anggota Terbaru</h3>
                <a href="/member/" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Lihat semua →</a>
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
                                <td class="py-4 text-right">
                                    <button
                                        class="px-3 py-1.5 text-xs text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">Detail</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Statistik Divisi</h3>
            <ul class="space-y-3">
                @foreach ($divisiStats as $d)
                    <li>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $d['name'] }}</span>
                            <span class="text-sm text-gray-500">{{ $d['count'] }} anggota</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-indigo-500 h-2 rounded-full transition-all" style="width: {{ $d['percent'] }}%">
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mt-6 xl:grid-cols-2">
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Kegiatan Mendatang</h3>
            <div class="space-y-3">
                @foreach ($upcomingEvents as $e)
                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <span class="text-xl font-bold text-indigo-600">{{ $e['day'] }}</span>
                            <span class="text-xs text-indigo-500">{{ $e['month'] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 truncate">{{ $e['title'] }}</p>
                            <p class="text-sm text-gray-500">{{ $e['divisi'] }} • {{ $e['time'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Pengumuman Terbaru</h3>
            <div class="space-y-3">
                @foreach ($announcements as $a)
                    <div class="p-3 bg-gray-50 rounded-lg border-l-4 border-indigo-500">
                        <p class="font-medium text-gray-900">{{ $a['title'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $a['content'] }}</p>
                        <p class="text-xs text-gray-400 mt-2">{{ $a['date'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
