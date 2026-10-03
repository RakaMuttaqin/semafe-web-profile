@extends('components.layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Welcome --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Selamat Datang, Admin</h2>
        <p class="text-sm text-gray-500">Ringkasan organisasi SEMAFE — {{ now()->translatedFormat('d F Y') }}</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Anggota</p>
                    <p class="text-2xl font-bold text-gray-900">48</p>
                </div>
                <div class="p-3 bg-indigo-100 rounded-full">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Divisi</p>
                    <p class="text-2xl font-bold text-gray-900">8</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Peran</p>
                    <p class="text-2xl font-bold text-gray-900">5</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pengguna</p>
                    <p class="text-2xl font-bold text-gray-900">12</p>
                </div>
                <div class="p-3 bg-pink-100 rounded-full">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 mt-6 xl:grid-cols-3">
        {{-- Anggota Terbaru --}}
        <div class="p-6 bg-white rounded-lg shadow xl:col-span-2">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Anggota Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead>
                        <tr class="text-gray-500 border-b">
                            <th class="pb-2 font-medium">Nama</th>
                            <th class="pb-2 font-medium">NIM</th>
                            <th class="pb-2 font-medium">Divisi</th>
                            <th class="pb-2 font-medium">Jabatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr>
                            <td class="py-3">Andi Pratama</td>
                            <td class="py-3">230101</td>
                            <td class="py-3">Humas</td>
                            <td class="py-3">Anggota</td>
                        </tr>
                        <tr>
                            <td class="py-3">Siti Rahma</td>
                            <td class="py-3">230105</td>
                            <td class="py-3">Media</td>
                            <td class="py-3">Koordinator</td>
                        </tr>
                        <tr>
                            <td class="py-3">Budi Santoso</td>
                            <td class="py-3">230108</td>
                            <td class="py-3">Kegiatan</td>
                            <td class="py-3">Anggota</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Divisi --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <h3 class="mb-4 text-lg font-semibold text-gray-800">Divisi</h3>
            <ul class="space-y-3">
                <li class="flex items-center justify-between"><span>Humas</span><span class="px-2 py-0.5 text-xs bg-indigo-100 text-indigo-700 rounded">6 orang</span></li>
                <li class="flex items-center justify-between"><span>Media</span><span class="px-2 py-0.5 text-xs bg-indigo-100 text-indigo-700 rounded">5 orang</span></li>
                <li class="flex items-center justify-between"><span>Kegiatan</span><span class="px-2 py-0.5 text-xs bg-indigo-100 text-indigo-700 rounded">8 orang</span></li>
                <li class="flex items-center justify-between"><span>Danus</span><span class="px-2 py-0.5 text-xs bg-indigo-100 text-indigo-700 rounded">4 orang</span></li>
            </ul>
        </div>
    </div>
@endsection
